<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveDays extends Model
{
    protected $fillable =['leave_type_id','no_of_days','isActive'];
    
     public function leaveType() {
         
        return $this->hasOne('App\LeaveType');
    }
}
