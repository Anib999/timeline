<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = ['user_id','leave_applicable_id','leave_type_id','request_date','no_of_days','from_date','to_date','remarks','paid_unpaid_status','approve_by','status', 'leave_details', 'leave_time'];
    
    public function users() {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function leaveType() {
        return $this->belongsTo('App\LeaveType', 'leave_type_id');
    }
    public function leaveApplicable() {
        return $this->belongsTo('App\LeaveApplicable', 'leave_applicable_id');
    }
}
