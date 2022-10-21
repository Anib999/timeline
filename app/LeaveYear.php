<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveYear extends Model
{
    protected $fillable = ['year','from_date','to_date','isActive'];
    
     public function leaveApplicable() {
        return $this->hasMany(LeaveApplicable::class,'year_id');
    }
    
}
