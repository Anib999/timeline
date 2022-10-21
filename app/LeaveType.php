<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected  $fillable = ['type', 'description','isActive'];
    
    public function leaveDays() {
        
        return $this->hasOne('App\LeaveDays','leave_type_id');
    }
}
