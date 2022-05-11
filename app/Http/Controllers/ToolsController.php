<?php

namespace App\Http\Controllers;

use App\Action;
use App\Activity;
use App\Comment;
use App\Customer;
use App\Project;
use App\Http\Controllers\Auth\AuthUsersForDataView;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\ProjectRevenue;
use App\Repositories\ActivityRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\Skill;
use App\User;
use App\UserSkill;
use Auth;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Session;
use App\SubActivityActuals;
use App\Actuals;
use App\Imports\activitiesImport;
use Maatwebsite\Excel\Facades\Excel;

class ToolsController extends Controller
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

    public function activities(AuthUsersForDataView $authUsersForDataView)
    {
        $authUsersForDataView->userCanView('tools-activity-all-view');
        Session::put('url', 'toolsActivities');
        $table_height = Auth::user()->table_height;
        $isManager = Auth::user()->is_manager;

        return view('tools/list', compact('authUsersForDataView','table_height','isManager'));
    }

    public function projectsAssignedAndNot()
    {
        $year = date('Y');
        $manager_list = [];
        if (Auth::user()->can('tools-activity-all-edit')) {
            $user_id_for_update = '0';
        } elseif (Auth::user()->is_manager == 1) {
            $user_id_for_update = '0';
        } else {
            $user_id_for_update = Auth::user()->id;
        }
        Session::put('url', 'toolsProjectsAssignedAndNot');
        //dd(Session::get('url'));
        return view('tools/projects_assigned_and_not', compact('manager_list', 'year', 'user_id_for_update'));
    }

    public function projectsMissingInfo()
    {
        $year = date('Y');
        $manager_list = [];
        if (Auth::user()->can('tools-activity-all-edit')) {
            $user_id_for_update = '0';
        } elseif (Auth::user()->is_manager == 1) {
            $user_id_for_update = '0';
        } else {
            $user_id_for_update = '0';
        }
        Session::put('url', 'toolsProjectsMissingInfo');

        return view('tools/projects_missing_info', compact('manager_list', 'year', 'user_id_for_update'));
    }

    public function projectsMissingOTL()
    {
        $year = date('Y');
        $manager_list = [];
        if (Auth::user()->can('tools-activity-all-edit')) {
            $user_id_for_update = '0';
        } elseif (Auth::user()->is_manager == 1) {
            $user_id_for_update = '0';
        } else {
            $user_id_for_update = '0';
        }
        Session::put('url', 'toolsProjectsMissingOTL');

        return view('tools/projects_missing_otl', compact('manager_list', 'year', 'user_id_for_update'));
    }

    public function projectsAll()
    {
        $year = date('Y');
        $manager_list = [];
        if (Auth::user()->can('tools-activity-all-edit')) {
            $user_id_for_update = '0';
        } elseif (Auth::user()->is_manager == 1) {
            $user_id_for_update = '0';
        } else {
            $user_id_for_update = Auth::user()->id;
        }
        Session::put('url', 'toolsProjectsAll');

        return view('tools/projects_all', compact('manager_list', 'year', 'user_id_for_update'));
    }

    public function projectsLost()
    {
        $year = date('Y');
        $manager_list = [];
        if (Auth::user()->can('tools-activity-all-edit')) {
            $user_id_for_update = '0';
        } elseif (Auth::user()->is_manager == 1) {
            $user_id_for_update = '0';
        } else {
            $user_id_for_update = '0';
        }
        Session::put('url', 'toolsProjectsLost');

        return view('tools/projects_lost', compact('manager_list', 'year', 'user_id_for_update'));
    }

    public function getFormCreate($year, $tab = 'tab_main')
    {
        $project_name_disabled = '';
        $customer_id_select_disabled = 'false';
        $otl_name_disabled = '';
        $meta_activity_select_disabled = 'false';
        $project_type_select_disabled = 'false';
        $activity_type_select_disabled = 'false';
        $project_status_select_disabled = 'false';
        $region_select_disabled = 'false';
        $country_select_disabled = 'false';
        $user_select_disabled = 'false';
        $customer_location_disabled = '';
        $technology_disabled = '';
        $description_disabled = '';
        $comments_disabled = '';
        $estimated_date_disabled = '';
        $LoE_onshore_disabled = '';
        $LoE_nearshore_disabled = '';
        $LoE_offshore_disabled = '';
        $LoE_contractor_disabled = '';
        $gold_order_disabled = '';
        $samba_options_disabled = '';
        $product_code_disabled = '';
        $revenue_disabled = '';
        $win_ratio_disabled = '';
        $show_change_button = 'false';
        $solution_complexity = "false";
        $opportunity_id_disable = '';

        $user_selected = '';

        $created_by_user_id = Auth::user()->id;

        if (Auth::user()->can('tools-activity-all-view')) {
            $user_list = $this->userRepository->getAllUsersList();
            $user_select_disabled = 'false';
        } elseif (Auth::user()->is_manager == 1) {
            $user_list = Auth::user()->employees()->pluck('name', 'user_id');
            $user_list->prepend(Auth::user()->name, Auth::user()->id);
            $user_select_disabled = 'false';
        } else {
            $user_list = [Auth::user()->id => Auth::user()->name];
            $user_selected = Auth::user()->id;
            $user_select_disabled = 'true';
        }

        $customers_list = Customer::orderBy('name')->pluck('name', 'id');
        $customers_list->prepend('', '');
        //dd($customers_list);

        $num_of_comments = 0;

        // return $customers_list;

        return view('tools/create_update', compact('year', 'customers_list',
      'user_list', 'user_selected', 'created_by_user_id',
      'project_name_disabled',
      'customer_id_select_disabled',
      'otl_name_disabled',
      'meta_activity_select_disabled',
      'project_type_select_disabled',
      'activity_type_select_disabled',
      'project_status_select_disabled',
      'region_select_disabled',
      'country_select_disabled',
      'user_select_disabled',
      'customer_location_disabled',
      'technology_disabled',
      'description_disabled',
      'comments_disabled',
      'estimated_date_disabled',
      'LoE_onshore_disabled',
      'LoE_nearshore_disabled',
      'LoE_offshore_disabled',
      'LoE_contractor_disabled',
      'gold_order_disabled',
      'samba_options_disabled',
      'product_code_disabled',
      'revenue_disabled',
      'win_ratio_disabled',
      'show_change_button',
      'num_of_comments', 'tab','solution_complexity','opportunity_id_disable'
      ))
      ->with('action', 'create');
    }

    public function postFormCreate(ProjectCreateRequest $request)
    {
        $inputs = $request->all();

        // dd($inputs);
        $start_end_date = explode(' - ', $inputs['estimated_date']);
        $inputs['estimated_start_date'] = trim($start_end_date[0]);
        $inputs['estimated_end_date'] = trim($start_end_date[1]);

        $project = $this->projectRepository->create($inputs);

        // Here I will test if a user has been selected or not
        if (! empty($inputs['user_id'])) {
            foreach ($inputs['user_id'] as $key => $user) {
            foreach ($inputs['month'] as $key => $value) {
            $inputsActivities = [
          'year' => $inputs['year'],
          'month' => $key,
          'project_id' => $project->id,
          'user_id' => $user,
          'task_hour' => $value,
          'from_otl' => 0,
            ];
                $activity = $this->activityRepository->create($inputsActivities);
            }
        }
        }

        // Here I will test if there is a comment
        if (! empty($inputs['project_comment'])) {
            $comment_input = [
        'user_id' => Auth::user()->id,
        'project_id' => $project->id,
        'comment' => $inputs['project_comment'],
      ];
            $comment = Comment::create($comment_input);
        }

        if (! empty(Session::get('url'))) {
            $redirect = Session::get('url');
        } else {
            $redirect = 'toolsActivities';
        }

        date_default_timezone_set('CET');
        $user = User::find(Auth::user()->id);
        $user->last_activity_update = date('Y-m-d H:i:s');
        $user->save();

        return redirect($redirect)->with('success', 'New project created successfully');
    }

    public function getFormUpdate($user_id, $project_id, $year, $tab = 'tab_main')
    {
        // Here we setup all the disabled fields to be disabled
        $project_name_disabled = 'disabled';
        $customer_id_select_disabled = 'true';
        $otl_name_disabled = 'disabled';
        $meta_activity_select_disabled = 'true';
        $project_type_select_disabled = 'true';
        $activity_type_select_disabled = 'true';
        $project_status_select_disabled = 'true';
        $region_select_disabled = 'true';
        $country_select_disabled = 'true';
        $user_select_disabled = 'true';
        $customer_location_disabled = 'disabled';
        $technology_disabled = 'disabled';
        $description_disabled = 'disabled';
        $comments_disabled = 'disabled';
        $estimated_date_disabled = 'disabled';
        $LoE_onshore_disabled = 'disabled';
        $LoE_nearshore_disabled = 'disabled';
        $LoE_offshore_disabled = 'disabled';
        $LoE_contractor_disabled = 'disabled';
        $gold_order_disabled = 'disabled';
        $samba_options_disabled = 'disabled';
        $product_code_disabled = 'disabled';
        $revenue_disabled = 'disabled';
        $win_ratio_disabled = 'disabled';
        $show_change_button = 'false';

        // Here we find the information about the project
        $project = $this->projectRepository->getById($project_id);

        if (Auth::user()->can('tools-all_projects-edit') || (isset($project->created_by_user_id) && (Auth::user()->id == $project->created_by_user_id))) {
            $project_name_disabled = '';
            $customer_id_select_disabled = 'false';
            $otl_name_disabled = '';
            $meta_activity_select_disabled = 'false';
            $project_type_select_disabled = 'false';
            $activity_type_select_disabled = 'false';
            $project_status_select_disabled = 'false';
            $region_select_disabled = 'false';
            $country_select_disabled = 'false';
            $user_select_disabled = 'false';
            $customer_location_disabled = '';
            $technology_disabled = '';
            $description_disabled = '';
            $comments_disabled = '';
            $estimated_date_disabled = '';
            $LoE_onshore_disabled = '';
            $LoE_nearshore_disabled = '';
            $LoE_offshore_disabled = '';
            $LoE_contractor_disabled = '';
            $gold_order_disabled = '';
            $samba_options_disabled = '';
            $product_code_disabled = '';
            $revenue_disabled = '';
            $win_ratio_disabled = '';
        }

        if ($project->otl_validated == 1 && ! Auth::user()->can('tools-update-existing_prime_code')) {
            $otl_name_disabled = 'disabled';
            $meta_activity_select_disabled = 'true';
        }

        $user_list = [];

        $customers_list = Customer::orderBy('name')->pluck('name', 'id');
        $customers_list->prepend('', '');

        // Here we will define if we can select a user for this project and activity or not
        // Attention, we need to prevent in the user_list to have ids when already assigned to a project
        if (Auth::user()->can('tools-activity-all-edit')) {
            $user_list_temp = $this->userRepository->getAllUsersList();

            if ($user_id == '0') {
                foreach ($user_list_temp as $key => $value) {
                    if ($this->activityRepository->user_assigned_on_project($year, $key, $project_id) == 0) {
                        $user_list[$key] = $value;
                    }
                }
                $user_select_disabled = 'false';
                $user_selected = '';
            } else {
                $user_list = $user_list_temp;
                $user_select_disabled = 'true';
                $user_selected = $user_id;
            }
        } elseif (Auth::user()->is_manager == 1) {
            $user_list_temp = Auth::user()->employees()->pluck('name', 'user_id');
            $user_list_temp->prepend(Auth::user()->name, Auth::user()->id);

            if ($user_id == '0') {
                foreach ($user_list_temp as $key => $value) {
                    if ($this->activityRepository->user_assigned_on_project($year, $key, $project_id) == 0) {
                        $user_list[$key] = $value;
                    }
                }
                $user_select_disabled = 'false';
                $user_selected = '';
            } else {
                $user_list = $user_list_temp;
                $user_select_disabled = 'true';
                $user_selected = $user_id;
            }
        } else {
            $user_list = [Auth::user()->id => Auth::user()->name];
            if ($user_id == '0') {
                $user_select_disabled = 'true';
                $user_selected = '';
            } else {
                $user_select_disabled = 'true';
                $user_selected = $user_id;
            }
        }

        $created_by_user_name = isset($project->created_by_user_id) ? $project->created_by_user->name : 'Admin';

        $activities = [];
        $from_otl = [];

        if ($user_id != '0') {
            $user = $this->userRepository->getById($user_id);
            $activity_forecast = $this->activityRepository->getByOTL($year, $user->id, $project->id, 0);
            $activity_OTL = $this->activityRepository->getByOTL($year, $user->id, $project->id, 1);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (isset($activity_OTL[$i])) {
                $activities[$i] = $activity_OTL[$i];
                $from_otl[$i] = 'disabled';
            } elseif (isset($activity_forecast[$i])) {
                $activities[$i] = $activity_forecast[$i];
                $from_otl[$i] = '';
            } else {
                $activities[$i] = '0';
                $from_otl[$i] = '';
            }
        }

        for ($i = 1; $i <= 12; $i++) {
            if (isset($activity_OTL[$i])) {
                $otl[$i] = $activity_OTL[$i];
            } else {
                $otl[$i] = 0;
            }
            if (isset($activity_forecast[$i])) {
                $forecast[$i] = $activity_forecast[$i];
            } else {
                $forecast[$i] = 0;
            }
        }

        //here is the check to see if we need the change user button
        $has_otl_activities = $this->activityRepository->getNumberOfOTLPerUserAndProject($user_id, $project_id);
        if (Auth::user()->can('tools-user_assigned-change') && $user_id != 0 && $has_otl_activities == 0) {
            $show_change_button = true;
        }

        $comments = Comment::where('project_id', '=', $project_id)->orderBy('updated_at', 'desc')->get();
        $num_of_comments = count($comments);

        $num_of_actions = Action::where('project_id', '=', $project_id)->get()->count();

        // all users on this project for the actions
        $users_on_project = DB::table('projects')
            ->leftjoin('activities', 'projects.id', '=', 'activities.project_id')
            ->leftjoin('users', 'users.id', '=', 'activities.user_id')
            ->select('users.id', 'users.name')
            ->where('project_id', $project_id)
            ->groupBy('users.name')
            ->pluck('users.name', 'users.id');

        return view('tools/create_update', compact('users_on_project','num_of_actions','user_id','project','year','activities','from_otl','forecast','otl',          'customers_list',
            'project_name_disabled',
            'customer_id_select_disabled',
            'otl_name_disabled',
            'meta_activity_select_disabled',
            'project_type_select_disabled',
            'activity_type_select_disabled',
            'project_status_select_disabled',
            'region_select_disabled',
            'country_select_disabled',
            'user_select_disabled',
            'customer_location_disabled',
            'technology_disabled',
            'description_disabled',
            'comments_disabled',
            'estimated_date_disabled',
            'LoE_onshore_disabled',
            'LoE_nearshore_disabled',
            'LoE_offshore_disabled',
            'LoE_contractor_disabled',
            'gold_order_disabled',
            'samba_options_disabled',
            'product_code_disabled',
            'revenue_disabled',
            'win_ratio_disabled',
            'show_change_button',
            'num_of_comments', 'comments', 'user_list', 'user_selected', 'user_select_disabled', 'created_by_user_name', 'tab'))
              ->with('action', 'update');
            }

    public function postFormUpdate(ProjectUpdateRequest $request)
    {
        if (! empty(Session::get('url'))) {
            $redirect = Session::get('url');
        } else {
            $redirect = 'toolsActivities';
        }

        $inputs = $request->all();

        // Now we need to check if the user has been flagged for remove from project
        if ($inputs['action'] == 'Remove') {
            if (Auth::user()->can('tools-user_assigned-remove')) {
                $activity = $this->activityRepository->removeUserFromProject($inputs['user_id'], $inputs['project_id'], $inputs['year']);

                return redirect($redirect)->with('success', 'User removed from project successfully');
            }

            return redirect($redirect)->with('error', 'You do not have permission to remove a user');
        }

        $start_end_date = explode(' - ', $inputs['estimated_date']);
        $inputs['estimated_start_date'] = trim($start_end_date[0]);
        $inputs['estimated_end_date'] = trim($start_end_date[1]);

        $project = $this->projectRepository->update($inputs['project_id'], $inputs);

        // if user_id_url = 0 then it is only project update and we don't need to add or update tasks
        if ($inputs['user_id_url'] != 0 && Auth::user()->can('tools-user_assigned-change')) {
            // Let's check first if we changed the user
            if ($inputs['user_id_url'] != $inputs['user_id']) {
                // Let's check if the user we changed to has already some activities on this project
                $has_activities = $this->activityRepository->getNumberPerUserAndProject($inputs['user_id'], $inputs['project_id']);
                if ($inputs['user_id'] == '') {
                    return redirect($redirect)->with('error', 'You must select at least a new user');
                } elseif ($has_activities > 0) {
                    return redirect($redirect)->with('error', 'The user you have selected already has activities for this project');
                } else {
                    foreach ($inputs['month'] as $key => $value) {
                        $inputs_new = $inputs;
                        $inputs_new['month'] = $key;
                        $inputs_new['task_hour'] = $value;
                        $inputs_new['from_otl'] = 0;
                        $activity = $this->activityRepository->assignNewUser($inputs_new['user_id_url'], $inputs_new);
                    }

                    return redirect($redirect)->with('success', 'New user assigned successfully');
                }
            }
        }

        if (! empty($inputs['user_id'])) {
            foreach ($inputs['month'] as $key => $value) {
                $inputs_new = $inputs;
                $inputs_new['month'] = $key;
                $inputs_new['task_hour'] = $value;
                $inputs_new['from_otl'] = 0;
                $activity = $this->activityRepository->createOrUpdate($inputs_new);
            }
        }

        // Here I will test if there is a comment
        if (! empty($inputs['project_comment'])) {
            $comment_input = [
        'user_id' => Auth::user()->id,
        'project_id' => $project->id,
        'comment' => $inputs['project_comment'],
      ];
            $comment = Comment::create($comment_input);
        }

        date_default_timezone_set('CET');
        $user = User::find(Auth::user()->id);
        $user->last_activity_update = date('Y-m-d H:i:s');
        $user->save();

        return redirect($redirect)->with('success', 'Project updated successfully');
    }

    public function getFormTransfer($user_id, $project_id)
    {
        return view('tools/transfer', compact('user_id', 'project_id'));
    }

    public function getFormTransferAction($user_id, $old_project_id, $new_project_id, Activity $activity)
    {
        if (! empty(Session::get('url'))) {
            $redirect = Session::get('url');
        } else {
            $redirect = 'toolsActivities';
        }

        // Now we need to look for all the activities assigned to this user and the old projects and update the project to new project
        $activity->where('user_id', $user_id)
              ->where('project_id', $old_project_id)
              ->update(['project_id' => $new_project_id]);

        return redirect($redirect)->with('success', 'User transfered to new project successfully');
    }

    public function userskillslist()
    {
        return view('tools/usersskillslist');
    }

    public function listOfUsersSkills($cert)
    {
        $skillList = DB::table('skills')
          ->select('skill_user.id', 'skills.domain', 'skills.subdomain', 'skills.technology', 'skills.skill', 'm.name AS manager_name', 'm.email AS manager_email', 'u.email AS user_email', 'u.name AS user_name','u.activity_status AS user_activity', 'u.region', 'u.country','u.pimsid','u.ftid','u.job_role', 'u.employee_type', 'skill_user.rating', 'skills.id AS skill_id')
          ->leftjoin('skill_user', 'skills.id', '=', 'skill_user.skill_id')
          ->leftjoin('users AS u', 'u.id', '=', 'skill_user.user_id')
          ->leftjoin('users_users AS uu', 'u.id', '=', 'uu.user_id')
          ->leftjoin('users AS m', 'm.id', '=', 'uu.manager_id')
          ->where('skills.certification', '=', $cert);

        if (! Auth::user()->can('tools-usersskills-view-all')) {
            $skillList->where('u.id', '=', Auth::user()->id);
        }
        $data = Datatables::of($skillList)->make(true);

        return $data;
    }

    public function listOfSkills($cert)
    {
        $skillList = DB::table('skills')
          ->select('id', 'domain', 'subdomain', 'technology', 'skill')
          ->where('certification', '=', $cert);

        $data = Datatables::of($skillList)->make(true);

        return $data;
    }

    public function userSkillDelete($id)
    {
        // When using stdClass(), we need to prepend with \ so that Laravel won't get confused...
        $result = new \stdClass();

        // Do a check first to see if you can delete this or not
        $userskill = UserSkill::find($id);
        if ((Auth::user()->id == $userskill->user_id) or (Auth::user()->can('tools-usersskills-editall'))) {
            $userskill = UserSkill::destroy($id);
            $result->result = 'success';
            $result->msg = 'Record deleted successfully';

            return json_encode($result);
        } else {
            $result->result = 'error';
            $result->msg = 'You don\'t have the rights to delete this record';

            return json_encode($result);
        }
    }

    public function getuserskillFormCreate($id = null)
    {
        if (! empty($id)) {
            $skill = Skill::find($id);
            if ($skill->certification == 1) {
                $select = config('select.usercert_rating');
            } else {
                $select = config('select.userskill_rating');
            }

            if (Auth::user()->can('tools-usersskills-editall')) {
                $user_list = [];
                $user_list_temp = $this->userRepository->getAllUsersList();
                foreach ($user_list_temp as $key => $value) {
                    $userinskilllist = DB::table('skill_user')
              ->select('id')
              ->where('user_id', $key)
              ->where('skill_id', $id)
              ->get();
                    if (count($userinskilllist) == 0) {
                        $user_list[$key] = $value;
                    }
                }
            } else {
                $user_list = User::where('id', Auth::user()->id)->pluck('name', 'id');
            }
        } else {
            $skill = null;
            $user_list = null;
            $select = null;
        }

        return view('tools/userskill_create_update', compact('skill', 'user_list', 'select'))->with('action', 'create');
    }

    public function getuserskillFormUpdate($id)
    {
        $userskill = UserSkill::find($id);
        $skill = Skill::find($userskill->skill_id);
        if ($skill->certification == 1) {
            $select = config('select.usercert_rating');
        } else {
            $select = config('select.userskill_rating');
        }

        $user_list = User::where('id', $userskill->user_id)->pluck('name', 'id');
        if (Auth::user()->can('tools-usersskills-editall') or (Auth::user()->id == $userskill->user_id)) {
            return view('tools/userskill_create_update', compact('userskill', 'skill', 'user_list', 'select'))->with('action', 'update');
        } else {
            return redirect('toolsUsersSkills')->with('error', 'You don\'t have the rights to modify this record');
        }
    }

    public function postuserskillFormCreate(Request $request)
    {
        $inputs = $request->only('skill_id', 'user_id', 'rating');
        if (UserSkill::where('skill_id', $inputs['skill_id'])->where('user_id', $inputs['user_id'])->exists()) {
            return redirect('toolsUsersSkills')->with('error', 'This record has already been assigned');
        } else {
            $userskill = UserSkill::create($inputs);

            return redirect('toolsUsersSkills')->with('success', 'Record created successfully');
        }
    }

    public function postuserskillFormUpdate(Request $request, $id)
    {
        $inputs = $request->only('rating');

        $userskill = UserSkill::find($id);
        $userskill->update($inputs);

        return redirect('toolsUsersSkills')->with('success', 'Record updated successfully');
    }

    public function userSummary(AuthUsersForDataView $authUsersForDataView)
    {
        $authUsersForDataView->userCanView('tools-activity-all-view');
        Session::put('url', 'toolsUserSummary');
        $table_height = Auth::user()->table_height;
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $extra_info_display = 'display:none;';
        //$extra_info_display = "";

        return view('tools/userSummary', compact('authUsersForDataView', 'extra_info_display', 'table_height', 'user_id', 'user_name'));
    }

    public function userSummaryProjects(Request $request)
    {
        $inputs = $request->all();
        $activities = $this->activityRepository->getListOfActivitiesPerUser($inputs);
        foreach ($activities as $key => $activity) {
            $revenue = ProjectRevenue::where('project_id', $activity->project_id)->where('year', $activity->year)->get();
            $activity->revenue_forecast = $revenue;
        }

        return $activities;
    }

      public function getModalData($manager_id,$user_id,$week_no,$year,$read)
    {
        //Get the projects assigned to the user
        $projects = DB::table('activities as a')
                    ->join('projects as p', 'a.project_id', '=','p.id')
                    ->select('p.project_name','a.project_id')
                    ->where('a.user_id',$user_id)
                    ->where('a.month',$week_no)
                    ->where('a.year',$year)
                    ->get();
    
        //If there are actuals on the project, get the user and the project name
        $empty = "false";
       
        $data = DB::table('subactivityactuals as actuals') 
                ->join('projects as p', 'actuals.project_id','=','p.id')
                ->join('subactivitytypes as ss','actuals.sub_id','=','ss.id')
                ->join('users as u','actuals.user_id','=','u.id')
                ->select('p.project_name as project','u.name as user','actuals.task_hour','actuals.year','actuals.week','ss.name','p.project_type','actuals.project_id','ss.id')
                ->where('actuals.user_id',$user_id)
                ->where('actuals.week',$week_no)
                ->where('actuals.year',$year)
                ->get();

        //If the project has no actuals
        if($data->isEmpty()){
            $empty = "true";
            $data = DB::table('activities as a')
            ->join('users as u','a.user_id','=','u.id')
            ->join('projects as p','a.project_id','=','p.id')
            ->select('u.name as user','p.project_name as project')
            ->where('a.user_id',$user_id)
            ->take(1)->get();
        }

        //Distribute the subactivities on their types to have dynamics dropdown menues
        $Account = DB::table('subactivitytypes')->where('type','Account')->select('id','name')->get();
        $General = DB::table('subactivitytypes')->where('type','General')->select('id','name')->get();
        $Opportunity = DB::table('subactivitytypes')->where('type','Opportunity')->select('id','name')->get();   

         
        return view('tools/actuals',compact('manager_id','data','week_no','user_id','year','projects','Account','General','Opportunity','empty','read'));
    }

    public function getForecastDetail($user_id,$week_no,$year)
    {
        //Get the projects assigned to the user
        $projects = DB::table('activities as a')
                    ->join('projects as p', 'a.project_id', '=','p.id')
                    ->join('users as u', 'a.user_id','=','u.id')
                    ->join('customers as c','p.customer_id','=','c.id')
                    ->select('p.project_name','p.project_type','c.name as customer_name','u.name','a.task_hour')
                    ->where('a.user_id',$user_id)
                    ->where('a.month',$week_no)
                    ->where('a.year',$year)
                    ->get();
        return view('activity/forecastDetail',compact('projects','week_no','year'));
    }

    public function addNew(Request $request)
    {
        if(Auth::user()->can('tools-activity-view')){
            $input = $request->all();
            if($input['taskHour']!=0){
            $record = SubActivityActuals::updateOrCreate(
                [
                    'sub_id'=>$input['taskId'],
                    'project_id'=>$input['pid'],
                    'year'=>$input['year'],
                    'week'=>$input['week'],
                    'user_id'=>$input['uid']
                ],
                [
                    'task_hour'=>$input['taskHour']
                ]);
            }
            else{
                return json_encode("Equal 0");
            }
        }
        else{
            return json_encode("no");
        }
        return json_encode("done");
    }   

    public function getActuals($user_id,$week_no,$year,$oldWeek_no,$oldYear_no,$read){

        if($week_no<12){
            $pastYearWeeks = 12 - $week_no;
            for ($i=1; $i<$week_no ; $i++) {
                $j = $i+1;
            ${'week_' . $j} = $week_no - $i;
            ${'year_'.$j} = $year;
            }

            for($i=0; $i<$pastYearWeeks;$i++){
                $j = $week_no + 1 + $i;
                ${'week_' . $j} = 52 - $i;    
                ${'year_'.$j} = $year - 1;
            }
        }else{
            for ($i=1; $i <12 ; $i++) { 
                $j = $i+1;
                ${'week_'.$j} = $week_no - $i;
                ${'year_'.$j} = $year;
            }
            
        }

        DB::table('subactivityactuals')
            ->insert(['year'=>2022,'week'=>1,'project_id'=>1812,'user_id'=>$user_id,'task_hour'=>0,'sub_id'=>53]);

        $data = DB::table('subactivityactuals as ss') 
                ->join('projects as p', 'ss.project_id','=','p.id')
                ->join('users as u','ss.user_id','=','u.id')
                ->select('p.project_name as project','u.name as user','ss.task_hour','p.project_type',DB::raw("SUM(CASE when ss.week='$week_no' AND ss.year='$year' then ss.task_hour else 0 end) as '$week_no', SUM(CASE when ss.week='$week_2' AND ss.year='$year_2' then ss.task_hour else 0 end) as '$week_2', SUM(CASE when ss.week='$week_3' AND ss.year='$year_3' then ss.task_hour else 0 end) as '$week_3', SUM(CASE when ss.week='$week_4' AND ss.year='$year_4' then ss.task_hour else 0 end) as '$week_4', SUM(CASE when ss.week='$week_5' AND ss.year='$year_5' then ss.task_hour else 0 end) as '$week_5', SUM(CASE when ss.week='$week_6' AND ss.year='$year_6' then ss.task_hour else 0 end) as '$week_6', SUM(CASE when ss.week='$week_7' AND ss.year='$year_7' then ss.task_hour else 0 end) as '$week_7', SUM(CASE when ss.week='$week_8' AND ss.year='$year_8' then ss.task_hour else 0 end) as '$week_8', SUM(CASE when ss.week='$week_9' AND ss.year='$year_9' then ss.task_hour else 0 end) as '$week_9', SUM(CASE when ss.week='$week_10' AND ss.year='$year_10' then ss.task_hour else 0 end) as '$week_10', SUM(CASE when ss.week='$week_11' AND ss.year='$year_11' then ss.task_hour else 0 end) as '$week_11', SUM(CASE when ss.week='$week_12' AND ss.year='$year_12' then ss.task_hour else 0 end) as '$week_12'"))
                ->where('ss.user_id',$user_id)
                ->groupBy('p.project_name')
                ->get();

        DB::table('subactivityactuals')
            ->where('sub_id',53)
            ->delete();

                return view('tools/actualsView',compact('read','user_id','data','year','week_no','week_2','week_3','week_4','week_5','week_6','week_7','week_8','week_9','week_10','week_11','week_12','oldWeek_no','oldYear_no'));
    }
    public function getActualsDetails($user_id,$week_no,$year){

        if($week_no<12){
            $pastYearWeeks = 12 - $week_no;
            for ($i=1; $i<$week_no ; $i++) {
                $j = $i+1;
            ${'week_' . $j} = $week_no - $i;
            ${'year_'.$j} = $year;
            }

            for($i=0; $i<$pastYearWeeks;$i++){
                $j = $week_no + 1 + $i;
                ${'week_' . $j} = 52 - $i;    
                ${'year_'.$j} = $year - 1;
            }
        }else{
            for ($i=1; $i <12 ; $i++) { 
                $j = $i+1;
                ${'week_'.$j} = $week_no - $i;
                ${'year_'.$j} = $year;
            }
            
        }
        $managerObject= DB::table('users_users as uu')->select(DB::raw('manager_id'))->where('uu.user_id',$user_id)->get();
        $manager_id = $managerObject[0]->manager_id;
        $manager_name=DB::table('users as u')->select(DB::raw('name'))->where('u.id',$manager_id)->get();
        $user = DB::table('users_users as uu')->select(DB::raw('user_id'))->where('uu.manager_id',$manager_id)->get();
        $arr = [];
            foreach($user as $key => $val){
                array_push($arr,$val->user_id);
            }
        $data = DB::table('subactivityactuals as ss')
                ->join('users as u','ss.user_id','=','u.id')
                ->select('u.name as user','ss.user_id as user_id',DB::raw("SUM(CASE when ss.week='$week_no' AND ss.year='$year' then ss.task_hour else 0 end) as '$week_no', SUM(CASE when ss.week='$week_2' AND ss.year='$year_2' then ss.task_hour else 0 end) as '$week_2', SUM(CASE when ss.week='$week_3' AND ss.year='$year_3' then ss.task_hour else 0 end) as '$week_3', SUM(CASE when ss.week='$week_4' AND ss.year='$year_4' then ss.task_hour else 0 end) as '$week_4', SUM(CASE when ss.week='$week_5' AND ss.year='$year_5' then ss.task_hour else 0 end) as '$week_5', SUM(CASE when ss.week='$week_6' AND ss.year='$year_6' then ss.task_hour else 0 end) as '$week_6', SUM(CASE when ss.week='$week_7' AND ss.year='$year_7' then ss.task_hour else 0 end) as '$week_7', SUM(CASE when ss.week='$week_8' AND ss.year='$year_8' then ss.task_hour else 0 end) as '$week_8', SUM(CASE when ss.week='$week_9' AND ss.year='$year_9' then ss.task_hour else 0 end) as '$week_9', SUM(CASE when ss.week='$week_10' AND ss.year='$year_10' then ss.task_hour else 0 end) as '$week_10', SUM(CASE when ss.week='$week_11' AND ss.year='$year_11' then ss.task_hour else 0 end) as '$week_11', SUM(CASE when ss.week='$week_12' AND ss.year='$year_12' then ss.task_hour else 0 end) as '$week_12'"))
                ->whereIn('ss.user_id',$arr)
                ->groupBy('ss.user_id')
                ->get();

                return view('actualsDetails',compact('manager_name','user_id','data','week_no','week_2','week_3','week_4','week_5','week_6','week_7','week_8','week_9','week_10','week_11','week_12'));
    }
    public function deleteActivity(Request $request){

        if(Auth::user()->can('tools-activity-view')){
            $input = $request->all();
            $deleted = SubActivityActuals::where('year',$input['year'])->where('week',$input['week'])
            ->where('project_id',$input['pid'])->where('sub_id',$input['taskId'])->where('task_hour',$input['taskHour'])
            ->where('user_id',$input['uid'])->delete();
        }else{
            return json_encode("failed");
        }
        return json_encode("Deleted");
    } 
    public function uploadFileView(){
        $feedBack = "";
        $color="blue";
        return view('uploadfile',compact('feedBack','color'));
    }
    public function importActivities(Request $request){
        //Get the file from the request
        $file = $request->file;
        //Check if the file is valid
        if($file->isValid()){
        $filePath = $request->file->getClientOriginalName(); 
        $fileextension = $file->getClientOriginalExtension();
        //Check the file type
            if ($fileextension != 'xlsx'){
                $color="red";
                $feedBack = "The loader could not upload your file";
                return view('uploadfile',compact('feedBack',"color"));
            }
            else{
                Excel::import(new activitiesImport, $file);
                $color="#07a64e";
                $feedBack = "The loader has uploaded your data successfully";
                return view('uploadfile',compact('feedBack','color'));
            }
        }
    }
    //Test Function
    public function t(){
        for($i=8, $j=0;$i<69;$i+=6,$j++){
            if($j==12) {
                $j=0;
            }
            echo "{".'"'."Column".$i.'"'.",".'"'."Rejected_Calls".$j.'"'."}".",";
        }
    }
    public function getActualss(){
        $user = User::all();
        return $user;
    }

    /*
     * Get an access token - requires refresh every hour
     * Get trouble tickets from Oceane using the fetched access token
     */

    //  public function getData()
    // {
    //     $tokenUrl = 'https://inside01.api.intraorange/oauth/v3/token';
    //     $tokenUsername = 'k6RucjPMxs0H14VrAOVCN2GM6X7RZHWX';
    //     $tokenPassword = 'Rv5qkzHTDRfQaNqg';
    //     $tokenHeaders = array(
    //         'Authorization: Basic azZSdWNqUE14czBIMTRWckFPVkNOMkdNNlg3UlpIV1g6UnY1cWt6SFREUmZRYU5xZw==',
    //         'Content-Type: application/x-www-form-urlencoded',
    //         'Accept: application/json',
    //     );
    //     $tokenBody = array(
    //        'grant_type' => 'client_credentials',
    //        'Token Name' => 'access_token'
    //     );

    //     $curl_handle=curl_init();
    //     curl_setopt($curl_handle, CURLOPT_URL, $tokenUrl);
    //     curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    //     curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
    //     curl_setopt($curl_handle, CURLOPT_POST, 1);
    //     curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($tokenBody));
    //     curl_setopt($curl_handle, CURLOPT_USERAGENT, 'PostmanRuntime/7.29.0');
    //     curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $tokenHeaders);

    //     $query = curl_exec($curl_handle);
    //     //echo(curl_error($curl_handle));
    //     //echo ($query);
    //     curl_close($curl_handle);
    //     $tokenJson = json_decode($query, true);
    //     $accessToken = $tokenJson['token_type'] . ' ' . $tokenJson['access_token'];
    //     //==========================================================================================

    //     $dataUrl = 'https://inside01.api.intraorange/troubleticket_sandbox_b2b/v1/troubleTicket';
    //     $dataHeaders = array(
    //         'User-Agent: PostmanRuntime/7.29.0',
    //         'Accept: */*',
    //         'Connection: close',
    //         'Authorization: ' . $accessToken,
    //         'X-OAPI-Application-Id: vsgMz9F24VWCy4sJ',
    //         'X-Client-User-Id: MMMM2876'
    //     );
    //     $dataParams = array(
    //         'offset' => '0',
    //         'limit' => '1',
    //         'fields' => 'name,description,externalId,creationDateTime,criticity,detectionDateTime',
    //         'creationDateTime.gte' => '2020-03-03T00:00:00Z',
    //         'creationDateTime.lte' => '2022-03-03T03:00:00Z'
    //     );

    //     $curl_handle=curl_init();
    //     curl_setopt($curl_handle, CURLOPT_URL, $dataUrl . '?' . http_build_query($dataParams));
    //     curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    //     curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
    //     curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($curl_handle, CURLOPT_USERAGENT, 'PostmanRuntime/7.29.0');
    //     curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $dataHeaders);

    //     $query = curl_exec($curl_handle);
    //     curl_close($curl_handle);

    //     ECHO $query;
    // }

    // public function mahmoud(){
    //    $curl_handle=curl_init();
    //     curl_setopt($curl_handle, CURLOPT_URL,'https://petstore.swagger.io/v2/pet/4');
    //     curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    //     curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($curl_handle, CURLOPT_USERAGENT, 'PostmanRuntime/7.29.0');
    //     $query = curl_exec($curl_handle);
    //     curl_close($curl_handle);

    //     echo $query;
    // }


    public function getMohab(){
        $table = DB::table('infopan_sql')->get();

        return $table;
    }
}

