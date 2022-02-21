<?php

namespace App\Http\Controllers;

use App\Action;
use App\Http\Controllers\Controller;
use Auth;
use Datatables;
use DB;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function __construct()
    {
    }

    public function actionList(Request $request)
    {
        $inputs = $request->all();
        //dd($inputs);

        $actionList = DB::table('actions');
        $actionList->select('actions.id AS action_id', 'created_by.id AS created_by_id', 'created_by.name AS created_by_name',
                        'assigned.id AS assigned_to_id', 'assigned.name AS assigned_to_name', 'actions.name AS action_name',
                        'actions.section AS action_section', 'actions.severity AS action_severity',
                        'actions.status AS action_status', 'actions.project_id AS project_id', 'projects.project_name AS project_name',
                        'actions.estimated_start_date AS action_estimated_start_date', 'actions.estimated_end_date AS action_estimated_end_date',
                        'actions.description AS action_description'
        );
        $actionList->leftjoin('users AS assigned', 'actions.assigned_user_id', '=', 'assigned.id');
        $actionList->leftjoin('users AS created_by', 'actions.user_id', '=', 'created_by.id');
        $actionList->leftjoin('projects', 'actions.project_id', '=', 'projects.id');

        if (! empty($inputs['user'])) {
            $actionList->where(function ($query) use ($inputs) {
                foreach ($inputs['user'] as $i) {
                    $query->orWhere('actions.assigned_user_id', $i);
                }
            });
        }

        if (isset($inputs['section'])) {
            $actionList->where('section', $inputs['section']);
        }

        if (isset($inputs['project_id'])) {
            $actionList->where('project_id', $inputs['project_id']);
        }

        if (isset($inputs['hide_closed']) && $inputs['hide_closed'] == 'true') {
            $actionList->where('actions.status', '!=', 'closed');
        }

        $data = $actionList->get();

        return $data;
    }

    public function actionListAjax($project_id = -1)
    {
        $actionList = DB::table('actions');
        $actionList->select('customers.id AS customer_id', 'customers.name AS customer_name',
                        'actions.project_id AS project_id', 'projects.project_name AS project_name',
                        'actions.id AS action_id', 'created_by.id AS created_by_id', 'created_by.name AS created_by_name',
                        'assigned.id AS assigned_to_user_id', 'assigned.name AS assigned_to_name', 'actions.name AS action_name', 'actions.requestor AS action_requestor',
                        'actions.severity AS action_severity', 'actions.status AS action_status', 'actions.percent_complete AS percent_complete',
                        'actions.estimated_start_date AS action_start_date', 'actions.estimated_end_date AS action_end_date',
                        'actions.description AS action_description',
                        'actions.next_action_description AS next_action_description', 'actions.next_action_dependency AS next_action_dependency', 'actions.next_action_due_date AS next_action_due_date',
                        'actions.created_at AS created_at', 'actions.updated_at AS updated_at'
        );
        $actionList->leftjoin('users AS assigned', 'actions.assigned_user_id', '=', 'assigned.id');
        $actionList->leftjoin('users AS created_by', 'actions.user_id', '=', 'created_by.id');
        $actionList->leftjoin('projects', 'actions.project_id', '=', 'projects.id');
        $actionList->leftjoin('customers', 'projects.customer_id', '=', 'customers.id');
        $actionList->where('section', 'project');

        if ($project_id != -1) {
            $actionList->where('project_id', $project_id);
        }

        $data = Datatables::of($actionList)->make(true);

        return $data;
    }

    public function actionInsertUpdate(Request $request)
    {
        $inputs = $request->all();
        $result = new \stdClass();
        $id = $inputs['id'];
        unset($inputs['id']);
        if ($inputs['project_id'] == 'null') {
            unset($inputs['project_id']);
        }

        if (! isset($id)) {
            $insert_result = Action::create($inputs);
            if ($insert_result != null) {
                $result->result = 'success';
                $result->box_type = 'success';
                $result->message_type = 'success';
                $result->msg = 'Record added successfully';
            } else {
                $result->result = 'error';
                $result->box_type = 'danger';
                $result->message_type = 'error';
                $result->msg = 'Record issue';
            }
        } else {
            $update_result = Action::find($id);
            $update_result->update($inputs);
            if ($update_result != null) {
                $result->result = 'success';
                $result->box_type = 'success';
                $result->message_type = 'success';
                $result->msg = 'Record updated successfully';
            } else {
                $result->result = 'error';
                $result->box_type = 'danger';
                $result->message_type = 'error';
                $result->msg = 'Record issue';
            }
        }

        return json_encode($result);
    }

    public function actionDelete(Request $request)
    {
        $inputs = $request->all();
        $result = new \stdClass();
        $delete_result = Action::find($inputs['id']);

        if ($delete_result->user_id == Auth::user()->id || Auth::user()->can('action-all')) {
            $delete_result->delete();
            $result->result = 'success';
            $result->box_type = 'success';
            $result->message_type = 'success';
            $result->msg = 'Record deleted successfully';
        } else {
            $result->result = 'error';
            $result->box_type = 'danger';
            $result->message_type = 'error';
            $result->msg = 'No permission to delete record';
        }
    }

    public function projectActionDelete($action_id)
    {
        $result = new \stdClass();
        $delete_result = Action::find($action_id);
        $project_id = $delete_result->project_id;

        if ($delete_result->user_id == Auth::user()->id || Auth::user()->can('action-all')) {
            $delete_result->delete();
            $result->result = 'success';
            $result->box_type = 'success';
            $result->message_type = 'success';
            $result->msg = 'Record deleted successfully';
        } else {
            $result->result = 'error';
            $result->box_type = 'danger';
            $result->message_type = 'error';
            $result->msg = 'No permission to delete record';
        }
        $num_of_actions = Action::where('project_id', '=', $project_id)->get()->count();
        $result->num_of_actions = $num_of_actions;

        return json_encode($result);
    }

    public function store(Request $request)
    {
        // First we need to validate the data we received
        $data = $request->validate([
            'assigned_user_id' => 'required',
            'name' => 'required',
            'section' => 'required',
            'project_id' => 'required',
        ]);
        
        $result = new \stdClass();
        $inputs = $request->all();

        $inputs["user_id"] = Auth::user()->id;

        // Create a new record
        Action::create($inputs);

        $result->result = 'success';
        $result->msg = 'Record added successfully';

        $num_of_actions = Action::where('project_id', '=', $inputs["project_id"])->get()->count();
        $result->num_of_actions = $num_of_actions;

        return json_encode($result);
    }

    public function update(Request $request, $id)
    {
        // First we need to validate the data we received
        $data = $request->validate([
            'assigned_user_id' => 'required',
            'name' => 'required',
            'section' => 'required'
        ]);

        $result = new \stdClass();
        $inputs = $request->all();

        // Update a record
        $update_result = Action::find($id);

        if ($update_result->user_id == Auth::user()->id || $update_result->assigned_user_id == Auth::user()->id || Auth::user()->can('action-all')) {
            $update_result->update($inputs);
            $result->result = 'success';
            $result->msg = 'Record updated successfully';
        } else {
            $result->result = 'error';
            $result->msg = 'No permission to update record';
        }

        $project_id = $update_result->project_id;
        $num_of_actions = Action::where('project_id', '=', $project_id)->get()->count();
        $result->num_of_actions = $num_of_actions;

        return json_encode($result);
    }
}
