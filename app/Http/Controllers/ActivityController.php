<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityCreateRequest;
use App\Http\Requests\ActivityUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Repositories\ActivityRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\ProjectTableRepository;
use App\Repositories\UserRepository;
use Auth;
use Datatables;
use DB;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $activityRepository;
    protected $userRepository;
    protected $projectRepository;

    public function __construct(ActivityRepository $activityRepository, UserRepository $userRepository, ProjectRepository $projectRepository)
    {
        $this->activityRepository = $activityRepository;
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
    }

    public function getList()
    {
        return view('activity/list');
    }

    public function show($id)
    {
        $activity = $this->activityRepository->getById($id);
        $allUsers_list = $this->userRepository->getAllUsersList();
        $allProjects_list = $this->projectRepository->getAllProjectsList();

        return view('activity/show', compact('activity', 'allUsers_list', 'allProjects_list'));
    }

    public function getFormCreate()
    {
        $allUsers_list = $this->userRepository->getAllUsersList();
        $allProjects_list = $this->projectRepository->getAllProjectsList();

        return view('activity/create_update', compact('allUsers_list', 'allProjects_list'))->with('action', 'create');
    }

    public function getFormUpdate($id)
    {
        $activity = $this->activityRepository->getById($id);
        $allUsers_list = $this->userRepository->getAllUsersList();
        $allProjects_list = $this->projectRepository->getAllProjectsList();

        return view('activity/create_update', compact('activity', 'allUsers_list', 'allProjects_list'))->with('action', 'update');
    }

public function hanaaCarfour()
    {
        // code...
        $table = DB::table('subactivityactuals as sub');

            for($j=0, $i=1; $i<53;$j++, $i++){
                    ${'y' . $i} = date('Y');
                    ${'m' . $i} = date('W') + $j;
                    if(${'m' . $i}>52){
                        ${'m' . $i} -= 52;
                        ${'y' . $i} = date('Y')-1;
                    }    
                }

        $table->select('uu.manager_id as manager_id','m.name AS manager_name','u.name as user_name', 'p.project_name as project_name',
           DB::raw("SUM(CASE when sub.week='1'  then sub.task_hour else 0 end) as m1_com"), 
                            DB::raw("SUM(CASE when sub.week='2'  then sub.task_hour else 0 end) as m2_com"), 
                            DB::raw("SUM(CASE when sub.week='3'  then sub.task_hour else 0 end) as m3_com"), 
                            DB::raw("SUM(CASE when sub.week='4'  then sub.task_hour else 0 end) as m4_com"), 
                            DB::raw("SUM(CASE when sub.week='5'  then sub.task_hour else 0 end) as m5_com"), 
                            DB::raw("SUM(CASE when sub.week='6'  then sub.task_hour else 0 end) as m6_com"), 
                            DB::raw("SUM(CASE when sub.week='7'  then sub.task_hour else 0 end) as m7_com"), 
                            DB::raw("SUM(CASE when sub.week='8'  then sub.task_hour else 0 end) as m8_com"), 
                            DB::raw("SUM(CASE when sub.week='9'  then sub.task_hour else 0 end) as m9_com"),
                            DB::raw("SUM(CASE when sub.week='10' then sub.task_hour else 0 end) as m10_com"), 
                            DB::raw("SUM(CASE when sub.week='11' then sub.task_hour else 0 end) as m11_com"), 
                            DB::raw("SUM(CASE when sub.week='12' then sub.task_hour else 0 end) as m12_com"), 
                            DB::raw("SUM(CASE when sub.week='13' then sub.task_hour else 0 end) as m13_com"),
                            DB::raw("SUM(CASE when sub.week='14' then sub.task_hour else 0 end) as m14_com"), 
                            DB::raw("SUM(CASE when sub.week='15' then sub.task_hour else 0 end) as m15_com"), 
                            DB::raw("SUM(CASE when sub.week='16' then sub.task_hour else 0 end) as m16_com"), 
                            DB::raw("SUM(CASE when sub.week='17' then sub.task_hour else 0 end) as m17_com"), 
                            DB::raw("SUM(CASE when sub.week='18' then sub.task_hour else 0 end) as m18_com"), 
                            DB::raw("SUM(CASE when sub.week='19' then sub.task_hour else 0 end) as m19_com"), 
                            DB::raw("SUM(CASE when sub.week='20' then sub.task_hour else 0 end) as m20_com"), 
                            DB::raw("SUM(CASE when sub.week='21' then sub.task_hour else 0 end) as m21_com"), 
                            DB::raw("SUM(CASE when sub.week='22' then sub.task_hour else 0 end) as m22_com"), 
                            DB::raw("SUM(CASE when sub.week='23' then sub.task_hour else 0 end) as m23_com"), 
                            DB::raw("SUM(CASE when sub.week='24' then sub.task_hour else 0 end) as m24_com"), 
                            DB::raw("SUM(CASE when sub.week='25' then sub.task_hour else 0 end) as m25_com"), 
                            DB::raw("SUM(CASE when sub.week='26' then sub.task_hour else 0 end) as m26_com"), 
                            DB::raw("SUM(CASE when sub.week='27' then sub.task_hour else 0 end) as m27_com"), 
                            DB::raw("SUM(CASE when sub.week='28' then sub.task_hour else 0 end) as m28_com"), 
                            DB::raw("SUM(CASE when sub.week='29' then sub.task_hour else 0 end) as m29_com"), 
                            DB::raw("SUM(CASE when sub.week='30' then sub.task_hour else 0 end) as m30_com"), 
                            DB::raw("SUM(CASE when sub.week='31' then sub.task_hour else 0 end) as m31_com"), 
                            DB::raw("SUM(CASE when sub.week='32' then sub.task_hour else 0 end) as m32_com"), 
                            DB::raw("SUM(CASE when sub.week='33' then sub.task_hour else 0 end) as m33_com"), 
                            DB::raw("SUM(CASE when sub.week='34' then sub.task_hour else 0 end) as m34_com"), 
                            DB::raw("SUM(CASE when sub.week='35' then sub.task_hour else 0 end) as m35_com"), 
                            DB::raw("SUM(CASE when sub.week='36' then sub.task_hour else 0 end) as m36_com"), 
                            DB::raw("SUM(CASE when sub.week='37' then sub.task_hour else 0 end) as m37_com"), 
                            DB::raw("SUM(CASE when sub.week='38' then sub.task_hour else 0 end) as m38_com"), 
                            DB::raw("SUM(CASE when sub.week='39' then sub.task_hour else 0 end) as m39_com"),
                            DB::raw("SUM(CASE when sub.week='40' then sub.task_hour else 0 end) as m40_com"),
                            DB::raw("SUM(CASE when sub.week='41' then sub.task_hour else 0 end) as m41_com"),
                            DB::raw("SUM(CASE when sub.week='42' then sub.task_hour else 0 end) as m42_com"),
                            DB::raw("SUM(CASE when sub.week='43' then sub.task_hour else 0 end) as m43_com"),
                            DB::raw("SUM(CASE when sub.week='44' then sub.task_hour else 0 end) as m44_com"),
                            DB::raw("SUM(CASE when sub.week='45' then sub.task_hour else 0 end) as m45_com"),
                            DB::raw("SUM(CASE when sub.week='46' then sub.task_hour else 0 end) as m46_com"),
                            DB::raw("SUM(CASE when sub.week='47' then sub.task_hour else 0 end) as m47_com"),
                            DB::raw("SUM(CASE when sub.week='48' then sub.task_hour else 0 end) as m48_com"),
                            DB::raw("SUM(CASE when sub.week='49' then sub.task_hour else 0 end) as m49_com"),
                            DB::raw("SUM(CASE when sub.week='50' then sub.task_hour else 0 end) as m50_com"),
                            DB::raw("SUM(CASE when sub.week='51' then sub.task_hour else 0 end) as m51_com"),
                            DB::raw("SUM(CASE when sub.week='52' then sub.task_hour else 0 end) as m52_com"))
                           ;
        $table->join('projects as p','p.id','=','sub.project_id');
        $table->join('users AS u', 'sub.user_id', '=', 'u.id');
        $table->join('users_users AS uu', 'u.id', '=', 'uu.user_id');
        $table->join('users AS m', 'm.id', '=', 'uu.manager_id');
        $table->where('sub.year','=','2022');
        $table->groupBy('p.project_name','sub.week');


        $data = Datatables::of($table)->make(true);

        $users = DB::table('activities');
        $user = $users->select('user_id')->get();
        $arr = [];
        foreach($user as $key => $val){
            array_push($arr,$val->user_id);
        }


        return $arr;

    }

    public function postFormCreate(ActivityCreateRequest $request)
    {
        $inputs = $request->all();
        $activity = $this->activityRepository->create($inputs);

        return redirect('activityList')->with('success', 'Record created successfully');
    }

    public function postFormUpdate(ActivityUpdateRequest $request, $id)
    {
        $inputs = $request->all();
        $activity = $this->activityRepository->update($id, $inputs);

        return redirect('activityList')->with('success', 'Record updated successfully');
    }

    public function delete($id)
    {
        // When using stdClass(), we need to prepend with \ so that Laravel won't get confused...
        $result = new \stdClass();
        $result->result = 'success';
        $result->msg = 'Record deleted successfully';
        $activity = $this->activityRepository->destroy($id);

        return json_encode($result);
    }

    public function ListOfactivities()
    {
        return $this->activityRepository->getListOfActivities();
    }

    public function ListOfActivitiesPerUser(Request $request) //ajax function
    {
        $input = $request->all();

        return $this->activityRepository->getListOfActivitiesPerUser($input);
    }
    public function ListOfActualsPerUser(Request $request) //ajax function
    {
        $input = $request->all();

        return $this->activityRepository->getListOfActualsPerUser($input);
    }

    public function listOfLoadPerUserAjax(Request $request)
    {
        $input = $request->all();

        return $this->activityRepository->getlistOfLoadPerUser($input);
    }

    public function listOfLoadPerUserChartAjax(Request $request)
    {
        $inputs = $request->all();

        //$inputs['year'] = ['2017'];
        //$inputs['user'] = [4];

        $theoreticalCapacity = $this->userRepository->getTheoreticalCapacity($inputs);

        $temp_table = new ProjectTableRepository('temp_a');

        $dsc = $this->activityRepository->getListOfLoadPerUserChart('temp_a', $inputs, 'project_type != "Pre-sales" and activity_type = "ASC"');
        $isc = $this->activityRepository->getListOfLoadPerUserChart('temp_a', $inputs, 'project_type != "Pre-sales" and activity_type = "ISC"');
        $orange = $this->activityRepository->getListOfLoadPerUserChart('temp_a', $inputs, 'project_type = "Orange absence or other"');
        $presales = $this->activityRepository->getListOfLoadPerUserChart('temp_a', $inputs, 'project_type = "Pre-sales"');

        unset($temp_table);

        $data = [];

        $data['dscvstotal'] = [];
        $data['dscvstotal'][0] = new \stdClass();
        $data['dscvstotal'][0]->year = $inputs['year'][0];
        $data['dscvstotal'][0]->jan_com = ($dsc[0]->jan_com + $isc[0]->jan_com) != 0 ? 100 * ($dsc[0]->jan_com) / ($dsc[0]->jan_com + $isc[0]->jan_com) : 0;
        $data['dscvstotal'][0]->feb_com = ($dsc[0]->feb_com + $isc[0]->feb_com) != 0 ? 100 * ($dsc[0]->feb_com) / ($dsc[0]->feb_com + $isc[0]->feb_com) : 0;
        $data['dscvstotal'][0]->mar_com = ($dsc[0]->mar_com + $isc[0]->mar_com) != 0 ? 100 * ($dsc[0]->mar_com) / ($dsc[0]->mar_com + $isc[0]->mar_com) : 0;
        $data['dscvstotal'][0]->apr_com = ($dsc[0]->apr_com + $isc[0]->apr_com) != 0 ? 100 * ($dsc[0]->apr_com) / ($dsc[0]->apr_com + $isc[0]->apr_com) : 0;
        $data['dscvstotal'][0]->may_com = ($dsc[0]->may_com + $isc[0]->may_com) != 0 ? 100 * ($dsc[0]->may_com) / ($dsc[0]->may_com + $isc[0]->may_com) : 0;
        $data['dscvstotal'][0]->jun_com = ($dsc[0]->jun_com + $isc[0]->jun_com) != 0 ? 100 * ($dsc[0]->jun_com) / ($dsc[0]->jun_com + $isc[0]->jun_com) : 0;
        $data['dscvstotal'][0]->jul_com = ($dsc[0]->jul_com + $isc[0]->jul_com) != 0 ? 100 * ($dsc[0]->jul_com) / ($dsc[0]->jul_com + $isc[0]->jul_com) : 0;
        $data['dscvstotal'][0]->aug_com = ($dsc[0]->aug_com + $isc[0]->aug_com) != 0 ? 100 * ($dsc[0]->aug_com) / ($dsc[0]->aug_com + $isc[0]->aug_com) : 0;
        $data['dscvstotal'][0]->sep_com = ($dsc[0]->sep_com + $isc[0]->sep_com) != 0 ? 100 * ($dsc[0]->sep_com) / ($dsc[0]->sep_com + $isc[0]->sep_com) : 0;
        $data['dscvstotal'][0]->oct_com = ($dsc[0]->oct_com + $isc[0]->oct_com) != 0 ? 100 * ($dsc[0]->oct_com) / ($dsc[0]->oct_com + $isc[0]->oct_com) : 0;
        $data['dscvstotal'][0]->nov_com = ($dsc[0]->nov_com + $isc[0]->nov_com) != 0 ? 100 * ($dsc[0]->nov_com) / ($dsc[0]->nov_com + $isc[0]->nov_com) : 0;
        $data['dscvstotal'][0]->dec_com = ($dsc[0]->dec_com + $isc[0]->dec_com) != 0 ? 100 * ($dsc[0]->dec_com) / ($dsc[0]->dec_com + $isc[0]->dec_com) : 0;

        $data['theoreticalCapacity'] = $theoreticalCapacity;
        $data['theoreticalCapacity'] = [];
        $data['theoreticalCapacity'][0] = new \stdClass();
        $data['theoreticalCapacity'][0]->year = $inputs['year'][0];
        $data['theoreticalCapacity'][0]->jan_com = $theoreticalCapacity[0];
        $data['theoreticalCapacity'][0]->feb_com = $theoreticalCapacity[1];
        $data['theoreticalCapacity'][0]->mar_com = $theoreticalCapacity[2];
        $data['theoreticalCapacity'][0]->apr_com = $theoreticalCapacity[3];
        $data['theoreticalCapacity'][0]->may_com = $theoreticalCapacity[4];
        $data['theoreticalCapacity'][0]->jun_com = $theoreticalCapacity[5];
        $data['theoreticalCapacity'][0]->jul_com = $theoreticalCapacity[6];
        $data['theoreticalCapacity'][0]->aug_com = $theoreticalCapacity[7];
        $data['theoreticalCapacity'][0]->sep_com = $theoreticalCapacity[8];
        $data['theoreticalCapacity'][0]->oct_com = $theoreticalCapacity[9];
        $data['theoreticalCapacity'][0]->nov_com = $theoreticalCapacity[10];
        $data['theoreticalCapacity'][0]->dec_com = $theoreticalCapacity[11];

        $data['dsc'] = $dsc;
        $data['isc'] = $isc;
        $data['orange'] = $orange;
        $data['presales'] = $presales;

        //dd($data);

        return json_encode($data);
    }

    public function test(UserRepository $userRepository)
    {
        return view('activity/test');
    }

    public function updateActivityAjax(Request $request)
    {
        $result = new \stdClass();
        $inputs = $request->all();
        // Verification if the user is authorised to modify
        if ($inputs['user_id'] == Auth::user()->id || Auth::user()->can('tools-activity-all-edit')) {
            // Validation that what has been entered is positive and numeric
            if (is_numeric($inputs['task_hour']) && $inputs['task_hour'] >= 0) {
                // Check if we need to create or update
                if (empty($inputs['id'])) {
                    $record = Activity::create(
                        [
                            'year'=>$inputs['year'],
                            'month'=>$inputs['month'],
                            'project_id'=>$inputs['project_id'],
                            'user_id'=>$inputs['user_id'],
                            'task_hour'=>$inputs['task_hour'],
                            'from_otl'=>0
                        ]);
                    $result->result = 'success';
                    $result->action = 'create';
                    $result->id = $record->id;
                    $result->msg = 'Record created successfully';
                } else {
                    $record = Activity::where('id',$inputs['id'])
                        ->update(['task_hour'=>$inputs['task_hour']]);
                    $result->result = 'success';
                    $result->action = 'update';
                    $result->msg = 'Record updated successfully';
                }
            } else {
                $result->result = 'error';
                $result->msg = 'Must be a positive numeric value';
            }
        } else {
            $result->result = 'error';
            $result->msg = 'No permission to edit this record';
        }
        return json_encode($result);
    }
}
