<?php

namespace App\Repositories;

use Auth;
use Datatables;
use DB;

class ProjectTableRepositoryVload
{
    // This version gives only the value from OTL if it exists or the value from the user if no OTL value has been found
    // We are going to create 1 temporary table and we need to protect them
    // manke sure you use unset() on the object reference so that it will call destruct and free up memory
    private $table_name_cols;

    // When creating the object, please pass the name of 2 tables that will be created...
    public function __construct($table_name_cols)
    {
        $this->table_name_cols = $table_name_cols;
        $this->create_temp_table_with_months_as_columns($this->table_name_cols);
    }

    public function __destruct()
    {
        $this->destroy_table($this->table_name_cols);
    }

    public function create_temp_table_with_months_as_columns($table_name_cols)
    {
        $dropTempTables = DB::unprepared(
         DB::raw('
             DROP TABLE IF EXISTS '.$table_name_cols.';
         ')
    );

        $createTempTable = DB::unprepared(DB::raw('
      CREATE TEMPORARY TABLE '.$table_name_cols.'
(
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          year INT(10),
          project_id INT(10),
          user_id INT(10),
          w1_com double(8,2) DEFAULT 0,
          w1_from_otl tinyint(1) DEFAULT 0,
          w2_com double(8,2) DEFAULT 0,
          w2_from_otl tinyint(1) DEFAULT 0,
          w3_com double(8,2) DEFAULT 0,
          w3_from_otl tinyint(1) DEFAULT 0,
          w4_com double(8,2) DEFAULT 0,
          w4_from_otl tinyint(1) DEFAULT 0,
          w5_com double(8,2) DEFAULT 0,
          w5_from_otl tinyint(1) DEFAULT 0,
          w6_com double(8,2) DEFAULT 0,
          w6_from_otl tinyint(1) DEFAULT 0,
          w7_com double(8,2) DEFAULT 0,
          w7_from_otl tinyint(1) DEFAULT 0,
          w8_com double(8,2) DEFAULT 0,
          w8_from_otl tinyint(1) DEFAULT 0,
          w9_com double(8,2) DEFAULT 0,
          w9_from_otl tinyint(1) DEFAULT 0,
          w10_com double(8,2) DEFAULT 0,
          w10_from_otl tinyint(1) DEFAULT 0,
          w11_com double(8,2) DEFAULT 0,
          w11_from_otl tinyint(1) DEFAULT 0,
          w12_com double(8,2) DEFAULT 0,
          w12_from_otl tinyint(1) DEFAULT 0,
          w13_com double(8,2) DEFAULT 0,
          w13_from_otl tinyint(1) DEFAULT 0,
          w14_com double(8,2) DEFAULT 0,
          w14_from_otl tinyint(1) DEFAULT 0,
          w15_com double(8,2) DEFAULT 0,
          w15_from_otl tinyint(1) DEFAULT 0,
          w16_com double(8,2) DEFAULT 0,
          w16_from_otl tinyint(1) DEFAULT 0,
          w17_com double(8,2) DEFAULT 0,
          w17_from_otl tinyint(1) DEFAULT 0,
          w18_com double(8,2) DEFAULT 0,
          w18_from_otl tinyint(1) DEFAULT 0,
          w19_com double(8,2) DEFAULT 0,
          w19_from_otl tinyint(1) DEFAULT 0,
          w20_com double(8,2) DEFAULT 0,
          w20_from_otl tinyint(1) DEFAULT 0,
          w21_com double(8,2) DEFAULT 0,
          w21_from_otl tinyint(1) DEFAULT 0,
          w22_com double(8,2) DEFAULT 0,
          w22_from_otl tinyint(1) DEFAULT 0,
          w23_com double(8,2) DEFAULT 0,
          w23_from_otl tinyint(1) DEFAULT 0,
          w24_com double(8,2) DEFAULT 0,
          w24_from_otl tinyint(1) DEFAULT 0,
          w25_com double(8,2) DEFAULT 0,
          w25_from_otl tinyint(1) DEFAULT 0,
          w26_com double(8,2) DEFAULT 0,
          w26_from_otl tinyint(1) DEFAULT 0,
          w27_com double(8,2) DEFAULT 0,
          w27_from_otl tinyint(1) DEFAULT 0,
          w28_com double(8,2) DEFAULT 0,
          w28_from_otl tinyint(1) DEFAULT 0,
          w29_com double(8,2) DEFAULT 0,
          w29_from_otl tinyint(1) DEFAULT 0,
          w30_com double(8,2) DEFAULT 0,
          w30_from_otl tinyint(1) DEFAULT 0,
          w31_com double(8,2) DEFAULT 0,
          w31_from_otl tinyint(1) DEFAULT 0,
          w32_com double(8,2) DEFAULT 0,
          w32_from_otl tinyint(1) DEFAULT 0,
          w33_com double(8,2) DEFAULT 0,
          w33_from_otl tinyint(1) DEFAULT 0,
          w34_com double(8,2) DEFAULT 0,
          w34_from_otl tinyint(1) DEFAULT 0,
          w35_com double(8,2) DEFAULT 0,
          w35_from_otl tinyint(1) DEFAULT 0,
          w36_com double(8,2) DEFAULT 0,
          w36_from_otl tinyint(1) DEFAULT 0,
          w37_com double(8,2) DEFAULT 0,
          w37_from_otl tinyint(1) DEFAULT 0,
          w38_com double(8,2) DEFAULT 0,
          w38_from_otl tinyint(1) DEFAULT 0,
          w39_com double(8,2) DEFAULT 0,
          w39_from_otl tinyint(1) DEFAULT 0,
          w40_com double(8,2) DEFAULT 0,
          w40_from_otl tinyint(1) DEFAULT 0,
          w41_com double(8,2) DEFAULT 0,
          w41_from_otl tinyint(1) DEFAULT 0,
          w42_com double(8,2) DEFAULT 0,
          w42_from_otl tinyint(1) DEFAULT 0,
          w43_com double(8,2) DEFAULT 0,
          w43_from_otl tinyint(1) DEFAULT 0,
          w44_com double(8,2) DEFAULT 0,
          w44_from_otl tinyint(1) DEFAULT 0,
          w45_com double(8,2) DEFAULT 0,
          w45_from_otl tinyint(1) DEFAULT 0,
          w46_com double(8,2) DEFAULT 0,
          w46_from_otl tinyint(1) DEFAULT 0,
          w47_com double(8,2) DEFAULT 0,
          w47_from_otl tinyint(1) DEFAULT 0,
          w48_com double(8,2) DEFAULT 0,
          w48_from_otl tinyint(1) DEFAULT 0,
          w49_com double(8,2) DEFAULT 0,
          w49_from_otl tinyint(1) DEFAULT 0,
          w50_com double(8,2) DEFAULT 0,
          w50_from_otl tinyint(1) DEFAULT 0,
          w51_com double(8,2) DEFAULT 0,
          w51_from_otl tinyint(1) DEFAULT 0,
          w52_com double(8,2) DEFAULT 0,
          w52_from_otl tinyint(1) DEFAULT 0
);

ALTER TABLE '.$table_name_cols.' ADD UNIQUE( `year`,`project_id`, `user_id`);

INSERT INTO '.$table_name_cols.' (`year`,`project_id`,`user_id`) (SELECT `year`,`project_id`,`user_id` FROM `activities` group by `year`,`project_id`,`user_id`);

UPDATE '.$table_name_cols.' t, activities a SET t.w1_com=a.task_hour,t.w1_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 1;
UPDATE '.$table_name_cols.' t, activities a SET t.w1_com=a.task_hour,t.w1_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 1;

UPDATE '.$table_name_cols.' t, activities a SET t.w2_com=a.task_hour,t.w2_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 2;
UPDATE '.$table_name_cols.' t, activities a SET t.w2_com=a.task_hour,t.w2_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 2;

UPDATE '.$table_name_cols.' t, activities a SET t.w3_com=a.task_hour,t.w3_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 3;
UPDATE '.$table_name_cols.' t, activities a SET t.w3_com=a.task_hour,t.w3_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 3;

UPDATE '.$table_name_cols.' t, activities a SET t.w4_com=a.task_hour,t.w4_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 4;
UPDATE '.$table_name_cols.' t, activities a SET t.w4_com=a.task_hour,t.w4_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 4;

UPDATE '.$table_name_cols.' t, activities a SET t.w5_com=a.task_hour,t.w5_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 5;
UPDATE '.$table_name_cols.' t, activities a SET t.w5_com=a.task_hour,t.w5_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 5;

UPDATE '.$table_name_cols.' t, activities a SET t.w6_com=a.task_hour,t.w6_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 6;
UPDATE '.$table_name_cols.' t, activities a SET t.w6_com=a.task_hour,t.w6_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 6;

UPDATE '.$table_name_cols.' t, activities a SET t.w7_com=a.task_hour,t.w7_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 7;
UPDATE '.$table_name_cols.' t, activities a SET t.w7_com=a.task_hour,t.w7_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 7;

UPDATE '.$table_name_cols.' t, activities a SET t.w8_com=a.task_hour,t.w8_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 8;
UPDATE '.$table_name_cols.' t, activities a SET t.w8_com=a.task_hour,t.w8_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 8;

UPDATE '.$table_name_cols.' t, activities a SET t.w9_com=a.task_hour,t.w9_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 9;
UPDATE '.$table_name_cols.' t, activities a SET t.w9_com=a.task_hour,t.w9_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 9;

UPDATE '.$table_name_cols.' t, activities a SET t.w10_com=a.task_hour,t.w10_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 10;
UPDATE '.$table_name_cols.' t, activities a SET t.w10_com=a.task_hour,t.w10_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 10;

UPDATE '.$table_name_cols.' t, activities a SET t.w11_com=a.task_hour,t.w11_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 11;
UPDATE '.$table_name_cols.' t, activities a SET t.w11_com=a.task_hour,t.w11_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 11;

UPDATE '.$table_name_cols.' t, activities a SET t.w12_com=a.task_hour,t.w12_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 12;
UPDATE '.$table_name_cols.' t, activities a SET t.w12_com=a.task_hour,t.w12_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 12;
UPDATE '.$table_name_cols.' t, activities a SET t.w13_com=a.task_hour,t.w13_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 13;
UPDATE '.$table_name_cols.' t, activities a SET t.w13_com=a.task_hour,t.w13_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 13;

UPDATE '.$table_name_cols.' t, activities a SET t.w14_com=a.task_hour,t.w14_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 14;
UPDATE '.$table_name_cols.' t, activities a SET t.w14_com=a.task_hour,t.w14_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 14;

UPDATE '.$table_name_cols.' t, activities a SET t.w15_com=a.task_hour,t.w15_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 15;
UPDATE '.$table_name_cols.' t, activities a SET t.w15_com=a.task_hour,t.w15_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 15;

UPDATE '.$table_name_cols.' t, activities a SET t.w16_com=a.task_hour,t.w16_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 16;
UPDATE '.$table_name_cols.' t, activities a SET t.w16_com=a.task_hour,t.w16_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 16;

UPDATE '.$table_name_cols.' t, activities a SET t.w17_com=a.task_hour,t.w17_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 17;
UPDATE '.$table_name_cols.' t, activities a SET t.w17_com=a.task_hour,t.w17_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 17;

UPDATE '.$table_name_cols.' t, activities a SET t.w18_com=a.task_hour,t.w18_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 18;
UPDATE '.$table_name_cols.' t, activities a SET t.w18_com=a.task_hour,t.w18_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 18;

UPDATE '.$table_name_cols.' t, activities a SET t.w19_com=a.task_hour,t.w19_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 19;
UPDATE '.$table_name_cols.' t, activities a SET t.w19_com=a.task_hour,t.w19_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 19;

UPDATE '.$table_name_cols.' t, activities a SET t.w20_com=a.task_hour,t.w20_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 20;
UPDATE '.$table_name_cols.' t, activities a SET t.w20_com=a.task_hour,t.w20_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 20;

UPDATE '.$table_name_cols.' t, activities a SET t.w21_com=a.task_hour,t.w21_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 21;
UPDATE '.$table_name_cols.' t, activities a SET t.w21_com=a.task_hour,t.w21_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 21;

UPDATE '.$table_name_cols.' t, activities a SET t.w22_com=a.task_hour,t.w22_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 22;
UPDATE '.$table_name_cols.' t, activities a SET t.w22_com=a.task_hour,t.w22_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 22;

UPDATE '.$table_name_cols.' t, activities a SET t.w23_com=a.task_hour,t.w23_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 23;
UPDATE '.$table_name_cols.' t, activities a SET t.w23_com=a.task_hour,t.w23_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 23;

UPDATE '.$table_name_cols.' t, activities a SET t.w24_com=a.task_hour,t.w24_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 24;
UPDATE '.$table_name_cols.' t, activities a SET t.w24_com=a.task_hour,t.w24_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 24;
UPDATE '.$table_name_cols.' t, activities a SET t.w25_com=a.task_hour,t.w25_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 25;
UPDATE '.$table_name_cols.' t, activities a SET t.w25_com=a.task_hour,t.w25_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 25;

UPDATE '.$table_name_cols.' t, activities a SET t.w26_com=a.task_hour,t.w26_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 26;
UPDATE '.$table_name_cols.' t, activities a SET t.w26_com=a.task_hour,t.w26_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 26;

UPDATE '.$table_name_cols.' t, activities a SET t.w27_com=a.task_hour,t.w27_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 27;
UPDATE '.$table_name_cols.' t, activities a SET t.w27_com=a.task_hour,t.w27_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 27;

UPDATE '.$table_name_cols.' t, activities a SET t.w28_com=a.task_hour,t.w28_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 28;
UPDATE '.$table_name_cols.' t, activities a SET t.w28_com=a.task_hour,t.w28_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 28;

UPDATE '.$table_name_cols.' t, activities a SET t.w29_com=a.task_hour,t.w29_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 29;
UPDATE '.$table_name_cols.' t, activities a SET t.w29_com=a.task_hour,t.w29_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 29;

UPDATE '.$table_name_cols.' t, activities a SET t.w30_com=a.task_hour,t.w30_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 30;
UPDATE '.$table_name_cols.' t, activities a SET t.w30_com=a.task_hour,t.w30_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 30;

UPDATE '.$table_name_cols.' t, activities a SET t.w31_com=a.task_hour,t.w31_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 31;
UPDATE '.$table_name_cols.' t, activities a SET t.w31_com=a.task_hour,t.w31_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 31;

UPDATE '.$table_name_cols.' t, activities a SET t.w32_com=a.task_hour,t.w32_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 32;
UPDATE '.$table_name_cols.' t, activities a SET t.w32_com=a.task_hour,t.w32_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 32;

UPDATE '.$table_name_cols.' t, activities a SET t.w33_com=a.task_hour,t.w33_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 33;
UPDATE '.$table_name_cols.' t, activities a SET t.w33_com=a.task_hour,t.w33_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 33;

UPDATE '.$table_name_cols.' t, activities a SET t.w34_com=a.task_hour,t.w34_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 34;
UPDATE '.$table_name_cols.' t, activities a SET t.w34_com=a.task_hour,t.w34_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 34;

UPDATE '.$table_name_cols.' t, activities a SET t.w35_com=a.task_hour,t.w35_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 35;
UPDATE '.$table_name_cols.' t, activities a SET t.w35_com=a.task_hour,t.w35_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 35;

UPDATE '.$table_name_cols.' t, activities a SET t.w36_com=a.task_hour,t.w36_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 36;
UPDATE '.$table_name_cols.' t, activities a SET t.w36_com=a.task_hour,t.w36_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 36;
UPDATE '.$table_name_cols.' t, activities a SET t.w37_com=a.task_hour,t.w37_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 37;
UPDATE '.$table_name_cols.' t, activities a SET t.w37_com=a.task_hour,t.w37_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 37;

UPDATE '.$table_name_cols.' t, activities a SET t.w38_com=a.task_hour,t.w38_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 38;
UPDATE '.$table_name_cols.' t, activities a SET t.w38_com=a.task_hour,t.w38_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 38;

UPDATE '.$table_name_cols.' t, activities a SET t.w39_com=a.task_hour,t.w39_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 39;
UPDATE '.$table_name_cols.' t, activities a SET t.w39_com=a.task_hour,t.w39_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 39;

UPDATE '.$table_name_cols.' t, activities a SET t.w40_com=a.task_hour,t.w40_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 40;
UPDATE '.$table_name_cols.' t, activities a SET t.w40_com=a.task_hour,t.w40_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 40;

UPDATE '.$table_name_cols.' t, activities a SET t.w41_com=a.task_hour,t.w41_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 41;
UPDATE '.$table_name_cols.' t, activities a SET t.w41_com=a.task_hour,t.w41_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 41;

UPDATE '.$table_name_cols.' t, activities a SET t.w42_com=a.task_hour,t.w42_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 42;
UPDATE '.$table_name_cols.' t, activities a SET t.w42_com=a.task_hour,t.w42_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 42;

UPDATE '.$table_name_cols.' t, activities a SET t.w43_com=a.task_hour,t.w43_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 43;
UPDATE '.$table_name_cols.' t, activities a SET t.w43_com=a.task_hour,t.w43_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 43;

UPDATE '.$table_name_cols.' t, activities a SET t.w44_com=a.task_hour,t.w44_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 44;
UPDATE '.$table_name_cols.' t, activities a SET t.w44_com=a.task_hour,t.w44_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 44;

UPDATE '.$table_name_cols.' t, activities a SET t.w45_com=a.task_hour,t.w45_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 45;
UPDATE '.$table_name_cols.' t, activities a SET t.w45_com=a.task_hour,t.w45_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 45;

UPDATE '.$table_name_cols.' t, activities a SET t.w46_com=a.task_hour,t.w46_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 46;
UPDATE '.$table_name_cols.' t, activities a SET t.w46_com=a.task_hour,t.w46_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 46;

UPDATE '.$table_name_cols.' t, activities a SET t.w47_com=a.task_hour,t.w47_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 47;
UPDATE '.$table_name_cols.' t, activities a SET t.w47_com=a.task_hour,t.w47_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 47;

UPDATE '.$table_name_cols.' t, activities a SET t.w48_com=a.task_hour,t.w48_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 48;
UPDATE '.$table_name_cols.' t, activities a SET t.w48_com=a.task_hour,t.w48_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 48;
UPDATE '.$table_name_cols.' t, activities a SET t.w49_com=a.task_hour,t.w49_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 49;
UPDATE '.$table_name_cols.' t, activities a SET t.w49_com=a.task_hour,t.w49_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 49;

UPDATE '.$table_name_cols.' t, activities a SET t.w50_com=a.task_hour,t.w50_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 50;
UPDATE '.$table_name_cols.' t, activities a SET t.w50_com=a.task_hour,t.w50_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 50;

UPDATE '.$table_name_cols.' t, activities a SET t.w51_com=a.task_hour,t.w51_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 51;
UPDATE '.$table_name_cols.' t, activities a SET t.w51_com=a.task_hour,t.w51_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 51;

UPDATE '.$table_name_cols.' t, activities a SET t.w52_com=a.task_hour,t.w52_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 52;
UPDATE '.$table_name_cols.' t, activities a SET t.w52_com=a.task_hour,t.w52_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 52;

      '));

        return $createTempTable;
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
