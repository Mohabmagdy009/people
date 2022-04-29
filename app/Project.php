<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    public $timestamps = true;
    protected $fillable = ['pullthru_samba_id', 'samba_id', 'samba_opportunit_owner', 'samba_lead_domain', 'samba_consulting_product_tcv', 'samba_pullthru_tcv', 'project_subtype', 'samba_stage', 'created_by_user_id', 'otl_validated', 'customer_id', 'project_name', 'project_type', 'meta_activity', 'region', 'country', 'customer_location', 'domain', 'description', 'estimated_end_date', 'comments', 'LoE_onshore', 'LoE_nearshore', 'LoE_offshore', 'LoE_contractor', 'gold_order_number', 'product_code', 'revenue', 'project_status', 'otl_project_code', 'win_ratio', 'created_at', 'updated_at','solution_complexity','opportunity_id'];

    public function activities()
    {
        return $this->hasMany(\App\Activity::class, 'project_id');
    }

    public function revenues()
    {
        return $this->hasMany(\App\ProjectRevenue::class, 'project_id');
    }

    public function loes()
    {
        return $this->hasMany(\App\Loe::class, 'project_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(\App\User::class, 'created_by_user_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Customer::class, 'customer_id');
    }
}
