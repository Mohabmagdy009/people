<?php

namespace App\Imports;

use App\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CustomersImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       if ($row['name'] != null) {
           // code...
        Customer::updateorCreate(
            [
            'name'=>$row['name'],
            ],
            [
            'name'=>$row['name'],    
            'cluster_owner'=>$row['cluster'],
            'country_owner'=>$row['country']
            ] 
        ); 
       }
    }
}
