<?php

namespace App\Imports;

use App\SubActivityTypes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class activitiesImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        SubActivityTypes::updateOrCreate(
            [
            'name'=>$row['name'],
            ],
            [
            'name'=>$row['name'],    
            'type'=>$row['type'],
            ] 
        ); 
    }
}
