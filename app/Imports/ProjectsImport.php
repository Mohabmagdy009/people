<?php

namespace App\Imports;

use App\Project;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProjectsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       if ($row['project_name'] != null) {
           // code...
        Project::updateorCreate(
            [
            'project_name'=>$row['project_name'],
            ],
            [
            'customer_id'=>$row['customer'],
            'project_name'=>$row['project_name'],    
            'project_type'=>$row['projecttype'],
            'region'=>$row['region'],
            'country'=>$row['country'],
            'opportunity_id'=>$row['opportunityid'],
            'solution_complexity'=>$row['solutioncomplexity']
            ] 
        ); 
       }
    }
}
