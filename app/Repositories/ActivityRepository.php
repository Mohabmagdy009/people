<?php

namespace App\Repositories;

use App\Activity;
use App\Repositories\ProjectTableRepository;
use App\Repositories\ProjectTableRepositoryV2;
use App\Repositories\UserRepository;
use Auth;
use Datatables;
use DB;

class ActivityRepository
{
    protected $activity;
    protected $userRepository;

    public function __construct(Activity $activity, UserRepository $userRepository)
    {
        $this->activity = $activity;
        $this->userRepository = $userRepository;
    }

    public function getById($id)
    {
        return $this->activity->findOrFail($id);
    }

    public function getByOTL($year, $user_id, $project_id, $from_otl)
    {
        return $this->activity->where('year', $year)->where('user_id', $user_id)->where('project_id', $project_id)->where('from_otl', $from_otl)->pluck('task_hour', 'month');
    }

    public function checkIfExists($inputs)
    {
        return $this->activity
                    ->where('year', $inputs['year'])
                    ->where('month', $inputs['month'])
                    ->where('user_id', $inputs['user_id'])
                    ->where('project_id', $inputs['project_id'])
                    ->where('from_otl', $inputs['from_otl'])
                    ->first();
    }

    public function user_assigned_on_project($year, $user_id, $project_id)
    {
        return $this->activity->where('year', $year)->where('user_id', $user_id)->where('project_id', $project_id)->count();
    }

    public function getByYMPUnum($year, $month, $project_id, $user_id)
    {
        return $this->activity->where('year', $year)->where('month', $month)->where('project_id', $project_id)->where('user_id', $user_id)->count();
    }

    public function getByYMPU($year, $month, $project_id, $user_id)
    {
        return $this->activity->where('year', $year)->where('month', $month)->where('project_id', $project_id)->where('user_id', $user_id)->first();
    }

    public function create(array $inputs)
    {
        $activity = new $this->activity;

        return $this->save($activity, $inputs);
    }

    public function update($id, array $inputs)
    {
        return $this->save($this->getById($id), $inputs);
    }

    public function createOrUpdate($inputs)
    {
        $activity = $this->activity
            ->where('year', $inputs['year'])
            ->where('month', $inputs['month'])
            ->where('project_id', $inputs['project_id'])
            ->where('user_id', $inputs['user_id'])
            ->where('from_otl', '1')
            ->first();

        if (! empty($activity)) {
            return $activity;
        } else {
            $activity = $this->activity
              ->where('year', $inputs['year'])
              ->where('month', $inputs['month'])
              ->where('project_id', $inputs['project_id'])
              ->where('user_id', $inputs['user_id'])
              ->first();
            if (empty($activity)) {
                $activity = new $this->activity;
            }

            return $this->save($activity, $inputs);
        }
    }

    public function assignNewUser($old_user, $inputs)
    {
        $activity = $this->activity
            ->where('year', $inputs['year'])
            ->where('month', $inputs['month'])
            ->where('project_id', $inputs['project_id'])
            ->where('user_id', $old_user)
            ->where('from_otl', '0')
            ->first();

        return $this->save($activity, $inputs);
    }

    public function removeUserFromProject($user_id, $project_id, $year)
    {
        $activity = $this->activity
            ->where('project_id', $project_id)
            ->where('user_id', $user_id)
            ->where('year', $year)
            ->delete();

        return $activity;
    }

    private function save(Activity $activity, array $inputs)
    {
        // Required fields
        if (isset($inputs['year'])) {
            $activity->year = $inputs['year'];
        }
        if (isset($inputs['month'])) {
            $activity->month = $inputs['month'];
        }
        if (isset($inputs['project_id'])) {
            $activity->project_id = $inputs['project_id'];
        }
        if (isset($inputs['user_id'])) {
            $activity->user_id = $inputs['user_id'];
        }
        if (isset($inputs['task_hour'])) {
            $activity->task_hour = $inputs['task_hour'];
        }

        // Boolean
        if (isset($inputs['from_otl'])) {
            $activity->from_otl = $inputs['from_otl'];
        }

        $activity->save();

        return $activity;
    }

    public function destroy($id)
    {
        $activity = $this->getById($id);
        $activity->delete();

        return $activity;
    }

    public function getListOfActivities()
    {
        /** We create here a SQL statement and the Datatables function will add the information it got from the AJAX request to have things like search or limit or show.
         *   So we need to have a proper SQL search that the ajax can use via get with parameters given to it.
         *   In the ajax datatables (view), there will be a parameter name that is going to be used here for the extra parameters so if we use a join,
         *   Then we will need to use in the view page the name of the table.column. This is so that it knows how to do proper sorting or search.
         **/
        $activityList = DB::table('activities')
    ->select('activities.id', 'activities.year','activities.month','activities.task_hour','activities.from_otl',
    'activities.project_id', 'projects.project_name', 'activities.user_id', 'users.name')
    ->leftjoin('projects', 'projects.id', '=', 'activities.project_id')
    ->leftjoin('users', 'users.id', '=', 'activities.user_id');
        $data = Datatables::of($activityList)->make(true);

        return $data;
    }

    public function getListOfActivitiesPerUser($where = null)
    {
        /** We create here a SQL statement and the Datatables function will add the information it got from the AJAX request to have things like search or limit or show.
         *   So we need to have a proper SQL search that the ajax can use via get with parameters given to it.
         *   In the ajax datatables (view), there will be a parameter name that is going to be used here for the extra parameters so if we use a join,
         *   Then we will need to use in the view page the name of the table.column. This is so that it knows how to do proper sorting or search.
         **/

        //dd($where['year'][0]);

        //We receive $where['month'] and we will create $where['months'] as an arry with year and months for the next 12 months
        $where['months'] = [];

        for ($i=$where['month'][0]; $i <= 52 ; $i++) { 
            array_push($where['months'],['year' => $where['year'][0],'month'=>$i]);
        }

        if ($where['month'][0] > 1) {
            for ($i=1; $i <= $where['month'][0]-1 ; $i++) { 
                array_push($where['months'],['year' => $where['year'][0]+1,'month'=>$i]);
            }
        } 

        //dd($where['months']);

        $temp_table = new ProjectTableRepositoryV2('temp_a',$where);

        $activityList = DB::table('temp_a');
        

        $activityList->select('uu.manager_id AS manager_id', 'm.name AS manager_name', 'temp_a.user_id AS user_id', 'u.name AS user_name', 'u.country AS user_country', 'u.employee_type AS user_employee_type', 'u.domain AS user_domain',
                            'temp_a.project_id AS project_id',
                            'p.project_name AS project_name',
                            'p.otl_project_code AS otl_project_code', 'p.meta_activity AS meta_activity', 'p.project_subtype AS project_subtype',
                            'p.technology AS technology', 'p.samba_id AS samba_id', 'p.pullthru_samba_id AS pullthru_samba_id',
                            'p.revenue AS project_revenue', 'p.samba_consulting_product_tcv AS samba_consulting_product_tcv', 'p.samba_pullthru_tcv AS samba_pullthru_tcv',
                            'p.samba_opportunit_owner AS samba_opportunit_owner', 'p.samba_lead_domain AS samba_lead_domain', 'p.samba_stage AS samba_stage',
                            'p.estimated_start_date AS estimated_start_date', 'p.estimated_end_date AS estimated_end_date',
                            'p.gold_order_number AS gold_order_number', 'p.win_ratio AS win_ratio',
                            'c.name AS customer_name', 'c.cluster_owner AS customer_cluster_owner', 'c.country_owner AS customer_country_owner',
                            'p.activity_type AS activity_type', 'p.project_status AS project_status', 'p.project_type AS project_type',
                            'm1_id','m1_com', 'm1_from_otl','m2_id','m2_com', 'm2_from_otl','m3_id','m3_com', 'm3_from_otl',
                            'm4_id','m4_com', 'm4_from_otl','m5_id','m5_com', 'm5_from_otl','m6_id','m6_com', 'm6_from_otl',
                            'm7_id','m7_com', 'm7_from_otl','m8_id','m8_com', 'm8_from_otl','m9_id','m9_com', 'm9_from_otl',
                            'm10_id','m10_com', 'm10_from_otl','m11_id','m11_com', 'm11_from_otl','m12_id','m12_com', 'm12_from_otl','m13_id','m13_com', 'm13_from_otl','m14_id','m14_com', 'm14_from_otl','m15_id','m15_com', 'm15_from_otl','m16_id','m16_com', 'm16_from_otl','m17_id','m17_com', 'm17_from_otl','m18_id','m18_com', 'm18_from_otl','m19_id','m19_com', 'm19_from_otl','m20_id','m20_com', 'm20_from_otl','m21_id','m21_com', 'm21_from_otl','m22_id','m22_com', 'm22_from_otl','m23_id','m23_com', 'm23_from_otl','m24_id','m24_com', 'm24_from_otl','m25_id','m25_com', 'm25_from_otl','m26_id','m26_com', 'm26_from_otl','m27_id','m27_com', 'm27_from_otl','m28_id','m28_com', 'm28_from_otl','m29_id','m29_com', 'm29_from_otl','m30_id','m30_com', 'm30_from_otl','m31_id','m31_com', 'm31_from_otl','m32_id','m32_com', 'm32_from_otl','m33_id','m33_com', 'm33_from_otl','m34_id','m34_com', 'm34_from_otl','m35_id','m35_com', 'm35_from_otl','m36_id','m36com', 'm36_from_otl','m37_id','m37_com', 'm37_from_otl','m38_id','m38_com', 'm38_from_otl','m39_id','m39_com', 'm39_from_otl','m40_id','m40_com', 'm40_from_otl','m41_id','m41_com', 'm41_from_otl','m42_id','m42_com', 'm42_from_otl','m43_id','m43_com', 'm43_from_otl','m44_id','m44_com', 'm44_from_otl','m45_id','m45_com', 'm45_from_otl','m46_id','m46_com', 'm46_from_otl','m47_id','m47_com', 'm47_from_otl','m48_id','m48_com', 'm48_from_otl','m49_id','m49_com', 'm49_from_otl','m50_id','m50_com', 'm50_from_otl','m51_id','m51_com', 'm51_from_otl','m52_id','m52_com', 'm52_from_otl',
                            DB::raw('COUNT(loe.id) as num_of_loe')
        );
        $activityList->leftjoin('projects AS p', 'p.id', '=', 'temp_a.project_id');
        $activityList->leftjoin('project_loe AS loe', 'temp_a.project_id', '=', 'loe.project_id');
        $activityList->leftjoin('users AS u', 'temp_a.user_id', '=', 'u.id');
        $activityList->leftjoin('users_users AS uu', 'u.id', '=', 'uu.user_id');
        $activityList->leftjoin('users AS m', 'm.id', '=', 'uu.manager_id');
        $activityList->leftjoin('customers AS c', 'c.id', '=', 'p.customer_id');

        // Removing customers
        if (! empty($where['except_customers'])) {
            $activityList->where(function ($query) use ($where) {
                foreach ($where['except_customers'] as $w) {
                    $query->where('c.name', '!=', $w);
                }
            });
        }
        // Only customers
        if (! empty($where['only_customers'])) {
            $activityList->where(function ($query) use ($where) {
                foreach ($where['only_customers'] as $w) {
                    $query->orWhere('c.name', $w);
                }
            });
        }

        // Project type
        if (! empty($where['project_type'])) {
            $activityList->where(function ($query) use ($where) {
                foreach ($where['project_type'] as $w) {
                    $query->orWhere('p.project_type', $w);
                }
            });
        }

        // Except project status
        if (! empty($where['except_project_status'])) {
            $activityList->where(function ($query) use ($where) {
                foreach ($where['except_project_status'] as $w) {
                    $query->where('p.project_status', '!=', $w);
                }
            });
        }

        // Check if we need to show closed
        if (! empty($where['checkbox_closed']) && $where['checkbox_closed'] == 1) {
            $activityList->where(function ($query) {
                return $query->where('project_status', '!=', 'Closed')
                    ->orWhereNull('project_status');
            }
        );
        }

        // Checking the roles to see if allowed to see all users
        if (Auth::user()->can('tools-activity-all-view')) {
            // Format of $manager_list is [ 1=> 'manager1', 2=>'manager2',...]
            // Checking which users to show from the manager list
            if (! empty($where['user'])) {
                $activityList->where(function ($query) use ($where) {
                    foreach ($where['user'] as $w) {
                        $query->orWhere('temp_a.user_id', $w);
                    }
                });
            } elseif (! empty($where['manager'])) {
                $activityList->where(function ($query) use ($where) {
                    foreach ($where['manager'] as $w) {
                        $query->orWhere('manager_id', $w);
                    }
                });
            }
        }
        // If the authenticated user is a manager, he can see his employees by default
        elseif (Auth::user()->is_manager == 1) {
            if (! isset($where['user'])) {
                $activityList->where('manager_id', '=', Auth::user()->id);
            }

            if (! empty($where['user'])) {
                $activityList->where(function ($query) use ($where) {
                    foreach ($where['user'] as $w) {
                        $query->orWhere('temp_a.user_id', $w);
                    }
                });
            }
        }
        // In the end, the user is not a manager and doesn't have a special role so he can only see himself
        else {
            $activityList->where('temp_a.user_id', '=', Auth::user()->id);
        }

        $activityList->groupBy('temp_a.project_id','temp_a.user_id');

        //$activityList->groupBy('manager_id','manager_name','user_id','user_name','project_id','project_name','year');
        if (isset($where['no_datatables']) && $where['no_datatables']) {
            $data = $activityList->get();
        } else {
            $data = Datatables::of($activityList)->make(true);
        }

        // Destroying the object so it will remove the 2 temp tables created
        unset($temp_table);

        return $data;
    }

    public function getlistOfLoadPerUser($where = null)
    {
        /** We create here a SQL statement and the Datatables function will add the information it got from the AJAX request to have things like search or limit or show.
         *   So we need to have a proper SQL search that the ajax can use via get with parameters given to it.
         *   In the ajax datatables (view), there will be a parameter name that is going to be used here for the extra parameters so if we use a join,
         *   Then we will need to use in the view page the name of the table.column. This is so that it knows how to do proper sorting or search.
         **/
        $temp_table = new ProjectTableRepositoryVload('temp_a');

        $activityList = DB::table('temp_a');

        $activityList->select('uu.manager_id AS manager_id', 'm.name AS manager_name', 'temp_a.user_id', 'u.name AS user_name', 'year','p.meta_activity',
                            DB::raw('ROUND(SUM(w1_com),1) AS w1_com'),DB::raw('SUM(w1_from_otl) AS w1_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w1_com ELSE 0 END),1) AS w1_bil'),
                            DB::raw('ROUND(SUM(w2_com),1) AS w2_com'),DB::raw('SUM(w2_from_otl) AS w2_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w2_com ELSE 0 END),1) AS w2_bil'),
                            DB::raw('ROUND(SUM(w3_com),1) AS w3_com'),DB::raw('SUM(w3_from_otl) AS w3_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w3_com ELSE 0 END),1) AS w3_bil'),
                            DB::raw('ROUND(SUM(w4_com),1) AS w4_com'),DB::raw('SUM(w4_from_otl) AS w4_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w4_com ELSE 0 END),1) AS w4_bil'),
                            DB::raw('ROUND(SUM(w5_com),1) AS w5_com'),DB::raw('SUM(w5_from_otl) AS w5_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w5_com ELSE 0 END),1) AS w5_bil'),
                            DB::raw('ROUND(SUM(w6_com),1) AS w6_com'),DB::raw('SUM(w6_from_otl) AS w6_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w6_com ELSE 0 END),1) AS w6_bil'),
                            DB::raw('ROUND(SUM(w7_com),1) AS w7_com'),DB::raw('SUM(w7_from_otl) AS w7_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w7_com ELSE 0 END),1) AS w7_bil'),
                            DB::raw('ROUND(SUM(w8_com),1) AS w8_com'),DB::raw('SUM(w8_from_otl) AS w8_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w8_com ELSE 0 END),1) AS w8_bil'),
                            DB::raw('ROUND(SUM(w9_com),1) AS w9_com'),DB::raw('SUM(w9_from_otl) AS w9_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w9_com ELSE 0 END),1) AS w9_bil'),
                            DB::raw('ROUND(SUM(w10_com),1) AS w10_com'),DB::raw('SUM(w10_from_otl) AS w10_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w10_com ELSE 0 END),1) AS w10_bil'),
                            DB::raw('ROUND(SUM(w11_com),1) AS w11_com'),DB::raw('SUM(w11_from_otl) AS w11_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w11_com ELSE 0 END),1) AS w11_bil'),
                            DB::raw('ROUND(SUM(w12_com),1) AS w12_com'),DB::raw('SUM(w12_from_otl) AS w12_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w12_com ELSE 0 END),1) AS w12_bil'),
                            DB::raw('ROUND(SUM(w13_com),1) AS w13_com'),DB::raw('SUM(w13_from_otl) AS w13_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w13_com ELSE 0 END),1) AS w13_bil'),
                            DB::raw('ROUND(SUM(w14_com),1) AS w14_com'),DB::raw('SUM(w14_from_otl) AS w14_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w14_com ELSE 0 END),1) AS w14_bil'),
                            DB::raw('ROUND(SUM(w15_com),1) AS w15_com'),DB::raw('SUM(w15_from_otl) AS w15_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w15_com ELSE 0 END),1) AS w15_bil'),
                            DB::raw('ROUND(SUM(w16_com),1) AS w16_com'),DB::raw('SUM(w16_from_otl) AS w16_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w16_com ELSE 0 END),1) AS w16_bil'),
                            DB::raw('ROUND(SUM(w17_com),1) AS w17_com'),DB::raw('SUM(w17_from_otl) AS w17_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w17_com ELSE 0 END),1) AS w17_bil'),
                            DB::raw('ROUND(SUM(w18_com),1) AS w18_com'),DB::raw('SUM(w18_from_otl) AS w18_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w18_com ELSE 0 END),1) AS w18_bil'),
                            DB::raw('ROUND(SUM(w19_com),1) AS w19_com'),DB::raw('SUM(w19_from_otl) AS w19_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w19_com ELSE 0 END),1) AS w19_bil'),
                            DB::raw('ROUND(SUM(w20_com),1) AS w20_com'),DB::raw('SUM(w20_from_otl) AS w20_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w20_com ELSE 0 END),1) AS w20_bil'),
                            DB::raw('ROUND(SUM(w21_com),1) AS w21_com'),DB::raw('SUM(w21_from_otl) AS w21_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w21_com ELSE 0 END),1) AS w21_bil'),
                            DB::raw('ROUND(SUM(w22_com),1) AS w22_com'),DB::raw('SUM(w22_from_otl) AS w22_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w22_com ELSE 0 END),1) AS w22_bil'),
                            DB::raw('ROUND(SUM(w23_com),1) AS w23_com'),DB::raw('SUM(w23_from_otl) AS w23_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w23_com ELSE 0 END),1) AS w23_bil'),
                            DB::raw('ROUND(SUM(w24_com),1) AS w24_com'),DB::raw('SUM(w24_from_otl) AS w24_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w24_com ELSE 0 END),1) AS w24_bil'),
                            DB::raw('ROUND(SUM(w25_com),1) AS w25_com'),DB::raw('SUM(w25_from_otl) AS w25_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w25_com ELSE 0 END),1) AS w25_bil'),
                            DB::raw('ROUND(SUM(w26_com),1) AS w26_com'),DB::raw('SUM(w26_from_otl) AS w26_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w26_com ELSE 0 END),1) AS w26_bil'),
                            DB::raw('ROUND(SUM(w27_com),1) AS w27_com'),DB::raw('SUM(w27_from_otl) AS w27_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w27_com ELSE 0 END),1) AS w27_bil'),
                            DB::raw('ROUND(SUM(w28_com),1) AS w28_com'),DB::raw('SUM(w28_from_otl) AS w28_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w28_com ELSE 0 END),1) AS w28_bil'),
                            DB::raw('ROUND(SUM(w29_com),1) AS w29_com'),DB::raw('SUM(w29_from_otl) AS w29_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w29_com ELSE 0 END),1) AS w29_bil'),
                            DB::raw('ROUND(SUM(w30_com),1) AS w30_com'),DB::raw('SUM(w30_from_otl) AS w30_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w30_com ELSE 0 END),1) AS w30_bil'),
                            DB::raw('ROUND(SUM(w31_com),1) AS w31_com'),DB::raw('SUM(w31_from_otl) AS w31_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w31_com ELSE 0 END),1) AS w31_bil'),
                            DB::raw('ROUND(SUM(w32_com),1) AS w32_com'),DB::raw('SUM(w32_from_otl) AS w32_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w32_com ELSE 0 END),1) AS w32_bil'),
                            DB::raw('ROUND(SUM(w33_com),1) AS w33_com'),DB::raw('SUM(w33_from_otl) AS w33_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w33_com ELSE 0 END),1) AS w33_bil'),
                            DB::raw('ROUND(SUM(w34_com),1) AS w34_com'),DB::raw('SUM(w34_from_otl) AS w34_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w34_com ELSE 0 END),1) AS w34_bil'),
                            DB::raw('ROUND(SUM(w35_com),1) AS w35_com'),DB::raw('SUM(w35_from_otl) AS w35_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w35_com ELSE 0 END),1) AS w35_bil'),
                            DB::raw('ROUND(SUM(w36_com),1) AS w36_com'),DB::raw('SUM(w36_from_otl) AS w36_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w36_com ELSE 0 END),1) AS w36_bil'),
                            DB::raw('ROUND(SUM(w37_com),1) AS w37_com'),DB::raw('SUM(w37_from_otl) AS w37_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w37_com ELSE 0 END),1) AS w37_bil'),
                            DB::raw('ROUND(SUM(w38_com),1) AS w38_com'),DB::raw('SUM(w38_from_otl) AS w38_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w38_com ELSE 0 END),1) AS w38_bil'),
                            DB::raw('ROUND(SUM(w39_com),1) AS w39_com'),DB::raw('SUM(w39_from_otl) AS w39_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w39_com ELSE 0 END),1) AS w39_bil'),
                            DB::raw('ROUND(SUM(w40_com),1) AS w40_com'),DB::raw('SUM(w40_from_otl) AS w40_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w40_com ELSE 0 END),1) AS w40_bil'),
                            DB::raw('ROUND(SUM(w41_com),1) AS w41_com'),DB::raw('SUM(w41_from_otl) AS w41_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w41_com ELSE 0 END),1) AS w41_bil'),
                            DB::raw('ROUND(SUM(w42_com),1) AS w42_com'),DB::raw('SUM(w42_from_otl) AS w42_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w42_com ELSE 0 END),1) AS w42_bil'),
                            DB::raw('ROUND(SUM(w43_com),1) AS w43_com'),DB::raw('SUM(w43_from_otl) AS w43_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w43_com ELSE 0 END),1) AS w43_bil'),
                            DB::raw('ROUND(SUM(w44_com),1) AS w44_com'),DB::raw('SUM(w44_from_otl) AS w44_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w44_com ELSE 0 END),1) AS w44_bil'),
                            DB::raw('ROUND(SUM(w45_com),1) AS w45_com'),DB::raw('SUM(w45_from_otl) AS w45_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w45_com ELSE 0 END),1) AS w45_bil'),
                            DB::raw('ROUND(SUM(w46_com),1) AS w46_com'),DB::raw('SUM(w46_from_otl) AS w46_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w46_com ELSE 0 END),1) AS w46_bil'),
                            DB::raw('ROUND(SUM(w47_com),1) AS w47_com'),DB::raw('SUM(w47_from_otl) AS w47_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w47_com ELSE 0 END),1) AS w47_bil'),
                            DB::raw('ROUND(SUM(w48_com),1) AS w48_com'),DB::raw('SUM(w48_from_otl) AS w48_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w48_com ELSE 0 END),1) AS w48_bil'),
                            DB::raw('ROUND(SUM(w49_com),1) AS w49_com'),DB::raw('SUM(w49_from_otl) AS w49_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w49_com ELSE 0 END),1) AS w49_bil'),
                            DB::raw('ROUND(SUM(w50_com),1) AS w50_com'),DB::raw('SUM(w50_from_otl) AS w50_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w50_com ELSE 0 END),1) AS w50_bil'),
                            DB::raw('ROUND(SUM(w51_com),1) AS w51_com'),DB::raw('SUM(w51_from_otl) AS w51_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w51_com ELSE 0 END),1) AS w51_bil'),
                            DB::raw('ROUND(SUM(w52_com),1) AS w52_com'),DB::raw('SUM(w52_from_otl) AS w52_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN w52_com ELSE 0 END),1) AS w52_bil')
    );
        $activityList->leftjoin('users_users AS uu', 'temp_a.user_id', '=', 'uu.user_id');
        $activityList->leftjoin('users AS u', 'temp_a.user_id', '=', 'u.id');
        $activityList->leftjoin('users AS m', 'm.id', '=', 'uu.manager_id');
        $activityList->leftjoin('projects AS p', 'p.id', '=', 'temp_a.project_id');

        if (! empty($where['year'])) {
            $activityList->where(function ($query) use ($where) {
                foreach ($where['year'] as $w) {
                    $query->orWhere('year', $w);
                }
            });
        }

        // Checking the roles to see if allowed to see all users
        if (Auth::user()->can('dashboard-all-view')) {
            // Format of $manager_list is [ 1=> 'manager1', 2=>'manager2',...]
            if (! empty($where['user'])) {
                $activityList->where(function ($query) use ($where) {
                    foreach ($where['user'] as $w) {
                        $query->orWhere('temp_a.user_id', $w);
                    }
                });
            } elseif (! empty($where['manager'])) {
                $activityList->where(function ($query) use ($where) {
                    foreach ($where['manager'] as $w) {
                        $query->orWhere('uu.manager_id', $w);
                    }
                });
            }
        } elseif (Auth::user()->is_manager == 1) {
            $activityList->where('manager_id', '=', Auth::user()->id);
            if (! empty($where['user'])) {
                $activityList->where(function ($query) use ($where) {
                    foreach ($where['user'] as $w) {
                        $query->orWhere('temp_a.user_id', $w);
                    }
                });
            }
        } else {
            $activityList->where('temp_a.user_id', '=', Auth::user()->id);
        }

        $activityList->groupBy('manager_id', 'user_id', 'year', 'm.name', 'u.name');

        //dd($activityList->get());

        if (isset($where['datatablesUse']) && ! $where['datatablesUse']) {
            $data = $activityList->get();
        } else {
            $data = Datatables::of($activityList)->make(true);
        }

        unset($temp_table);

        return $data;
    }

    public function getListOfLoadPerUserChart($table, $where, $where_raw)
    {
        /** We create here a SQL statement and the Datatables function will add the information it got from the AJAX request to have things like search or limit or show.
         *   So we need to have a proper SQL search that the ajax can use via get with parameters given to it.
         *   In the ajax datatables (view), there will be a parameter name that is going to be used here for the extra parameters so if we use a join,
         *   Then we will need to use in the view page the name of the table.column. This is so that it knows how to do proper sorting or search.
         **/
        $data = 0;

        $activityList = DB::table($table);

        $activityList->select('year',
                            DB::raw('SUM(w1_com) AS w1_com'),
                            DB::raw('SUM(w2_com) AS w2_com'),
                            DB::raw('SUM(w3_com) AS w3_com'),
                            DB::raw('SUM(w4_com) AS w4_com'),
                            DB::raw('SUM(w5_com) AS w5_com'),
                            DB::raw('SUM(w6_com) AS w6_com'),
                            DB::raw('SUM(w7_com) AS w7_com'),
                            DB::raw('SUM(w8_com) AS w8_com'),
                            DB::raw('SUM(w9_com) AS w9_com'),
                            DB::raw('SUM(w10_com) AS w10_com'),
                            DB::raw('SUM(w11_com) AS w11_com'),
                            DB::raw('SUM(w12_com) AS w12_com'),
                            DB::raw('SUM(w13_com) AS w13_com'),
                            DB::raw('SUM(w14_com) AS w14_com'),
                            DB::raw('SUM(w15_com) AS w15_com'),
                            DB::raw('SUM(w16_com) AS w16_com'),
                            DB::raw('SUM(w17_com) AS w17_com'),
                            DB::raw('SUM(w18_com) AS w18_com'),
                            DB::raw('SUM(w19_com) AS w19_com'),
                            DB::raw('SUM(w20_com) AS w20_com'),
                            DB::raw('SUM(w21_com) AS w21_com'),
                            DB::raw('SUM(w22_com) AS w22_com'),
                            DB::raw('SUM(w23_com) AS w23_com'),
                            DB::raw('SUM(w24_com) AS w24_com'),
                            DB::raw('SUM(w25_com) AS w25_com'),
                            DB::raw('SUM(w26_com) AS w26_com'),
                            DB::raw('SUM(w27_com) AS w27_com'),
                            DB::raw('SUM(w28_com) AS w28_com'),
                            DB::raw('SUM(w29_com) AS w29_com'),
                            DB::raw('SUM(w30_com) AS w30_com'),
                            DB::raw('SUM(w31_com) AS w31_com'),
                            DB::raw('SUM(w32_com) AS w32_com'),
                            DB::raw('SUM(w33_com) AS w33_com'),
                            DB::raw('SUM(w34_com) AS w34_com'),
                            DB::raw('SUM(w35_com) AS w35_com'),
                            DB::raw('SUM(w36_com) AS w36_com'),
                            DB::raw('SUM(w37_com) AS w37_com'),
                            DB::raw('SUM(w38_com) AS w38_com'),
                            DB::raw('SUM(w39_com) AS w39_com'),
                            DB::raw('SUM(w40_com) AS w40_com'),
                            DB::raw('SUM(w41_com) AS w41_com'),
                            DB::raw('SUM(w42_com) AS w42_com'),
                            DB::raw('SUM(w43_com) AS w43_com'),
                            DB::raw('SUM(w44_com) AS w44_com'),
                            DB::raw('SUM(w45_com) AS w45_com'),
                            DB::raw('SUM(w46_com) AS w46_com'),
                            DB::raw('SUM(w47_com) AS w47_com'),
                            DB::raw('SUM(w48_com) AS w48_com'),
                            DB::raw('SUM(w49_com) AS w49_com'),
                            DB::raw('SUM(w50_com) AS w50_com'),
                            DB::raw('SUM(w51_com) AS w51_com'),
                            DB::raw('SUM(w52_com) AS w52_com')
    );
        $activityList->leftjoin('projects AS p', 'p.id', '=', $table.'.project_id');
        $activityList->leftjoin('users_users AS uu', $table.'.user_id', '=', 'uu.user_id');

        if (! empty($where['year'])) {
            $activityList->where(function ($query) use ($where) {
                foreach ($where['year'] as $w) {
                    $query->orWhere('year', $w);
                }
            });
        }

        // Checking the roles to see if allowed to see all users
        if (Auth::user()->can('dashboard-all-view')) {
            // Format of $manager_list is [ 1=> 'manager1', 2=>'manager2',...]
            if (! empty($where['user'])) {
                $activityList->where(function ($query) use ($where,$table) {
                    foreach ($where['user'] as $w) {
                        $query->orWhere($table.'.user_id', $w);
                    }
                });
            } elseif (! empty($where['manager'])) {
                $activityList->where(function ($query) use ($where) {
                    foreach ($where['manager'] as $w) {
                        $query->orWhere('uu.manager_id', $w);
                    }
                });
            }
        } elseif (Auth::user()->is_manager == 1) {
            $activityList->where('manager_id', '=', Auth::user()->id);
            if (! empty($where['user'])) {
                $activityList->where(function ($query) use ($where,$table) {
                    foreach ($where['user'] as $w) {
                        $query->orWhere($table.'.user_id', $w);
                    }
                });
            }
        } else {
            $activityList->where($table.'.user_id', '=', Auth::user()->id);
        }

        if (! empty($where_raw)) {
            $activityList->whereRaw($where_raw);
        }

        $activityList->groupBy('year');

        //dd($activityList->toSql());

        $data = $activityList->get();

        // This is in case we don't find any record then we put everything to 0
        if (count($data) == 0) {
            $data = [];
            $data[0] = new \stdClass();
            $data[0]->year = $where['year'][0];
            $data[0]->w1_com = 0;
            $data[0]->w2_com = 0;
            $data[0]->w3_com = 0;
            $data[0]->w4_com = 0;
            $data[0]->w5_com = 0;
            $data[0]->w6_com = 0;
            $data[0]->w7_com = 0;
            $data[0]->w8_com = 0;
            $data[0]->w9_com = 0;
            $data[0]->w10_com = 0;
            $data[0]->w11_com = 0;
            $data[0]->w12_com = 0;
            $data[0]->w13_com = 0;
            $data[0]->w14_com = 0;
            $data[0]->w15_com = 0;
            $data[0]->w16_com = 0;
            $data[0]->w17_com = 0;
            $data[0]->w18_com = 0;
            $data[0]->w19_com = 0;
            $data[0]->w20_com = 0;
            $data[0]->w21_com = 0;
            $data[0]->w22_com = 0;
            $data[0]->w23_com = 0;
            $data[0]->w24_com = 0;
            $data[0]->w25_com = 0;
            $data[0]->w26_com = 0;
            $data[0]->w27_com = 0;
            $data[0]->w28_com = 0;
            $data[0]->w29_com = 0;
            $data[0]->w30_com = 0;
            $data[0]->w31_com = 0;
            $data[0]->w32_com = 0;
            $data[0]->w33_com = 0;
            $data[0]->w34_com = 0;
            $data[0]->w35_com = 0;
            $data[0]->w36_com = 0;
            $data[0]->w37_com = 0;
            $data[0]->w38_com = 0;
            $data[0]->w39_com = 0;
            $data[0]->w40_com = 0;
            $data[0]->w41_com = 0;
            $data[0]->w42_com = 0;
            $data[0]->w43_com = 0;
            $data[0]->w44_com = 0;
            $data[0]->w45_com = 0;
            $data[0]->w46_com = 0;
            $data[0]->w47_com = 0;
            $data[0]->w48_com = 0;
            $data[0]->w49_com = 0;
            $data[0]->w50_com = 0;
            $data[0]->w51_com = 0;
            $data[0]->w52_com = 0;
        }

        return $data;
    }

    public function getNumberOfOTLPerUserAndProject($user_id, $project_id)
    {
        return $this->activity->where('user_id', $user_id)->where('project_id', $project_id)->where('from_otl', 1)->count();
    }

    public function getNumberPerUserAndProject($user_id, $project_id)
    {
        return $this->activity->where('user_id', $user_id)->where('project_id', $project_id)->count();
    }

    public function getCustomersPerCluster($cluster, $year, $limit, $domain,$users_list)
    {
        $customers = DB::table('projects');

        $customers->select('customers.name', DB::raw('sum(task_hour)'), 'customers.cluster_owner');
        $customers->leftjoin('activities', 'activities.project_id', '=', 'projects.id');
        $customers->leftjoin('customers', 'projects.customer_id', '=', 'customers.id');
        $customers->leftjoin('users', 'activities.user_id', '=', 'users.id');
        $customers->where('projects.project_type', '!=', 'Pre-sales');
        $customers->where('customers.cluster_owner', '=', $cluster);
        $customers->where('customers.name', '!=', 'Orange Business Services');
        $customers->where('activities.year', '=', $year);
        if ($domain != 'all') {
            $customers->where('users.domain', '=', $domain);
        }
        if (!empty($users_list)) {
            $customers->where(function($q) use ($users_list) {
                for($i=0; $i < count($users_list); $i++) {
                    if ($i == 0) {
                        $q->where('users.id', '=', $users_list[$i]);
                    } else {
                        $q->orWhere('users.id', '=', $users_list[$i]);
                    }
                }
            });
        }
        $customers->groupBy('customers.name');
        $customers->orderBy(DB::raw('sum(task_hour)'), 'DESC');
        $customers->limit($limit);
        $data = $customers->get();
        //dd($data);
        return $data;
    }

    public function getActivitiesPerCustomer($customer_name, $year, $temp_table, $domain)
    {
        $activityList = DB::table($temp_table);
        $activityList->leftjoin('projects AS p', 'p.id', '=', $temp_table.'.project_id');
        $activityList->leftjoin('users AS u', $temp_table.'.user_id', '=', 'u.id');
        $activityList->leftjoin('customers AS c', 'c.id', '=', 'p.customer_id');
        $activityList->select('year','project_id','user_id','u.name AS user_name','u.country AS user_country','u.employee_type',
        'w1_com','w2_com','w3_com','w4_com','w5_com','w6_com','w7_com','w8_com','w9_com','w10_com','w11_com','w12_com','w13_com','w14_com','w15_com','w16_com','w17_com','w18_com','w19_com','w20_com','w21_com','w22_com','w23_com','w24_com','w25_com','w26_com','w27_com','w28_com','w29_com','w30_com','w31_com','w32_com','w33_com','w34_com','w35_com','w36_com','w37_com','w38_com','w39_com','w40_com','w41_com','w42_com','w43_com','w44_com','w45_com','w46_com','w47_com','w48_com','w49_com','w50_com','w51_com','w52_com',
        'w1_from_otl','w2_from_otl','w3_from_otl','w4_from_otl','w5_from_otl','w6_from_otl','w7_from_otl','w8_from_otl','w9_from_otl','w10_from_otl','w11_from_otl','w12_from_otl','w13_from_otl','w14_from_otl','w15_from_otl','w16_from_otl','w17_from_otl','w18_from_otl','w19_from_otl','w20_from_otl','w21_from_otl','w22_from_otl','w23_from_otl','w24_from_otl','w25_from_otl','w26_from_otl','w27_from_otl','w28_from_otl','w29_from_otl','w30_from_otl','w31_from_otl','w32_from_otl','w33_from_otl','w34_from_otl','w35_from_otl','w36_from_otl','w37_from_otl','w38_from_otl','w39_from_otl','w40_from_otl','w41_from_otl','w42_from_otl','w43_from_otl','w44_from_otl','w45_from_otl','w46_from_otl','w47_from_otl','w48_from_otl','w49_from_otl','w50_from_otl','w51_from_otl','w52_from_otl',
                                'project_name', 'u.domain AS user_domain');
        $activityList->where('c.name', '=', $customer_name);
        $activityList->where('year', '=', $year);
        $activityList->where('p.project_type', '!=', 'Pre-sales');
        if ($domain != 'all') {
            $activityList->where('u.domain', '=', $domain);
        }
        $activityList->orderBy('p.country')->orderBy('c.name')->orderBy('project_name');
        //$activityList->groupBy('project_name','user_name');
        $data = $activityList->get();
        //dd($data);
        return $data;
    }

    public function getActivitiesPerCustomerTot($customer_name, $year, $temp_table, $domain)
    {
        $activityList = DB::table($temp_table);
        $activityList->leftjoin('projects AS p', 'p.id', '=', $temp_table.'.project_id');
        $activityList->leftjoin('users AS u', $temp_table.'.user_id', '=', 'u.id');
        $activityList->leftjoin('customers AS c', 'c.id', '=', 'p.customer_id');
        $activityList->select('year', DB::raw('sum(w1_com) AS w1_com'), DB::raw('sum(w2_com) AS w2_com'), DB::raw('sum(w3_com) AS w3_com'), DB::raw('sum(w4_com) AS w4_com'), DB::raw('sum(w5_com) AS w5_com'), DB::raw('sum(w6_com) AS w6_com'), DB::raw('sum(w7_com) AS w7_com'), DB::raw('sum(w8_com) AS w8_com'), DB::raw('sum(w9_com) AS w9_com'), DB::raw('sum(w10_com) AS w10_com'), DB::raw('sum(w11_com) AS w11_com'), DB::raw('sum(w12_com) AS w12_com'), DB::raw('sum(w13_com) AS w13_com'), DB::raw('sum(w14_com) AS w14_com'), DB::raw('sum(w15_com) AS w15_com'), DB::raw('sum(w16_com) AS w16_com'), DB::raw('sum(w17_com) AS w17_com'), DB::raw('sum(w18_com) AS w18_com'), DB::raw('sum(w19_com) AS w19_com'), DB::raw('sum(w20_com) AS w20_com'), DB::raw('sum(w21_com) AS w21_com'), DB::raw('sum(w22_com) AS w22_com'), DB::raw('sum(w23_com) AS w23_com'), DB::raw('sum(w24_com) AS w24_com'), DB::raw('sum(w25_com) AS w25_com'), DB::raw('sum(w26_com) AS w26_com'), DB::raw('sum(w27_com) AS w27_com'), DB::raw('sum(w28_com) AS w28_com'), DB::raw('sum(w29_com) AS w29_com'), DB::raw('sum(w30_com) AS w30_com'), DB::raw('sum(w31_com) AS w31_com'), DB::raw('sum(w32_com) AS w32_com'), DB::raw('sum(w33_com) AS w33_com'), DB::raw('sum(w34_com) AS w34_com'), DB::raw('sum(w35_com) AS w35_com'), DB::raw('sum(w36_com) AS w36_com'), DB::raw('sum(w37_com) AS w37_com'), DB::raw('sum(w38_com) AS w38_com'), DB::raw('sum(w39_com) AS w39_com'), DB::raw('sum(w40_com) AS w40_com'), DB::raw('sum(w41_com) AS w41_com'), DB::raw('sum(w42_com) AS w42_com'), DB::raw('sum(w43_com) AS 43_com'), DB::raw('sum(w44_com) AS w44_com'), DB::raw('sum(w45_com) AS w45_com'), DB::raw('sum(w46_com) AS w46_com'), DB::raw('sum(w47_com) AS w47_com'), DB::raw('sum(w48_com) AS w48_com'), DB::raw('sum(w49_com) AS w49_com'), DB::raw('sum(w50_com) AS w50_com'), DB::raw('sum(w51_com) AS w51_com'), DB::raw('sum(w52_com) AS w52_com'));
        $activityList->where('c.name', '=', $customer_name);
        $activityList->where('p.project_type', '!=', 'Pre-sales');
        $activityList->where('year', '=', $year);
        if ($domain != 'all') {
            $activityList->where('u.domain', '=', $domain);
        }
        $activityList->groupBy('c.name');
        $activityList->orderBy('p.country')->orderBy('c.name');
        $data = $activityList->first();
        //dd($data);

        return $data;
    }
}
