<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //protected $fillable = ['check_in_setting_status'];
    
    protected $fillable = ['setting_name','status'];
}
