<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HolidayYear extends Model
{
    protected  $fillable = ['year', 'from_date', 'to_date'];
}
