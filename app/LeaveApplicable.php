<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveApplicable extends Model
{
    protected $fillable = ['user_id','leave_id','year_id','remaining_days','isActive'];
    
    
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function leaveYear() {
        return $this->belongsTo(LeaveYear::class,'year_id');
    }
    public function leaveType() {
        return $this->belongsTo(LeaveType::class,'leave_id');
    }
    
}
