<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLeave extends Model
{
    protected $fillable = ['user_id','leave_applicable_id','available_day'];
}
