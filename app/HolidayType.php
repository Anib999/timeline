<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HolidayType extends Model
{
    protected  $fillable = ['type_name','description'];
}
