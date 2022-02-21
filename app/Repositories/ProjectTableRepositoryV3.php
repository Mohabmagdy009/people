<?php

namespace App\Repositories;

use Auth;
use Datatables;
use DB;

class ProjectTableRepositoryV3
{
    // We are going to create 1 temporary table and we need to protect them
    // manke sure you use unset() on the object reference so that it will call destruct and free up memory
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
                    m1_from_otl tinyint(1) DEFAULT 0,
                    m2_id INT(10),
                    m2_com double(8,2) DEFAULT 0,
                    m2_from_otl tinyint(1) DEFAULT 0,
                    m3_id INT(10),
                    m3_com double(8,2) DEFAULT 0,
                    m3_from_otl tinyint(1) DEFAULT 0,
                    m4_id INT(10),
                    m4_com double(8,2) DEFAULT 0,
                    m4_from_otl tinyint(1) DEFAULT 0,
                    m5_id INT(10),
                    m5_com double(8,2) DEFAULT 0,
                    m5_from_otl tinyint(1) DEFAULT 0,
                    m6_id INT(10),
                    m6_com double(8,2) DEFAULT 0,
                    m6_from_otl tinyint(1) DEFAULT 0,
                    m7_id INT(10),
                    m7_com double(8,2) DEFAULT 0,
                    m7_from_otl tinyint(1) DEFAULT 0,
                    m8_id INT(10),
                    m8_com double(8,2) DEFAULT 0,
                    m8_from_otl tinyint(1) DEFAULT 0,
                    m9_id INT(10),
                    m9_com double(8,2) DEFAULT 0,
                    m9_from_otl tinyint(1) DEFAULT 0,
                    m10_id INT(10),
                    m10_com double(8,2) DEFAULT 0,
                    m10_from_otl tinyint(1) DEFAULT 0,
                    m11_id INT(10),
                    m11_com double(8,2) DEFAULT 0,
                    m11_from_otl tinyint(1) DEFAULT 0,
                    m12_id INT(10),
                    m12_com double(8,2) DEFAULT 0,
                    m12_from_otl tinyint(1) DEFAULT 0,
                    m13_id INT(10),
                    m13_com double(8,2) DEFAULT 0,
                    m13_from_otl tinyint(1) DEFAULT 0,
                    m14_id INT(10),
                    m14_com double(8,2) DEFAULT 0,
                    m14_from_otl tinyint(1) DEFAULT 0,
                    m15_id INT(10),
                    m15_com double(8,2) DEFAULT 0,
                    m15_from_otl tinyint(1) DEFAULT 0,
                    m16_id INT(10),
                    m16_com double(8,2) DEFAULT 0,
                    m16_from_otl tinyint(1) DEFAULT 0,
                    m17_id INT(10),
                    m17_com double(8,2) DEFAULT 0,
                    m17_from_otl tinyint(1) DEFAULT 0,
                    m18_id INT(10),
                    m18_com double(8,2) DEFAULT 0,
                    m18_from_otl tinyint(1) DEFAULT 0,
                    m19_id INT(10),
                    m19_com double(8,2) DEFAULT 0,
                    m19_from_otl tinyint(1) DEFAULT 0,
                    m20_id INT(10),
                    m20_com double(8,2) DEFAULT 0,
                    m20_from_otl tinyint(1) DEFAULT 0,
                    m21_id INT(10),
                    m21_com double(8,2) DEFAULT 0,
                    m21_from_otl tinyint(1) DEFAULT 0,
                    m22_id INT(10),
                    m22_com double(8,2) DEFAULT 0,
                    m22_from_otl tinyint(1) DEFAULT 0,
                    m23_id INT(10),
                    m23_com double(8,2) DEFAULT 0,
                    m23_from_otl tinyint(1) DEFAULT 0,
                    m24_id INT(10),
                    m24_com double(8,2) DEFAULT 0,
                    m24_from_otl tinyint(1) DEFAULT 0,
                    m25_id INT(10),
                    m25_com double(8,2) DEFAULT 0,
                    m25_from_otl tinyint(1) DEFAULT 0,
                    m26_id INT(10),
                    m26_com double(8,2) DEFAULT 0,
                    m26_from_otl tinyint(1) DEFAULT 0,
                    m27_id INT(10),
                    m27_com double(8,2) DEFAULT 0,
                    m27_from_otl tinyint(1) DEFAULT 0,
                    m28_id INT(10),
                    m28_com double(8,2) DEFAULT 0,
                    m28_from_otl tinyint(1) DEFAULT 0,
                    m29_id INT(10),
                    m29_com double(8,2) DEFAULT 0,
                    m29_from_otl tinyint(1) DEFAULT 0,
                    m30_id INT(10),
                    m30_com double(8,2) DEFAULT 0,
                    m30_from_otl tinyint(1) DEFAULT 0,
                    m31_id INT(10),
                    m31_com double(8,2) DEFAULT 0,
                    m31_from_otl tinyint(1) DEFAULT 0,
                    m32_id INT(10),
                    m32_com double(8,2) DEFAULT 0,
                    m32_from_otl tinyint(1) DEFAULT 0,
                    m33_id INT(10),
                    m33_com double(8,2) DEFAULT 0,
                    m33_from_otl tinyint(1) DEFAULT 0,
                    m34_id INT(10),
                    m34_com double(8,2) DEFAULT 0,
                    m34_from_otl tinyint(1) DEFAULT 0,
                    m35_id INT(10),
                    m35_com double(8,2) DEFAULT 0,
                    m35_from_otl tinyint(1) DEFAULT 0,
                    m36_id INT(10),
                    m36_com double(8,2) DEFAULT 0,
                    m36_from_otl tinyint(1) DEFAULT 0,
                    m37_id INT(10),
                    m37_com double(8,2) DEFAULT 0,
                    m37_from_otl tinyint(1) DEFAULT 0,
                    m38_id INT(10),
                    m38_com double(8,2) DEFAULT 0,
                    m38_from_otl tinyint(1) DEFAULT 0,
                    m39_id INT(10),
                    m39_com double(8,2) DEFAULT 0,
                    m39_from_otl tinyint(1) DEFAULT 0,
                    m40_id INT(10),
                    m40_com double(8,2) DEFAULT 0,
                    m40_from_otl tinyint(1) DEFAULT 0,
                    m41_id INT(10),
                    m41_com double(8,2) DEFAULT 0,
                    m41_from_otl tinyint(1) DEFAULT 0,
                    m42_id INT(10),
                    m42_com double(8,2) DEFAULT 0,
                    m42_from_otl tinyint(1) DEFAULT 0,
                    m43_id INT(10),
                    m43_com double(8,2) DEFAULT 0,
                    m43_from_otl tinyint(1) DEFAULT 0,
                    m44_id INT(10),
                    m44_com double(8,2) DEFAULT 0,
                    m44_from_otl tinyint(1) DEFAULT 0,
                    m45_id INT(10),
                    m45_com double(8,2) DEFAULT 0,
                    m45_from_otl tinyint(1) DEFAULT 0,
                    m46_id INT(10),
                    m46_com double(8,2) DEFAULT 0,
                    m46_from_otl tinyint(1) DEFAULT 0,
                    m47_id INT(10),
                    m47_com double(8,2) DEFAULT 0,
                    m47_from_otl tinyint(1) DEFAULT 0,
                    m48_id INT(10),
                    m48_com double(8,2) DEFAULT 0,
                    m48_from_otl tinyint(1) DEFAULT 0,
                    m49_id INT(10),
                    m49_com double(8,2) DEFAULT 0,
                    m49_from_otl tinyint(1) DEFAULT 0,
                    m50_id INT(10),
                    m50_com double(8,2) DEFAULT 0,
                    m50_from_otl tinyint(1) DEFAULT 0,
                    m51_id INT(10),
                    m51_com double(8,2) DEFAULT 0,
                    m51_from_otl tinyint(1) DEFAULT 0,
                    m52_id INT(10),
                    m52_com double(8,2) DEFAULT 0,
                    m52_from_otl tinyint(1) DEFAULT 0
                    );
            ')
        );


            $years = [$where['months'][0]['year'],$where['months'][51]['year']];
            $users = DB::table('subactivityactuals');
            $user = $users->select(DB::raw('distinct user_id'))->get();
            $arr = [965,967];
            // foreach($user as $key => $val){
            //     array_push($arr,$val->user_id);
            // }
        

            DB::unprepared(
                DB::raw('
                    INSERT INTO '.$table_name_cols.' (`project_id`,`user_id`) (SELECT `project_id`,`user_id` FROM `subactivityactuals` WHERE `year` = '.$years[0].' group by `user_id`);
                ')
            );

            if ($years[0] != $years[1]) {
                DB::unprepared(
                    DB::raw('
                        INSERT INTO '.$table_name_cols.' (`project_id`,`user_id`) (SELECT `project_id`,`user_id` FROM `subactivityactuals` WHERE `year` = '.$years[1].' group by `user_id`)ON DUPLICATE KEY UPDATE m1_com = 0;
                    ')
                );
            }

            foreach ($where['months'] as $key => $month) {
                $ref = $key+1;
                foreach($arr as $x){
                    DB::unprepared(
                        DB::raw('
                            UPDATE '.$table_name_cols.' t, subactivityactuals a SET t.m'.$ref.'_com=(SELECT sum(task_hour) FROM `subactivityactuals` where week ='.$month['month'].' and year ='.$month['year'].' and user_id = '.$x.'), t.m'.$ref.'_from_otl=0,t.m'.$ref.'_id=0 where t.user_id='.$x.';
                        ')
                    );
                }
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
