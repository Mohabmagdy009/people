<?php

namespace App\Http\Controllers;

use App\Cluster;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\OptionsUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use App\User;
use Spatie\Permission\Models\Role;
use Auth;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Imports\UserFullImport;
use App\Activity;

use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getList()
    {
        return view('user/list');
    }

    public function profile($id)
    {
        $user = User::find($id);
        if (Auth::user()->id != $id) {
            return redirect('userList')->with('error', 'You are not user '.$user->name.'!!!');
        }

        return view('user/profile', compact('user'));
    }

    public function updatePasswordGet(User $user)
    {
        if (Auth::user()->id != $user->id) {
            return redirect()->route('updatePasswordGet',[Auth::user()->id])->with('error', 'You are not user '.$user->name.'!!!');
        }

        return view('user/updatePassword', compact('user'));
    }

    public function updatePasswordStore(PasswordUpdateRequest $request, User $user)
    {
        $inputs = $request->only('password');

        if (Auth::user()->id != $user->id) {
            return redirect()->route('updatePasswordGet',[Auth::user()->id])->with('error', 'You are not user '.$user->name.'!!!');
        }

        $user->update_password($inputs['password'],true);

        return redirect()->route('updatePasswordGet',[Auth::user()->id])->with('success', 'Password updated')->with('user',$user);
    }

    public function passwordUpdateAjax(Request $request, User $user)
    {
        $result = new \stdClass();
        $inputs = $request->all();
        $can_reset = false;

        $manager_id = $user->managers()->first()->id;
        $auth_id = Auth::user()->id;

        if ($user->id == 1) {
            $result->result = 'error';
            $result->msg = 'You cannot reset the password of the admin!!!';
        } elseif (Auth::user()->id == $user->id) {
            $result->result = 'success';
            $result->msg = 'Password updated';
            $can_reset = true;
        } elseif ($manager_id == $auth_id) {
            $result->result = 'success';
            $result->msg = 'Password updated';
            $can_reset = true;
        } elseif (Auth::user()->can('user-view-all')) {
            $result->result = 'success';
            $result->msg = 'Password updated';
            $can_reset = true;
        } else {
            $result->result = 'error';
            $result->msg = 'You have no rights to reset the password';
        }
        if ($can_reset) {
            $user->update_password($inputs['password'],true);
        }
        return json_encode($result);
    }

    public function optionsUpdate(OptionsUpdateRequest $request, $id)
    {
        $user = User::find($id);
        if (Auth::user()->id != $id) {
            return redirect('userList')->with('error', 'You are not user '.$user->name.'!!!');
        }
        $inputs = $request->all();
        //dd($inputs);
        $user->clusterboard_top = $inputs['clusterboard_top'];
        $user->revenue_product_codes = $inputs['revenue_product_codes'];
        $user->revenue_target = $inputs['revenue_target'];
        $user->order_target = $inputs['order_target'];
        $user->table_height = $inputs['table_height'];
        $user->save();

        return redirect('profile/'.$id)->with('success', 'Options updated successfully');
    }

    public function getFormCreate()
    {
        $defaultRole = config('select.defaultRole');

        if (Auth::user()->hasRole('Admin')) {
            $roles = Role::all()->pluck('name', 'id');
        } else {
            $roles = Role::where('id','!=',1)->pluck('name','id');
        }

        if (Auth::user()->can('role-assign')) {
            $role_select_disabled = 'false';
        } else {
            $role_select_disabled = 'true';
        }

        $manager_list = User::orderBy('name')->where('is_manager', '1')->pluck('name', 'id');
        $manager_list->prepend('', '');

        $clusters = Customer::where('cluster_owner', '!=', '')->groupBy('cluster_owner')->pluck('cluster_owner');

        return view('user/create_update', compact('manager_list', 'clusters', 'roles', 'role_select_disabled', 'defaultRole'))->with('action', 'create');
    }

    public function getFormUpdate(User $user)
    {

        if (Auth::user()->hasRole('Admin')) {
            $roles = Role::all()->pluck('name', 'id');
        } else {
            $roles = Role::where('id','!=',1)->pluck('name','id');
        }

        if (Auth::user()->can('role-assign')) {
            $role_select_disabled = 'false';
        } else {
            $role_select_disabled = 'true';
        }

        $manager_list = User::orderBy('name')->where('is_manager', '1')->pluck('name', 'id');
        $manager_list->prepend('', '');

        $manager = $user->managers()->pluck('manager_id');

        $clusters = Customer::where('cluster_owner', '!=', '')->groupBy('cluster_owner')->pluck('cluster_owner');
        $userCluster = $user->clusters()->pluck('cluster_owner')->toArray();

        $userRole = $user->getRoleNames()->toArray();

        return view('user/create_update', compact('manager_list', 'user', 'manager', 'roles', 'userRole', 'clusters', 'userCluster', 'role_select_disabled'))->with('action', 'update');
    }

    // this function used to create new user
    public function postFormCreate(UserCreateRequest $request)
    {
        $inputs = $request->all();
        //dd($inputs['user']);

        $user = User::create($inputs['user']);


        //dd($user);

        $user->update_password($inputs['password'],true);

        // Now we have to treat the manager
        if (isset($inputs['manager']['manager_id'])) {
            /* We need first to check that there is not already a manager defined in which case we have to delete it
            *   Because we want to remove all traces of previous managers, we do a detach without giving
            *   the manager_id as parameter.
            **/
            $user->managers()->detach();
            /* Now we need to create the link in the pivot table
            *   For this we have a function defined in our model User.php called managers()
            *   We only need to say we want to attach the manager_id to this user_id.
            **/
            $user->managers()->attach($inputs['manager']['manager_id']);
        } else {
            // In this case, we need to remove any trace of manager for this user
            $user->managers()->detach();
        }

        // Now we need to save the clusters
        if (isset($inputs['managed_clusters'])) {
            $user->clusters()->delete();
            foreach ($inputs['managed_clusters'] as $key => $value) {
                $cluster = new Cluster;
                $cluster->user_id = $user->id;
                $cluster->cluster_owner = $value;
                $cluster->save();
            }
        } else {
            //DB::table('cluster_user')->where('user_id',$user->id)->delete();
            $user->clusters()->delete();
        }

        // Now we need to save the roles
        if (isset($inputs['roles'])) {
            $user->syncRoles($inputs['roles']);
        }


        // -----------------User Created successfully-----------------------------------
        
        $user_id_for_Activity = $user->id;
        $getYear = date("Y");

        for($i=0;$i<5;$i++){
            for ($j=1; $j<53; $j++) { 
             $record = Activity::create([
                    'project_id'=>'1812', 
                    'year'=> $getYear+$i, 
                    'user_id'=>$user_id_for_Activity,
                    'month'=>$j,
                ]);

                $secondRecord = Activity::create([
                    'project_id'=>'1813', 
                    'year'=> $getYear+$i, 
                    'user_id'=>$user_id_for_Activity,
                    'month'=>$j,
                ]);   
            }
        }
        return redirect('userList')->with('success', 'Record created successfully');
    }

    public function postFormUpdate(UserUpdateRequest $request, User $user)
    {
        $inputs = $request->all();

        if ($inputs['user_id'] == 1 && ($inputs['user']['name'] != 'admin' || $inputs['user']['email'] != 'admin@orange.com')) {
            return redirect('userList')->with('error', 'You cannot change the name or the email of the admin');
        }

        $user->update($inputs['user']);



        // Now we have to treat the manager
        if (isset($inputs['manager']['manager_id'])) {
            /* We need first to check that there is not already a manager defined in which case we have to delete it
            *   Because we want to remove all traces of previous managers, we do a detach without giving
            *   the manager_id as parameter.
            **/
            $user->managers()->detach();
            /* Now we need to create the link in the pivot table
            *   For this we have a function defined in our model User.php called managers()
            *   We only need to say we want to attach the manager_id to this user_id.
            **/
            $user->managers()->attach($inputs['manager']['manager_id']);
        } else {
            // In this case, we need to remove any trace of manager for this user
            $user->managers()->detach();
        }

        // Now we need to save the clusters
        if (isset($inputs['managed_clusters'])) {
            $user->clusters()->delete();
            foreach ($inputs['managed_clusters'] as $key => $value) {
                $cluster = new Cluster;
                $cluster->user_id = $user->id;
                $cluster->cluster_owner = $value;
                $cluster->save();
            }
        } else {
            //DB::table('cluster_user')->where('user_id',$user->id)->delete();
            $user->clusters()->delete();
        }

        if ($inputs['user_id'] == 1 && !in_array(1,$inputs['roles'])) {
            return redirect('userList')->with('error', 'You cannot remove the admin role from the admin user');
        }
        // Now we need to save the roles
        if (isset($inputs['roles'])) {
            $user->syncRoles($inputs['roles']);
        }

        return redirect('userList')->with('success', 'Record updated successfully');
    }

    public function delete($id)
    {
        // When using stdClass(), we need to prepend with \ so that Laravel won't get confused...
        $result = new \stdClass();

        // First we need to verify that we don't try to delete ourselve.
        if (Auth::user()->id == $id) {
            $result->result = 'error';
            $result->msg = 'User '.Auth::user()->name.' cannot delete himself';

            return json_encode($result);
        }

        $user = User::find($id);

        if (count($user->employees)) {
            $result->result = 'error';
            $result->msg = 'Record cannot be deleted because some employees are associated to it.';

            return json_encode($result);
        }

        if (count($user->activities)) {
            $result->result = 'error';
            $result->msg = 'Record cannot be deleted because some activities are associated to it.';

            return json_encode($result);
        }

        $projects = $user->projects;
        if (count($projects)) {
            foreach ($projects as $project) {
                $project->created_by_user_id = 1;
                $project->save();
            }
        }

        $user->managers()->detach();
        $user->clusters()->delete();

        User::destroy($id);

        $result->result = 'success';
        $result->msg = 'Record deleted successfully';

        return json_encode($result);
    }

    public function ListOfusers($exclude_contractors = 0)
    {
        return $this->userRepository->getListOfUsers($exclude_contractors);
    }

    // update FTID and PIMSID function using the UsersImport class

    public function store(Request $req)
    {
        $file = $req->file;
        $data = Excel :: toArray(new UsersImport, $file);
        //dd($data);
        // var_dump($data[0][0]);
        // echo $data[0][0]['email'];
        // // DB::tables('users')->where('email',)
        
        for($i=0;$i<sizeof($data[0]);$i++){
            $fields = [
            'pimsid' => $data[0][$i]['pimsid'],
            'ftid'   => $data[0][$i]['ftid']
        ];
            DB::table('users')->where('email',$data[0][$i]['email'])->update($fields);

        }
        return redirect()->route('userList');
    }


    //upload excel

    public function UploadExcelToCreateOrUpdateUsers(Request $request)

    {
        $file = $request->file;
        $employes_file_list = Excel::toArray(new UserFullImport, $file);

        $managment_code_list = config('select.users-mc');
        $efl = $employes_file_list[0];

         $man_emp_mail    ='';
         $man_emp_name    ='';
         $man_emp_pimsid  ='';
         $man_emp_ftid    ='';
         $man_emp_region  ='';
         $man_emp_country ='';
         $man_emp_mc      ='';
         $man_emp_type    ='';
         $is_manager      ='';
         $man_emp_domain  ='';
         $man_emp_job_role='';
         $man_emp_activty ='';
         $man_emp_roles   ='';
         
        //CaC Manager Create Array with MC exits in the MC array
        //CaC File Does not have manager mail feild
        //Manager mail does not exits
        

         for($i=0;$i<sizeof($efl);$i++)
        {
            $man_emp_region    = $efl[$i]['region'];
            $man_emp_name      = $efl[$i]['name'];
            $man_emp_email     = $efl[$i]['email'];
            $man_emp_pimsid    = $efl[$i]['pims_id'];
            $man_emp_ftid      = $efl[$i]['ft_id'];
            $man_emp_country   = $efl[$i]['country'];
            $man_emp_type      = $efl[$i]['type'];
            $man_emp_mc        = $efl[$i]['management_code'];
            $man_emp_activty   = 'active';
            $man_emp_is_manager= $efl[$i]['is_manager'];
            

            // CaC Create User Array
            $cac_create_user = 
            [
                'email'             => $man_emp_email,
                'name'              => $man_emp_name,
                'pimsid'            => $man_emp_pimsid,
                'ftid'              => $man_emp_ftid,
                'region'            => $man_emp_region,
                'country'           => $man_emp_country,
                'employee_type'     => $man_emp_type,
                'activity_status'   => $man_emp_activty
            ];
            
            // CaC Update User Array

            $cac_update_user =
            [
                'pimsid' => $man_emp_pimsid,
                'ftid'   => $man_emp_ftid,
            ];
            $employes_email_exists = User::where('email', $man_emp_email)->first();
            $employes_email_exists_in = $employes_email_exists->email;

            // If manager mail does not exists in the file 
            // Check if manager_email field not exits then check if this manager already 
            // exits or not so it is update or create
            // manager file starts
            if(!isset($efl[$i]['manager_email'])&& $man_emp_is_manager =='Y')
            {
                if(array_key_exists($man_emp_mc,$managment_code_list))
                {
                    $cac_create_user['management_code'] = $man_emp_mc;
                    $cac_create_user['is_manager'] = 1;
                   $this->UpdateCreateManagerWithSameEmail($man_emp_email,$efl[$i]['email'],$cac_create_user,$cac_update_user);
                    
            }
            else
            {
                    $manager_email_exits = User::where('email',$man_emp_email)->first();
                    $cac_create_user['management_code'] = '-';
                    $cac_create_user['is_manager'] = 1;
                    $this->UpdateCreateManagerWithSameEmail($manager_email_exits,$efl[$i]['email'],$cac_create_user,$cac_update_user);
                    
            }
        }
            elseif($employes_email_exists_in && !isset($efl[$i]['manager_email']))
            {
                
                User::where('email',$employes_email_exists_in)->update($cac_update_user);

            }

            // end of manager file
            //Start of Deafult User (employee)
            else{
                if(array_key_exists($man_emp_mc,$managment_code_list))
                {

                    $cac_create_user['management_code'] = $man_emp_mc;

                    $this->UpdateCreateUserEmp($man_emp_email,$efl[$i]['manager_email'],$cac_create_user,$cac_update_user);
                }
                else
                {

                    $cac_create_user['management_code'] = "-";

                    $this->UpdateCreateUserEmp($man_emp_email,$efl[$i]['manager_email'],$cac_create_user,$cac_update_user);
                }

            }

           


        }
        return redirect('userList')->with('success', 'Record created successfully');
    }
    public function UpdateCreateManagerWithSameEmail($email_check,$user_email,$create_user_array,$update_user_array)
    {

         $manager_email_exits = User::where('email',$email_check)->first();
         if($manager_email_exits)
                {
                    
            User::where('email',$email_check)->update($update_user_array);
                    
               }
            else
            {

            $var_array['manager_email'] = $email_check;
            $create_user = User::create($create_user_array);
            $created_user = User::where('email',$user_email)->first();
            echo $created_user->id;
            $created_user->managers()->attach($created_user->id);
            $created_user->update_password('Welcome1',true);
            $created_user->syncRoles('Only Skills');   
                        
                        
            }
    }

    public function UpdateCreateUserEmp($employee_email,$manager_email,$create_emp_arr,$update_emp_arr)
    {
        $employee = User::where('email',$employee_email)->first();
        $employee_manager_id = User::where('email',$manager_email)->pluck('id');
        if($employee)
        {
            // update
            User::where('email',$employee_email)->update($update_emp_arr);
        }
        else
        {
            //create and attach to manager
            $create_emp_arr['is_manager']=0;
            $create_emp_user = User::create($create_emp_arr);

            $create_emp_user->managers()->attach($employee_manager_id);
            $create_emp_user->update_password('Welcome_1',true);
            $create_emp_user->syncRoles('Only Skills');

        }

    }
}
