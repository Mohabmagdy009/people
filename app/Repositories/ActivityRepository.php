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
        
    $activityList = DB::table('activities')
    ->select('activities.id', 'activities.year','activities.month','activities.task_hour','activities.from_otl',
    'activities.project_id', 'projects.project_name', 'activities.user_id', 'users.name')
    ->leftjoin('projects', 'projects.id', '=', 'activities.project_id')
    ->leftjoin('users', 'users.id', '=', 'activities.user_id');
        $data = Datatables::of($activityList)->make(true);

        return $data;
    }

    
    public function getListOfActivitiesPerUser($where = null) //ajax post
    {

        $where['months'] = [];

        for ($i=$where['month'][0]; $i <= 52 ; $i++) { 
            array_push($where['months'],['year' => $where['year'][0],'month'=>$i]);
        }

        if ($where['month'][0] > 1) {
            for ($i=1; $i <= $where['month'][0]-1 ; $i++) { 
                array_push($where['months'],['year' => $where['year'][0]+1,'month'=>$i]);
            }
        } 

        $temp_table = new ProjectTableRepositoryV2('temp_a',$where);

        $activityList = DB::table('temp_a');

            
        
        $activityList->select('uu.manager_id AS manager_id', 'm.name AS manager_name', 'temp_a.user_id AS user_id', 'u.name AS user_name', 'u.country AS user_country', 'u.employee_type AS user_employee_type', 'u.domain AS user_domain','temp_a.project_id AS project_id','p.project_name AS project_name','p.otl_project_code AS otl_project_code', 'p.meta_activity AS meta_activity', 'p.project_subtype AS project_subtype','p.technology AS technology', 'p.samba_id AS samba_id', 'p.pullthru_samba_id AS pullthru_samba_id','p.revenue AS project_revenue', 'p.samba_consulting_product_tcv AS samba_consulting_product_tcv', 'p.samba_pullthru_tcv AS samba_pullthru_tcv','p.samba_opportunit_owner AS samba_opportunit_owner', 'p.samba_lead_domain AS samba_lead_domain', 'p.samba_stage AS samba_stage','p.estimated_start_date AS estimated_start_date', 'p.estimated_end_date AS estimated_end_date','p.gold_order_number AS gold_order_number', 'p.win_ratio AS win_ratio','c.name AS customer_name', 'c.cluster_owner AS customer_cluster_owner', 'c.country_owner AS customer_country_owner','p.activity_type AS activity_type', 'p.project_status AS project_status', 'p.project_type AS project_type','mm.created_at AS created_at',
            'm1_id','m1_com','m2_id','m2_com', 'm3_id','m3_com', 'm4_id','m4_com', 'm5_id','m5_com', 'm6_id','m6_com', 'm7_id','m7_com', 'm8_id','m8_com', 'm9_id','m9_com', 'm10_id','m10_com', 'm11_id','m11_com', 'm12_id','m12_com','m13_id','m13_com','m14_id','m14_com', 'm15_id','m15_com','m16_id','m16_com','m17_id','m17_com','m18_id','m18_com','m19_id','m19_com','m20_id','m20_com','m21_id','m21_com','m22_id','m22_com','m23_id','m23_com','m24_id','m24_com','m25_id','m25_com','m26_id','m26_com', 'm27_id','m27_com','m28_id','m28_com','m29_id','m29_com','m30_id','m30_com','m31_id','m31_com','m32_id','m32_com','m33_id','m33_com','m34_id','m34_com','m35_id','m35_com','m36_id','m36_com','m37_id','m37_com','m38_id','m38_com','m39_id','m39_com','m40_id','m40_com','m41_id','m41_com','m42_id','m42_com','m43_id','m43_com','m44_id','m44_com','m45_id','m45_com','m46_id','m46_com','m47_id','m47_com','m48_id','m48_com','m49_id','m49_com','m50_id','m50_com','m51_id','m51_com','m52_id','m52_com'
                );
                $activityList->leftjoin('projects AS p', 'p.id', '=', 'temp_a.project_id');
                $activityList->leftjoin('project_loe AS loe', 'temp_a.project_id', '=', 'loe.project_id');
                $activityList->leftjoin('users AS u', 'temp_a.user_id', '=', 'u.id');
                $activityList->leftjoin('users_users AS uu', 'u.id', '=', 'uu.user_id');
                $activityList->leftjoin('users AS m', 'm.id', '=', 'uu.manager_id');
                $activityList->leftjoin('customers AS c', 'c.id', '=', 'p.customer_id');
                $activityList->leftjoin('activities AS mm', 'mm.project_id', '=', 'temp_a.project_id');
                $activityList->orderBy('created_at','asc');


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
        if (Auth::user()->is_manager == 1){
           $activityList->groupBy('temp_a.user_id');
        }else{
            $activityList->groupBy('temp_a.project_id','temp_a.user_id');
        }

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

    public function getListOfActualsPerUser($where = null) //ajax post
    {

        $where['months'] = [];

        for ($i=$where['month'][0]; $i <= 52 ; $i++) { 
            array_push($where['months'],['year' => $where['year'][0],'month'=>$i]);
        }

        if ($where['month'][0] > 1) {
            for ($i=1; $i <= $where['month'][0]-1 ; $i++) { 
                array_push($where['months'],['year' => $where['year'][0]+1,'month'=>$i]);
            }
        } 

        $temp_table = new ProjectTableRepositoryV3('temp_a',$where);

        $activityList = DB::table('temp_a');

            
        
        $activityList->select('uu.manager_id AS manager_id', 'm.name AS manager_name', 'temp_a.user_id AS user_id', 'u.name AS user_name', 'u.country AS user_country', 'u.employee_type AS user_employee_type', 'u.domain AS user_domain','temp_a.project_id AS project_id','p.project_name AS project_name','p.otl_project_code AS otl_project_code', 'p.meta_activity AS meta_activity', 'p.project_subtype AS project_subtype','p.technology AS technology', 'p.samba_id AS samba_id', 'p.pullthru_samba_id AS pullthru_samba_id','p.revenue AS project_revenue', 'p.samba_consulting_product_tcv AS samba_consulting_product_tcv', 'p.samba_pullthru_tcv AS samba_pullthru_tcv','p.samba_opportunit_owner AS samba_opportunit_owner', 'p.samba_lead_domain AS samba_lead_domain', 'p.samba_stage AS samba_stage','p.estimated_start_date AS estimated_start_date', 'p.estimated_end_date AS estimated_end_date','p.gold_order_number AS gold_order_number', 'p.win_ratio AS win_ratio','c.name AS customer_name', 'c.cluster_owner AS customer_cluster_owner', 'c.country_owner AS customer_country_owner','p.activity_type AS activity_type', 'p.project_status AS project_status', 'p.project_type AS project_type',
            'm1_id','m1_com','m2_id','m2_com', 'm3_id','m3_com', 'm4_id','m4_com', 'm5_id','m5_com', 'm6_id','m6_com', 'm7_id','m7_com', 'm8_id','m8_com', 'm9_id','m9_com', 'm10_id','m10_com', 'm11_id','m11_com', 'm12_id','m12_com','m13_id','m13_com','m14_id','m14_com', 'm15_id','m15_com','m16_id','m16_com','m17_id','m17_com','m18_id','m18_com','m19_id','m19_com','m20_id','m20_com','m21_id','m21_com','m22_id','m22_com','m23_id','m23_com','m24_id','m24_com','m25_id','m25_com','m26_id','m26_com', 'm27_id','m27_com','m28_id','m28_com','m29_id','m29_com','m30_id','m30_com','m31_id','m31_com','m32_id','m32_com','m33_id','m33_com','m34_id','m34_com','m35_id','m35_com','m36_id','m36_com','m37_id','m37_com','m38_id','m38_com','m39_id','m39_com','m40_id','m40_com','m41_id','m41_com','m42_id','m42_com','m43_id','m43_com','m44_id','m44_com','m45_id','m45_com','m46_id','m46_com','m47_id','m47_com','m48_id','m48_com','m49_id','m49_com','m50_id','m50_com','m51_id','m51_com','m52_id','m52_com'
                );
                $activityList->leftjoin('projects AS p', 'p.id', '=', 'temp_a.project_id');
                $activityList->leftjoin('project_loe AS loe', 'temp_a.project_id', '=', 'loe.project_id');
                $activityList->leftjoin('users AS u', 'temp_a.user_id', '=', 'u.id');
                $activityList->leftjoin('users_users AS uu', 'u.id', '=', 'uu.user_id');
                $activityList->leftjoin('users AS m', 'm.id', '=', 'uu.manager_id');
                $activityList->leftjoin('customers AS c', 'c.id', '=', 'p.customer_id');
                $activityList->leftjoin('subactivityactuals AS mm', 'mm.project_id', '=', 'temp_a.project_id');



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
           $activityList->groupBy('temp_a.user_id');
        

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

    public function c($where = null) //ajax post
    {
        
        $where['months'] = [];

        for ($i=$where['month'][0]; $i <= 52 ; $i++) { 
            array_push($where['months'],['year' => $where['year'][0],'month'=>$i]);
        }

        if ($where['month'][0] > 1) {
            for ($i=1; $i <= $where['month'][0]-1 ; $i++) { 
                array_push($where['months'],['year' => $where['year'][0]+1,'month'=>$i]);
            }
        } 

        // dd($where['months']);

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
                            'p.activity_type AS activity_type', 'p.project_status AS project_status', 'p.project_type AS project_type','mm.created_at AS created_at',
                            'm1_id','m1_com', 'm1_from_otl','m2_id','m2_com', 'm2_from_otl','m3_id','m3_com', 'm3_from_otl',
                            'm4_id','m4_com', 'm4_from_otl','m5_id','m5_com', 'm5_from_otl','m6_id','m6_com', 'm6_from_otl',
                            'm7_id','m7_com', 'm7_from_otl','m8_id','m8_com', 'm8_from_otl','m9_id','m9_com', 'm9_from_otl',
                            'm10_id','m10_com', 'm10_from_otl','m11_id','m11_com', 'm11_from_otl','m12_id','m12_com', 'm12_from_otl','m13_id','m13_com','m13_from_otl', 'm14_id','m14_com', 'm14_from_otl','m15_id','m15_com', 'm15_from_otl','m16_id','m16_com', 'm16_from_otl',
                            'm17_id','m17_com', 'm17_from_otl','m18_id','m18_com', 'm18_from_otl','m19_id','m19_com', 'm19_from_otl',
                            'm20_id','m20_com', 'm20_from_otl','m21_id','m21_com', 'm21_from_otl','m22_id','m22_com', 'm22_from_otl',
                            'm23_id','m23_com', 'm23_from_otl','m24_id','m24_com', 'm24_from_otl','m25_id','m25_com', 'm25_from_otl','m26_id','m26_com','m26_from_otl', 'm27_id','m27_com', 'm27_from_otl','m28_id','m28_com', 'm28_from_otl','m29_id','m29_com', 'm29_from_otl',
                            'm30_id','m30_com', 'm30_from_otl','m31_id','m31_com', 'm31_from_otl','m32_id','m32_com', 'm32_from_otl',
                            'm33_id','m33_com', 'm33_from_otl','m34_id','m34_com', 'm34_from_otl','m35_id','m35_com', 'm35_from_otl',
                            'm36_id','m36_com', 'm36_from_otl','m37_id','m37_com', 'm37_from_otl','m38_id','m38_com', 'm38_from_otl','m39_id','m39_com','m39_from_otl', 'm40_id','m40_com', 'm40_from_otl','m41_id','m41_com', 'm41_from_otl','m42_id','m42_com', 'm42_from_otl',
                            'm43_id','m43_com', 'm43_from_otl','m44_id','m44_com', 'm44_from_otl','m45_id','m45_com', 'm45_from_otl',
                            'm46_id','m46_com', 'm46_from_otl','m47_id','m47_com', 'm47_from_otl','m48_id','m48_com', 'm48_from_otl',
                            'm49_id','m49_com', 'm49_from_otl','m50_id','m50_com', 'm50_from_otl','m51_id','m51_com', 'm51_from_otl','m52_id','m52_com','m52_from_otl',
                            DB::raw('COUNT(loe.id) as num_of_loe')
        );
        $activityList->leftjoin('projects AS p', 'p.id', '=', 'temp_a.project_id');
        $activityList->leftjoin('project_loe AS loe', 'temp_a.project_id', '=', 'loe.project_id');
        $activityList->leftjoin('users AS u', 'temp_a.user_id', '=', 'u.id');
        $activityList->leftjoin('users_users AS uu', 'u.id', '=', 'uu.user_id');
        $activityList->leftjoin('users AS m', 'm.id', '=', 'uu.manager_id');
        $activityList->leftjoin('customers AS c', 'c.id', '=', 'p.customer_id');
        $activityList->leftjoin('activities AS mm', 'mm.project_id', '=', 'temp_a.project_id');
        $activityList->orderBy('created_at','asc');

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
                            DB::raw('ROUND(SUM(jan_com),1) AS jan_com'),DB::raw('SUM(jan_from_otl) AS jan_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN jan_com ELSE 0 END),1) AS jan_bil'),
                            DB::raw('ROUND(SUM(feb_com),1) AS feb_com'),DB::raw('SUM(feb_from_otl) AS feb_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN feb_com ELSE 0 END),1) AS feb_bil'),
                            DB::raw('ROUND(SUM(mar_com),1) AS mar_com'),DB::raw('SUM(mar_from_otl) AS mar_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN mar_com ELSE 0 END),1) AS mar_bil'),
                            DB::raw('ROUND(SUM(apr_com),1) AS apr_com'),DB::raw('SUM(apr_from_otl) AS apr_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN apr_com ELSE 0 END),1) AS apr_bil'),
                            DB::raw('ROUND(SUM(may_com),1) AS may_com'),DB::raw('SUM(may_from_otl) AS may_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN may_com ELSE 0 END),1) AS may_bil'),
                            DB::raw('ROUND(SUM(jun_com),1) AS jun_com'),DB::raw('SUM(jun_from_otl) AS jun_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN jun_com ELSE 0 END),1) AS jun_bil'),
                            DB::raw('ROUND(SUM(jul_com),1) AS jul_com'),DB::raw('SUM(jul_from_otl) AS jul_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN jul_com ELSE 0 END),1) AS jul_bil'),
                            DB::raw('ROUND(SUM(aug_com),1) AS aug_com'),DB::raw('SUM(aug_from_otl) AS aug_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN aug_com ELSE 0 END),1) AS aug_bil'),
                            DB::raw('ROUND(SUM(sep_com),1) AS sep_com'),DB::raw('SUM(sep_from_otl) AS sep_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN sep_com ELSE 0 END),1) AS sep_bil'),
                            DB::raw('ROUND(SUM(oct_com),1) AS oct_com'),DB::raw('SUM(oct_from_otl) AS oct_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN oct_com ELSE 0 END),1) AS oct_bil'),
                            DB::raw('ROUND(SUM(nov_com),1) AS nov_com'),DB::raw('SUM(nov_from_otl) AS nov_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN nov_com ELSE 0 END),1) AS nov_bil'),
                            DB::raw('ROUND(SUM(dec_com),1) AS dec_com'),DB::raw('SUM(dec_from_otl) AS dec_from_otl'),DB::raw('ROUND(SUM(CASE WHEN p.meta_activity="BILLABLE" THEN dec_com ELSE 0 END),1) AS dec_bil')
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
                            DB::raw('SUM(jan_com) AS jan_com'),
                            DB::raw('SUM(feb_com) AS feb_com'),
                            DB::raw('SUM(mar_com) AS mar_com'),
                            DB::raw('SUM(apr_com) AS apr_com'),
                            DB::raw('SUM(may_com) AS may_com'),
                            DB::raw('SUM(jun_com) AS jun_com'),
                            DB::raw('SUM(jul_com) AS jul_com'),
                            DB::raw('SUM(aug_com) AS aug_com'),
                            DB::raw('SUM(sep_com) AS sep_com'),
                            DB::raw('SUM(oct_com) AS oct_com'),
                            DB::raw('SUM(nov_com) AS nov_com'),
                            DB::raw('SUM(dec_com) AS dec_com')
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
            $data[0]->jan_com = 0;
            $data[0]->feb_com = 0;
            $data[0]->mar_com = 0;
            $data[0]->apr_com = 0;
            $data[0]->may_com = 0;
            $data[0]->jun_com = 0;
            $data[0]->jul_com = 0;
            $data[0]->aug_com = 0;
            $data[0]->sep_com = 0;
            $data[0]->oct_com = 0;
            $data[0]->nov_com = 0;
            $data[0]->dec_com = 0;
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
        'jan_com','feb_com','mar_com','apr_com','may_com','jun_com','jul_com','aug_com','sep_com','oct_com','nov_com','dec_com',
        'jan_from_otl','feb_from_otl','mar_from_otl','apr_from_otl','may_from_otl','jun_from_otl','jul_from_otl','aug_from_otl','sep_from_otl','oct_from_otl','nov_from_otl','dec_from_otl',
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
        $activityList->select('year', DB::raw('sum(jan_com) AS jan_com'), DB::raw('sum(feb_com) AS feb_com'), DB::raw('sum(mar_com) AS mar_com'), DB::raw('sum(apr_com) AS apr_com'), DB::raw('sum(may_com) AS may_com'), DB::raw('sum(jun_com) AS jun_com'), DB::raw('sum(jul_com) AS jul_com'), DB::raw('sum(aug_com) AS aug_com'), DB::raw('sum(sep_com) AS sep_com'), DB::raw('sum(oct_com) AS oct_com'), DB::raw('sum(nov_com) AS nov_com'), DB::raw('sum(dec_com) AS dec_com'));
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
