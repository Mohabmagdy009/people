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
  jan_com double(8,2) DEFAULT 0,
  jan_from_otl tinyint(1) DEFAULT 0,
  feb_com double(8,2) DEFAULT 0,
  feb_from_otl tinyint(1) DEFAULT 0,
  mar_com double(8,2) DEFAULT 0,
  mar_from_otl tinyint(1) DEFAULT 0,
  apr_com double(8,2) DEFAULT 0,
  apr_from_otl tinyint(1) DEFAULT 0,
  may_com double(8,2) DEFAULT 0,
  may_from_otl tinyint(1) DEFAULT 0,
  jun_com double(8,2) DEFAULT 0,
  jun_from_otl tinyint(1) DEFAULT 0,
  jul_com double(8,2) DEFAULT 0,
  jul_from_otl tinyint(1) DEFAULT 0,
  aug_com double(8,2) DEFAULT 0,
  aug_from_otl tinyint(1) DEFAULT 0,
  sep_com double(8,2) DEFAULT 0,
  sep_from_otl tinyint(1) DEFAULT 0,
  oct_com double(8,2) DEFAULT 0,
  oct_from_otl tinyint(1) DEFAULT 0,
  nov_com double(8,2) DEFAULT 0,
  nov_from_otl tinyint(1) DEFAULT 0,
  dec_com double(8,2) DEFAULT 0,
  dec_from_otl tinyint(1) DEFAULT 0
);

ALTER TABLE '.$table_name_cols.' ADD UNIQUE( `year`,`project_id`, `user_id`);

INSERT INTO '.$table_name_cols.' (`year`,`project_id`,`user_id`) (SELECT `year`,`project_id`,`user_id` FROM `activities` group by `year`,`project_id`,`user_id`);

UPDATE '.$table_name_cols.' t, activities a SET t.jan_com=a.task_hour,t.jan_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 1;
UPDATE '.$table_name_cols.' t, activities a SET t.jan_com=a.task_hour,t.jan_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 1;

UPDATE '.$table_name_cols.' t, activities a SET t.feb_com=a.task_hour,t.feb_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 2;
UPDATE '.$table_name_cols.' t, activities a SET t.feb_com=a.task_hour,t.feb_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 2;

UPDATE '.$table_name_cols.' t, activities a SET t.mar_com=a.task_hour,t.mar_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 3;
UPDATE '.$table_name_cols.' t, activities a SET t.mar_com=a.task_hour,t.mar_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 3;

UPDATE '.$table_name_cols.' t, activities a SET t.apr_com=a.task_hour,t.apr_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 4;
UPDATE '.$table_name_cols.' t, activities a SET t.apr_com=a.task_hour,t.apr_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 4;

UPDATE '.$table_name_cols.' t, activities a SET t.may_com=a.task_hour,t.may_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 5;
UPDATE '.$table_name_cols.' t, activities a SET t.may_com=a.task_hour,t.may_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 5;

UPDATE '.$table_name_cols.' t, activities a SET t.jun_com=a.task_hour,t.jun_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 6;
UPDATE '.$table_name_cols.' t, activities a SET t.jun_com=a.task_hour,t.jun_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 6;

UPDATE '.$table_name_cols.' t, activities a SET t.jul_com=a.task_hour,t.jul_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 7;
UPDATE '.$table_name_cols.' t, activities a SET t.jul_com=a.task_hour,t.jul_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 7;

UPDATE '.$table_name_cols.' t, activities a SET t.aug_com=a.task_hour,t.aug_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 8;
UPDATE '.$table_name_cols.' t, activities a SET t.aug_com=a.task_hour,t.aug_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 8;

UPDATE '.$table_name_cols.' t, activities a SET t.sep_com=a.task_hour,t.sep_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 9;
UPDATE '.$table_name_cols.' t, activities a SET t.sep_com=a.task_hour,t.sep_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 9;

UPDATE '.$table_name_cols.' t, activities a SET t.oct_com=a.task_hour,t.oct_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 10;
UPDATE '.$table_name_cols.' t, activities a SET t.oct_com=a.task_hour,t.oct_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 10;

UPDATE '.$table_name_cols.' t, activities a SET t.nov_com=a.task_hour,t.nov_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 11;
UPDATE '.$table_name_cols.' t, activities a SET t.nov_com=a.task_hour,t.nov_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 11;

UPDATE '.$table_name_cols.' t, activities a SET t.dec_com=a.task_hour,t.dec_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=0 AND a.month = 12;
UPDATE '.$table_name_cols.' t, activities a SET t.dec_com=a.task_hour,t.dec_from_otl=a.from_otl WHERE t.year=a.year AND t.project_id=a.project_id AND t.user_id=a.user_id AND a.from_otl=1 AND a.month = 12;

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
