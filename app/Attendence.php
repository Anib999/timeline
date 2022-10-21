<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model {

    
    protected $fillable = ['user_id', 'present', 'day', 'check_in', 'check_in_by', 'check_out', 'check_in_time', 'check_out_time','total_work_hour', 'checkin_remarks', 'checkout_remarks', 'checkin_location', 'is_leave_auto'];

}
