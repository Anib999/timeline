<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HolidayApplicable extends Model
{
    protected  $fillable = ['holidayYear','holidayType','holidayDay'];
    
       public function holidayyear() {
        return $this->belongsTo('App\HolidayYear','holidayYear');
    }
     public function holidaytype() {
        return $this->belongsTo('App\HolidayType','holidayType');
    }
}
