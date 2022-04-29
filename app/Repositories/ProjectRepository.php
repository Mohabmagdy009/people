<?php

namespace App\Repositories;

use App\Project;
use Auth;
use Datatables;
use DB;

class ProjectRepository
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getById($id)
    {
        return $this->project->findOrFail($id);
    }

    public function getRevenueByProjectId($id)
    {
        return $this->project->findOrFail($id)->revenues()->get();
    }

    public function getProjectCustomer($id)
    {
        $data = $this->project->findOrFail($id)->customer()->select('name')->get();

        return $data;
    }

    public function getByOTL($otl_project_code, $meta_activity)
    {
        return $this->project->where('otl_project_code', $otl_project_code)->where('meta_activity', $meta_activity)->first();
    }

    public function getByOTLnum($otl_project_code, $meta_activity)
    {
        return $this->project->where('otl_project_code', $otl_project_code)->where('meta_activity', $meta_activity)->count();
    }

    public function getByNameCustomernum($project_name, $customer_id)
    {
        return $this->project->where('project_name', $project_name)->where('customer_id', $customer_id)->count();
    }

    public function getByNameCustomer($project_name, $customer_id)
    {
        return $this->project->where('project_name', $project_name)->where('customer_id', $customer_id)->first();
    }

    public function getBySambaID($samba_id)
    {
        return $this->project->where('samba_id', $samba_id)->where('project_type', 'Pre-sales')->get();
    }

    public function create(array $inputs)
    {
        $project = new $this->project;
        $inputs['created_by_user_id'] = Auth::user()->id;
        // dd($inputs);
        return $this->save($project, $inputs);
    }

    public function update($id, array $inputs)
    {
        return $this->save($this->getById($id), $inputs);
    }

    private function save(Project $project, array $inputs)
    {
        // Required fields
        if (isset($inputs['project_name'])) {
            $project->project_name = $inputs['project_name'];
        }
        if (isset($inputs['customer_id'])) {
            $project->customer_id = $inputs['customer_id'];
        }

        // OTL project code and meta activity can be empty and then it needs to be entered as null

        if (array_key_exists('meta_activity', $inputs)) {
            $project->meta_activity = $inputs['meta_activity'];
        }
        if (array_key_exists('otl_project_code', $inputs)) {
            $project->otl_project_code = $inputs['otl_project_code'];
        }

        // Nullable
        if (array_key_exists('project_type', $inputs)) {
            $project->project_type = $inputs['project_type'];
        }
        if (array_key_exists('activity_type', $inputs)) {
            $project->activity_type = $inputs['activity_type'];
        }
        if (array_key_exists('region', $inputs)) {
            $project->region = $inputs['region'];
        }
        if (array_key_exists('country', $inputs)) {
            $project->country = $inputs['country'];
        }
        if (array_key_exists('customer_location', $inputs)) {
            $project->customer_location = $inputs['customer_location'];
        }
        if (array_key_exists('comments', $inputs)) {
            $project->comments = $inputs['comments'];
        }
        if (array_key_exists('description', $inputs)) {
            $project->description = $inputs['description'];
        }
        if (array_key_exists('technology', $inputs)) {
            $project->technology = $inputs['technology'];
        }
        if (array_key_exists('estimated_start_date', $inputs)) {
            $project->estimated_start_date = $inputs['estimated_start_date'];
        }
        if (array_key_exists('estimated_end_date', $inputs)) {
            $project->estimated_end_date = $inputs['estimated_end_date'];
        }
        if (array_key_exists('LoE_onshore', $inputs)) {
            $project->LoE_onshore = $inputs['LoE_onshore'];
        }
        if (array_key_exists('LoE_nearshore', $inputs)) {
            $project->LoE_nearshore = $inputs['LoE_nearshore'];
        }
        if (array_key_exists('LoE_offshore', $inputs)) {
            $project->LoE_offshore = $inputs['LoE_offshore'];
        }
        if (array_key_exists('LoE_contractor', $inputs)) {
            $project->LoE_contractor = $inputs['LoE_contractor'];
        }
        if (array_key_exists('gold_order_number', $inputs)) {
            $project->gold_order_number = $inputs['gold_order_number'];
        }
        if (array_key_exists('product_code', $inputs)) {
            $project->product_code = $inputs['product_code'];
        }
        if (array_key_exists('revenue', $inputs)) {
            $project->revenue = $inputs['revenue'];
        }
        if (array_key_exists('samba_consulting_product_tcv', $inputs)) {
            $project->samba_consulting_product_tcv = $inputs['samba_consulting_product_tcv'];
        }
        if (array_key_exists('samba_pullthru_tcv', $inputs)) {
            $project->samba_pullthru_tcv = $inputs['samba_pullthru_tcv'];
        }
        if (array_key_exists('project_status', $inputs)) {
            $project->project_status = $inputs['project_status'];
        }
        if (array_key_exists('pullthru_samba_id', $inputs)) {
            $project->pullthru_samba_id = $inputs['pullthru_samba_id'];
        }
        if (array_key_exists('samba_id', $inputs)) {
            $project->samba_id = $inputs['samba_id'];
        }
        if (array_key_exists('samba_opportunit_owner', $inputs)) {
            $project->samba_opportunit_owner = $inputs['samba_opportunit_owner'];
        }
        if (array_key_exists('samba_lead_domain', $inputs)) {
            $project->samba_lead_domain = $inputs['samba_lead_domain'];
        }
        if (array_key_exists('project_subtype', $inputs)) {
            $project->project_subtype = $inputs['project_subtype'];
        }
        if (array_key_exists('samba_stage', $inputs)) {
            $project->samba_stage = $inputs['samba_stage'];
        }
        if (array_key_exists('win_ratio', $inputs)) {
            $project->win_ratio = $inputs['win_ratio'];
        }
        if (array_key_exists('created_by_user_id', $inputs)) {
            $project->created_by_user_id = $inputs['created_by_user_id'];
        }

        if (array_key_exists('solution_complexity', $inputs)) {
            $project->solution_complexity = $inputs['solution_complexity'];
        }

        if (array_key_exists('opportunity_id', $inputs)) {
            $project->opportunity_id = $inputs['opportunity_id'];
        }
        // Boolean
        if (array_key_exists('otl_validated', $inputs)) {
            $project->otl_validated = $inputs['otl_validated'];
        }


        $project->save();

        return $project;
    }

    public function destroy($id)
    {
        $project = $this->getById($id);
        $project->delete();

        return $project;
    }

    public function getAllProjectsList()
    {
        return $this->project->pluck('project_name', 'id');
    }

    public function getListOfProjectsMissingInfo($where = null)
    {
        /** We create here a SQL statement and the Datatables function will add the information it got from the AJAX request to have things like search or limit or show.
         *   So we need to have a proper SQL search that the ajax can use via get with parameters given to it.
         *   In the ajax datatables (view), there will be a parameter name that is going to be used here for the extra parameters so if we use a join,
         *   Then we will need to use in the view page the name of the table.column. This is so that it knows how to do proper sorting or search.
         **/
        $projectList = $this->project
      ->select('u2.name AS manager_name','users.id AS user_id','users.name','projects.id','customers.name AS customer_name','projects.project_name','projects.otl_project_code','projects.project_type',
                'projects.activity_type','projects.project_status','projects.meta_activity','projects.region',
                'projects.country','projects.technology','projects.description','projects.estimated_start_date','projects.estimated_end_date',
                'projects.comments','projects.LoE_onshore','projects.LoE_nearshore',
                'projects.LoE_offshore', 'projects.LoE_contractor', 'projects.gold_order_number', 'projects.product_code', 'projects.revenue', 'projects.win_ratio');
        $projectList->leftjoin('activities', 'project_id', '=', 'projects.id');
        $projectList->leftjoin('users', 'user_id', '=', 'users.id');
        $projectList->leftjoin('users_users', 'users.id', '=', 'users_users.user_id');
        $projectList->leftjoin('users AS u2', 'u2.id', '=', 'users_users.manager_id');
        $projectList->leftjoin('customers', 'projects.customer_id', '=', 'customers.id');
        $projectList->whereRaw("(project_type = '' or activity_type = '' or project_status = '')");
        $projectList->groupBy('users.name', 'projects.id');

        $data = Datatables::of($projectList)->make(true);

        return $data;
    }

    public function getListOfProjectsMissingOTL($where = null)
    {
        /** We create here a SQL statement and the Datatables function will add the information it got from the AJAX request to have things like search or limit or show.
         *   So we need to have a proper SQL search that the ajax can use via get with parameters given to it.
         *   In the ajax datatables (view), there will be a parameter name that is going to be used here for the extra parameters so if we use a join,
         *   Then we will need to use in the view page the name of the table.column. This is so that it knows how to do proper sorting or search.
         **/
        $projectList = $this->project
      ->select('u2.name AS manager_name','users.id AS user_id','users.name','projects.id','customers.name AS customer_name','projects.project_name','projects.otl_project_code','projects.project_type',
                'projects.activity_type','projects.project_status','projects.meta_activity','projects.region',
                'projects.country','projects.technology','projects.description','projects.estimated_start_date','projects.estimated_end_date',
                'projects.comments','projects.LoE_onshore','projects.LoE_nearshore',
                'projects.LoE_offshore', 'projects.LoE_contractor', 'projects.gold_order_number', 'projects.product_code', 'projects.revenue', 'projects.win_ratio');
        $projectList->leftjoin('activities', 'project_id', '=', 'projects.id');
        $projectList->leftjoin('users', 'user_id', '=', 'users.id');
        $projectList->leftjoin('users_users', 'users.id', '=', 'users_users.user_id');
        $projectList->leftjoin('users AS u2', 'u2.id', '=', 'users_users.manager_id');
        $projectList->leftjoin('customers', 'projects.customer_id', '=', 'customers.id');
        $projectList->whereRaw("(otl_project_code = '' or meta_activity = '' or otl_project_code IS NULL or meta_activity IS NULL)");
        $projectList->groupBy('users.name', 'projects.id');

        $data = Datatables::of($projectList)->make(true);

        return $data;
    }

    public function getListOfProjectsLost($where = null)
    {
        /** We create here a SQL statement and the Datatables function will add the information it got from the AJAX request to have things like search or limit or show.
         *   So we need to have a proper SQL search that the ajax can use via get with parameters given to it.
         *   In the ajax datatables (view), there will be a parameter name that is going to be used here for the extra parameters so if we use a join,
         *   Then we will need to use in the view page the name of the table.column. This is so that it knows how to do proper sorting or search.
         **/
        $projectList = $this->project
      ->select('u2.name AS manager_name','users.id AS user_id','users.name','projects.id','customers.name AS customer_name','projects.project_name','projects.otl_project_code','projects.project_type',
                'projects.activity_type','projects.project_status','projects.meta_activity','projects.region',
                'projects.country','projects.technology','projects.description','projects.estimated_start_date','projects.estimated_end_date',
                'projects.comments','projects.LoE_onshore','projects.LoE_nearshore',
                'projects.LoE_offshore', 'projects.LoE_contractor', 'projects.gold_order_number', 'projects.product_code', 'projects.revenue', 'projects.win_ratio');
        $projectList->leftjoin('activities', 'project_id', '=', 'projects.id');
        $projectList->leftjoin('users', 'user_id', '=', 'users.id');
        $projectList->leftjoin('users_users', 'users.id', '=', 'users_users.user_id');
        $projectList->leftjoin('users AS u2', 'u2.id', '=', 'users_users.manager_id');
        $projectList->leftjoin('customers', 'projects.customer_id', '=', 'customers.id');
        $projectList->whereRaw('(win_ratio = 0)');
        $projectList->groupBy('users.name', 'projects.id');

        $data = Datatables::of($projectList)->make(true);

        return $data;
    }

    public function getListOfProjectsAll()
    {
        /** We create here a SQL statement and the Datatables function will add the information it got from the AJAX request to have things like search or limit or show.
         *   So we need to have a proper SQL search that the ajax can use via get with parameters given to it.
         *   In the ajax datatables (view), there will be a parameter name that is going to be used here for the extra parameters so if we use a join,
         *   Then we will need to use in the view page the name of the table.column. This is so that it knows how to do proper sorting or search.
         **/
        $projectList = $this->project
      ->select('projects.id','customers.name AS customer_name','projects.project_name','projects.otl_project_code','projects.project_type',
                'projects.activity_type','projects.project_status','projects.meta_activity','projects.region',
                'projects.country','projects.technology','projects.description','projects.estimated_start_date','projects.estimated_end_date',
                'projects.comments','projects.LoE_onshore','projects.LoE_nearshore',
                'projects.LoE_offshore', 'projects.LoE_contractor', 'projects.gold_order_number', 'projects.product_code', 'projects.revenue', 'projects.win_ratio');
        $projectList->leftjoin('customers', 'projects.customer_id', '=', 'customers.id');

        $data = Datatables::of($projectList)->make(true);

        return $data;
    }
}
