<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

class AttendanceTimeController extends Controller {

    public function exportTime() {
        $fromEarly = '7, 0, 0, 0';
        $toEarly = '9, 45, 0, 0';

        $fromLate = '10, 1, 0, 0';
        $toLate = '10, 30, 59, 0';
        
        $fromFirstHalfLeave = '10, 31, 0, 0';
        $toFirstHalfLeave = '12, 0, 59, 0';

        $fromSecondHalfLeave = '12, 1, 0, 0';
        $toSecondHalfLeave = '17, 0, 59, 0';

        return compact(
            'fromEarly',
            'toEarly',
            'fromLate',
            'toLate',
            'fromFirstHalfLeave',
            'toFirstHalfLeave',
            'fromSecondHalfLeave',
            'toSecondHalfLeave'
        );
    }
}