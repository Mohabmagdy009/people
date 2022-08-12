<?php

namespace App\Imports;

use App\Activity;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProjectsUserImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row['project_id'] != null) {
            for ($i=30; $i <53 ; $i++) { 
                Activity::UpdateOrCreate(
                    [
                        'project_id'=>$row['project_id'],
                        'user_id'=>$row['user_id'],
                        'task_hour'=>0,
                        'year'=>2022,
                        'month'=> $i
                    ],
                    [
                        'project_id'=>$row['project_id'],
                        'user_id'=>$row['user_id'],
                        'task_hour'=>0,
                        'year'=>2022,
                        'month'=> $i
                    ]
                );
            }            
       }
    }
}
