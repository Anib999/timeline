<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminToEmployeeRequestForCheckInOut extends Model {
    

    protected $fillable = ['user_id', 'day', 'em_comment', 'request_type','check_in_request_time','check_out_request_time', 'ap_comment','aprove_by', 'status'];
    
    public function user() {

        return $this->belongsTo('App\User', 'user_id');
    }

}
