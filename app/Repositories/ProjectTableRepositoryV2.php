<?php

namespace App\Repositories;

use Auth;
use Datatables;
use DB;

class ProjectTableRepositoryV2
{
    // We are going to create 1 temporary table and we need to protect them
    // make sure you use unset() on the object reference so that it will call destruct and free up memory
    private $table_name_cols;
    private $where;

    // When creating the object, please pass the name of 2 tables that will be created...
    public function __construct($table_name_cols,$where)
    {
        $this->table_name_cols = $table_name_cols;
        $this->where = $where;
        $this->create_temp_table_with_months_as_columns($this->table_name_cols,$this->where);
    }

    public function __destruct()
    {
        $this->destroy_table($this->table_name_cols);
    }

    public function create_temp_table_with_months_as_columns($table_name_cols,$where)
    {
        DB::unprepared(
            DB::raw('
                DROP TABLE IF EXISTS '.$table_name_cols.';
            ')
        );

        DB::unprepared(
            DB::raw('
                CREATE TEMPORARY TABLE '.$table_name_cols.'
                    (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    project_id INT(10),
                    user_id INT(10),
                    m1_id INT(10),
                    m1_com double(8,2) DEFAULT 0,
                    m2_id INT(10),
                    m2_com double(8,2) DEFAULT 0,
                    m3_id INT(10),
                    m3_com double(8,2) DEFAULT 0,
                    m4_id INT(10),
                    m4_com double(8,2) DEFAULT 0,
                    m5_id INT(10),
                    m5_com double(8,2) DEFAULT 0,
                    m6_id INT(10),
                    m6_com double(8,2) DEFAULT 0,
                    m7_id INT(10),
                    m7_com double(8,2) DEFAULT 0,
                    m8_id INT(10),
                    m8_com double(8,2) DEFAULT 0,
                    m9_id INT(10),
                    m9_com double(8,2) DEFAULT 0,
                    m10_id INT(10),
                    m10_com double(8,2) DEFAULT 0,
                    m11_id INT(10),
                    m11_com double(8,2) DEFAULT 0,
                    m12_id INT(10),
                    m12_com double(8,2) DEFAULT 0
                    );
            ')
        );

        $is_manager = Auth::user()->is_manager;
        $manager_id = Auth::user()->id;
        $is_admin = Auth::user()->hasRole('Admin');

        if($is_manager == 1 || $is_admin == 1){
            $years = [$where['months'][0]['year'],$where['months'][11]['year']];
            if($is_admin == 1){
                $users = DB::table('users')->where('is_manager', 0)->get();
                $arr = [];
                foreach($users as $key => $val){
                    array_push($arr,$val->id);
                }
            }
            else{
                $users = DB::table('users_users')->where('manager_id', $manager_id)->get();
                $arr = [];
                foreach($users as $key => $val){
                    array_push($arr,$val->user_id);
                }
            }
            DB::unprepared(
                DB::raw('
                    INSERT INTO '.$table_name_cols.' (`project_id`,`user_id`) (SELECT `project_id`,`user_id` FROM `activities` WHERE `year` = '.$years[0].' group by `user_id`);
                ')
            );

            if ($years[0] != $years[1]) {
                DB::unprepared(
                    DB::raw('
                        INSERT INTO '.$table_name_cols.' (`project_id`,`user_id`) (SELECT `project_id`,`user_id` FROM `activities` WHERE `year` = '.$years[1].' group by `user_id`)ON DUPLICATE KEY UPDATE m1_com = 0;
                    ')
                );
            }

            foreach ($where['months'] as $key => $month) {
                $ref = $key+1;
                foreach($arr as $x){
                    DB::unprepared(
                        DB::raw('
                            UPDATE '.$table_name_cols.' t, activities a SET t.m'.$ref.'_com=(SELECT sum(task_hour) FROM `activities` where month ='.$month['month'].' and year ='.$month['year'].' and user_id = '.$x.'), t.m'.$ref.'_id=0 where t.user_id='.$x.';
                        ')
                    );
            }
        }
            return;
        }else{
            DB::unprepared(
            DB::raw('
                ALTER TABLE '.$table_name_cols.' ADD UNIQUE( `project_id`, `user_id`);
            ')
        );

        $years = [$where['months'][0]['year'],$where['months'][11]['year']];

        DB::unprepared(
            DB::raw('
                INSERT INTO '.$table_name_cols.' (`project_id`,`user_id`) (SELECT `project_id`,`user_id` FROM `activities` WHERE `year` = '.$years[0].' group by `project_id`,`user_id`);
            ')
        );

        if ($years[0] != $years[1]) {
            DB::unprepared(
                DB::raw('
                    INSERT INTO '.$table_name_cols.' (`project_id`,`user_id`) (SELECT `project_id`,`user_id` FROM `activities` WHERE `year` = '.$years[1].' group by `project_id`,`user_id`) ON DUPLICATE KEY UPDATE m1_com = 0;
                ')
            );
        }

        foreach ($where['months'] as $key => $month) {
            $ref = $key+1;
            DB::unprepared(
                DB::raw('
                    UPDATE '.$table_name_cols.' t, activities a SET t.m'.$ref.'_com=a.task_hour,t.m'.$ref.'_id=a.id WHERE a.year='.$month['year'].' AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.month = '.$month['month'].';
                    
                ')
            );
          
        }
        return;
        }
    }

    public function destroy_table($table_name)
    {
        $dropTempTables = DB::unprepared(
         DB::raw('
             DROP TABLE IF EXISTS '.$table_name.';
         ')
    );
    }
}
