<?php

namespace App\Http\Controllers;

use App\Attendence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use App\AdminToEmployeeRequestForCheckInOut;
use App\Setting;
use App\HolidayApplicable;
use App\LeaveRequest;
use App\DayWorkEntry;
use Illuminate\Support\Facades\DB;

class AttendenceController extends AttendanceTimeController {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function index(Request $request) {
        $timeData = json_encode($this->exportTime());
        //$setting = Setting::where('id', 1)->pluck('check_in_setting_status')->first();
        $setting = Setting::where('setting_name','check_in_without_admin_approval_setting')->pluck('status')->first();
//var_dump( Carbon::now('GMT+5:45') ); exit;

        //$request->user()->authorizeRoles('manager');

        $user_id = Auth::user()->id;
        $now = Carbon::now('GMT+5:45')->format('Y-m-d');
        $requestTypes = ['Check In', 'Check Out', 'Both'];

        $attendances = Attendence::where('user_id', $user_id)
                ->get();
//dd($attendances); exit();
        $already_checkIn = Attendence::where('user_id', $user_id)
                ->where('day', $now)
                ->count();
        
        $already_checkOut = Attendence::where('user_id', $user_id)
                ->where('day', $now)
                ->whereNull('check_out_time')
                ->count();

        /*$totalHour = DayWorkEntry::whereRaw('user_id = ' . $user_id)
            ->whereRaw('Date(created_at) = '.$now)
            ->select('workHour')
            ->sum('workHour');*/
        
        //dd( $totalHour ); exit();

        $holiday = new HolidayApplicable();
        $holidays = $holiday->join('holiday_types','holiday_types.id','=','holiday_applicables.holidayType')->get();

        $holidayDays = $holiday->pluck('holidayDay');

        $holidayDays_array = $holidayDays->toArray();

        $leaves = LeaveRequest::where('user_id', $user_id)->where('status', 1)->get();

        $userDetail = Auth::user();

        //dd($leaves); exit();
        return view('attendanceEmployee', compact('attendances', 'already_checkIn', 'already_checkOut', 'requestTypes', 'setting', 'holidays', 'leaves','holidayDays_array', 'userDetail', 'timeData'));
    }

    public function show() {
        
    }

    /**
     * create check in .
     * check in button comes here
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $check_in = ($request->has('check_in')) ? 1 : 0;
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $now = Carbon::now('GMT+5:45');
        $present = null;
        $attendance = new Attendence;

        $already_checkIn = $attendance->where('user_id', $user_id)
                        ->where('day', $now->format('Y-m-d'))->get();
        $check_checkIN_request = AdminToEmployeeRequestForCheckInOut::whereRaw("user_id =". $user_id ." AND day = '".$now->format('Y-m-d')."' AND check_in_request_time != 'NULL' ")->count();
        $check_in_day = $now->format('Y-m-d');
        $leaveRequest = new LeaveRequest;
        //$holidayApplicable = new HolidayApplicable;

        if($check_checkIN_request > 0){
            return response()->json(['message' => 'You Have Already Request Check In ']);
        }

        $leaves = $leaveRequest->get();
        //$flag = false;
        $leaveDates = array();

        if (is_array($leaves)) {
            foreach ($leaves as $leave) {
                $period = $this->generateDateRange(new carbon($leave->from_date), new carbon($leave->to_date));
                foreach ($period as $key => $value) {
                    $leaveDates[] = $value;
                }
            }
        }
        //$holidayApplicableDay = $holidayApplicable->where('holidayDay', $check_in_day)->count();


        if (in_array($check_in_day, $leaveDates)) {

            return response()->json(['message' => 'This day is your leave day']);
        } /*else if ($holidayApplicableDay > 0) {
            return response()->json(['message' => 'This day is your Holiday']);
        }*/ else if (count($already_checkIn) == 0 ) {

            if ($check_in == 1) {
                $present = 9;
            } else {
                $present = 2;
            }

            //compare date here
            $nYear = $now->year;
            $nMonth = $now->month;
            $nDay = $now->day ;
            //
            $sevenAm = Carbon::create($nYear, $nMonth, $nDay, 7, 0, 0, '+05:45');

            if($now->lessThan($sevenAm)){
                return response()->json(['message' => 'Cannot Check In before 7AM', 'date' => $now->format('Y-m-d')]);    
            }
            $employeeReason = str_replace(['"', "'"], "`", $request->get('allReasonField'));
            $defaultTodayTime = Carbon::now()
                                ->setTimezone('Asia/Kathmandu')
                                ->toTimeString();

            $isOverCheckInTime = $request->get('dl');
            if($isOverCheckInTime == 1 || $isOverCheckInTime == 2){
                $checkIfCheckedIn = $this->checkIfCheckIn($request);
                if($checkIfCheckedIn['isRequestCheckedIn'] == 0 && $checkIfCheckedIn['isCheckedIn'] == 0){
                    $checkInOutRequest = new AdminToEmployeeRequestForCheckInOut;

                    $saved = $checkInOutRequest->create([
                        'user_id' => $user_id,
                        'day' => $check_in_day,
                        'request_type' => 0,
                        'check_in_request_time' => $defaultTodayTime,
                        'em_comment' => $employeeReason
                    ]);
                    if ($saved) {
                        return response()->json(['message' => 'Your request successfully sent !']);
                    } else {
                        return response()->json(['message' => 'Your Request was not sent! try again.. ']);
                    }
                }else{
                    return response()->json(['message' => 'You Have Already Sent Check In Request']);
                }
            }else{

                $attendance->create([
                    'user_id' => $user_id,
                    'present' => $present,
                    'day' => $now->format('Y-m-d'),
                    'check_in' => $check_in,
                    'check_in_by' => $user_name,
                    'check_in_time' => $now->format('H:i:s'),
                    //'total_work_hour' => 1
                    //new added remarks
                    'checkin_remarks' => $employeeReason,
                    'checkin_location' => $request->get('gMap'),
                ]);
                   
                return response()->json(['message' => 'You Have Checked In', 'date' => $now->format('Y-m-d')]);
            }
        } else {
            return response()->json(['message' => 'You Have Already Checked In']);
        }
    }

    /**
     * create check out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendence  $attendence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id) {

        $now = Carbon::now('GMT+5:45');
        $check_out = ($request->has('check_out')) ? 1 : 0;

        $attendance = new Attendence;
        $already_checkOut = $attendance->where('user_id', $user_id)
                        ->where('day', $now->format('Y-m-d'))->whereNotNull('check_out')->get();

        $check_checkOUT_request = AdminToEmployeeRequestForCheckInOut::whereRaw("user_id =". $user_id ." AND day = '".$now->format('Y-m-d')."' AND check_out_request_time != 'NULL' ")->count();

        if($check_checkOUT_request > 0){
            return response()->json(['message' => 'You Have Already Request Check Out ']);
        }

        $check_in_day = $now->format('Y-m-d');
        $leaveRequest = new LeaveRequest;
        //$holidayApplicable = new HolidayApplicable;

        $leaves = $leaveRequest->get();
        //$flag = false;
        $leaveDates = array();

        if (is_array($leaves)) {
            foreach ($leaves as $leave) {
                $period = $this->generateDateRange(new carbon($leave->from_date), new carbon($leave->to_date));
                foreach ($period as $key => $value) {
                    $leaveDates[] = $value;
                }
            }
        }
        //$holidayApplicableDay = $holidayApplicable->where('holidayDay', $check_in_day)->count();


        if (in_array($check_in_day, $leaveDates)) {

            return response()->json(['message' => 'This day is your leave day']);
        } /*else if ($holidayApplicableDay > 0) {
            return response()->json(['message' => 'This day is your Holiday']);
        } */else if (count($already_checkOut) == 0) {
            $attendance->where('user_id', $user_id)
                    ->where('day', $now->format('Y-m-d'))
                    ->whereNull('check_out')
                    ->whereNull('check_out_time')
                    ->firstOrFail()
                    ->update([
                        'check_out' => $check_out,
                        'check_out_time' => $now->format('H:i:s'),
                        //new remarks added
                        'checkout_remarks' => $request->get('allReasonOutField')
            ]);
            return response()->json(['message' => 'You Have Checked Out']);
        } else {
            return response()->json(['message' => 'You Have Already Checked Out']);
        }
    }

    /*
     * send check in request
     */

    public function requestForCheckIn(Request $request) {

        $user_id = Auth::user()->id;

        $validatedata = Validator::make($request->all(), [
                    'day' => 'required',
                    'comment' => 'required|min:10|max:255',
                    'request_type' => 'required'
        ]);

        $now = Carbon::now('GMT+5:45');
        $request_day = $request->get('attendance_date');

        $checkInOutRequest = new AdminToEmployeeRequestForCheckInOut;
        $attendance = new Attendence;

        $already_send_request_check_in = $checkInOutRequest->where('user_id', $user_id)
                        ->where('day', $request_day)->count();

        $already_checkIn = $attendance->where('user_id', $user_id)
                        ->where('day', $request_day)->count();



        $already_send_request_check_out = $checkInOutRequest->where('user_id', $user_id)
                        ->where('day', $request_day)->whereNotNull('check_out_request_time')->count();


        $already_checkOut = $attendance->where('user_id', $user_id)
                        ->where('day', $request_day)->whereNotNull('check_out')->count();

        $request_type = $request->get('request_type');


        $leaveRequest = new LeaveRequest;
        //$holidayApplicable = new HolidayApplicable;
//        var_dump(time($request_day) <= time($now->format('Y-m-d')) );exit();

        $leaves = $leaveRequest->get();
        //$flag = false;
        $leaveDates = array();
        if (is_array($leaves)) {
            foreach ($leaves as $leave) {
                $period = $this->generateDateRange(new carbon($leave->from_date), new carbon($leave->to_date));
                foreach ($period as $key => $value) {
                    $leaveDates[] = $value;
                }
            }
        }
        //$holidayApplicableDay = $holidayApplicable->where('holidayDay', $request_day)->count();
        $defaultTodayTime = Carbon::now()
                                ->setTimezone('Asia/Kathmandu')
                                ->toTimeString();

        if (in_array($request_day, $leaveDates)) {

            return response()->json(['message' => 'This day is your leave day,So U can\'t send Request for attendance']);
        } /*else if ($holidayApplicableDay > 0) {
            return response()->json(['message' => 'This day is your Holiday,So U can\'t send Request for attendance']);
        }*/else if(time($request_day) <= time($now->format('Y-m-d'))) {


            switch ($request_type) {

                case 0:

                    if ($already_send_request_check_in == 0 && $already_checkIn == 0) {

                        if ($validatedata) {
                            $saved = $checkInOutRequest->create([
                                'user_id' => $user_id,
                                'day' => $request_day,
                                'request_type' => $request->get('request_type'),
                                'check_in_request_time' => $request->get('check_in_request_time') != '' && $request->get('check_in_request_time') != null ? $request->get('check_in_request_time') : $defaultTodayTime,
                                'em_comment' => $request->get('em_comment')
                            ]);
                        } else {
                            return response()->json(['message' => 'fill required all field  ']);
                        }

                        if ($saved) {
                            return response()->json(['message' => 'Your request successfully sent !']);
                        } else {
                            return response()->json(['message' => 'Your Request was not sent! try again.. ']);
                        }
                    } else if ($already_send_request_check_in > 0) {
                        return response()->json(['message' => 'Your selected date was alredy sent for check in request']);
                    } else if ($already_checkIn > 0) {
                        return response()->json(['message' => 'Your selected date was alredy Checked In']);
                    } else {
                        return response()->json(['message' => 'There is Some Error ! Please Try Again']);
                    }

                    break;



                case 1:

                    if ($already_send_request_check_out == 0 && $already_checkOut == 0 && ($already_send_request_check_in >= 1 || $already_checkIn >= 1)) {

                        if ($validatedata) {
                            $saved = $checkInOutRequest->create([
                                'user_id' => $user_id,
                                'day' => $request_day,
                                'request_type' => $request->get('request_type'),
                                'check_out_request_time' => $request->get('check_out_request_time'),
                                'em_comment' => $request->get('em_comment')
                            ]);
                        } else {
                            return response()->json(['message' => 'Fill all required  fields  ']);
                        }

                        if ($saved) {
                            return response()->json(['message' => 'Your request successfully sent !']);
                        } else {
                            return response()->json(['message' => 'Your Request was not sent! try again.. ']);
                        }
                    } else if ($already_send_request_check_out > 0) {
                        return response()->json(['message' => 'Your selected date was alredy sent for check out request']);
                    } else if ($already_checkOut > 0) {
                        return response()->json(['message' => ' Alredy Checked Out on the selected date']);
                    } else if (($already_send_request_check_in == 0 || $already_checkIn == 0)) {
                        return response()->json(['message' => 'Must Be checked in first']);
                    } else {
                        return response()->json(['message' => 'There is Some Error ! Please Try Again']);
                    }

                    break;

                case 2:

                    if ($already_send_request_check_in == 0 && $already_checkIn == 0) {

                        if ($validatedata) {
                            $saved = $checkInOutRequest->create([
                                'user_id' => $user_id,
                                'day' => $request_day,
                                'request_type' => $request->get('request_type'),
                                'check_in_request_time' => $request->get('check_in_request_time') != '' && $request->get('check_in_request_time') != null ? $request->get('check_in_request_time') : $defaultTodayTime,
                                'check_out_request_time' => $request->get('check_out_request_time'),
                                'em_comment' => $request->get('em_comment')
                            ]);
                        } else {
                            return response()->json(['message' => 'fill required all field  ']);
                        }

                        if ($saved) {
                            return response()->json(['message' => 'Your request successfully sent!']);
                        } else {
                            return response()->json(['message' => 'Your Request was not sent! try again.. ']);
                        }
                    } else if ($already_send_request_check_out > 0) {
                        return response()->json(['message' => 'Your selected date was alredy sent for check In/out request']);
                    } else if ($already_checkOut > 0) {
                        return response()->json(['message' => 'Alredy Check In/Out on the selected Date']);
                    } else {
                        return response()->json(['message' => 'There is Some Error ! Please Try Again']);
                    }

                    break;
            }
        } else {
            return response()->json(['message' => 'Date is greater  than Today Date']);
        }
    }

    /*
     * Directly   daily Attendance
     */

    public function checkIn(Request $request) {
        $check_in_day = $request->get('attendance_date');
        $check_in = $check_in_day ? 1 : 0;
        $user_id = Auth::user()->id;
        $now = Carbon::now('GMT+5:45');
        $present = null;
        $attendance = new Attendence;
        // var_dump('asdf');exit;

        $already_checkIn = $attendance->where('user_id', $user_id)
                        ->where('day', $check_in_day)->get();
        $leaveRequest = new LeaveRequest;
        //$holidayApplicable = new HolidayApplicable;

        $leaves = $leaveRequest->get();

        //$flag = false;
        $leaveDates = array();

        if (is_array($leaves)) {

            foreach ($leaves as $leave) {
                $periods = $this->generateDateRange(new carbon($leave->from_date), new carbon($leave->to_date));
                foreach ($periods as $key => $value) {
                    $leaveDates[] = $value;
                }
            }
        }
        //$holidayApplicableDay = $holidayApplicable->where('holidayDay', $check_in_day)->count();

        if (in_array($check_in_day, $leaveDates)) {

            return response()->json(['message' => 'This day is your leave day']);
        } /*else if ($holidayApplicableDay > 0) {

            return response()->json(['message' => 'This day is your Holiday']);
        } */else {
            if (count($already_checkIn) == 0) {

                if ($check_in == 1) {
                    $present = 9;
                } else {
                    $present = 2;
                }

                $isOverCheckInTime = $request->get('dl');
                $employeeReason = str_replace(['"', "'"], "`", $request->get('allReasonField'));
                $defaultTodayTime = Carbon::now()
                                ->setTimezone('Asia/Kathmandu')
                                ->toTimeString();

                if($isOverCheckInTime == 1 || $isOverCheckInTime == 2){
                    $checkIfCheckedIn = $this->checkIfCheckIn($request);
                    if($checkIfCheckedIn['isRequestCheckedIn'] == 0 && $checkIfCheckedIn['isCheckedIn'] == 0){
                        $checkInOutRequest = new AdminToEmployeeRequestForCheckInOut;

                        $saved = $checkInOutRequest->create([
                            'user_id' => $user_id,
                            'day' => $check_in_day,
                            'request_type' => 0,
                            'check_in_request_time' => $request->get('check_in_request_time') != '' && $request->get('check_in_request_time') != null ? $request->get('check_in_request_time') : $defaultTodayTime ,
                            'em_comment' => $employeeReason
                        ]);
                        if ($saved) {
                            return response()->json(['message' => 'Your request successfully sent !']);
                        } else {
                            return response()->json(['message' => 'Your Request was not sent! try again.. ']);
                        }
                    }else{
                        return response()->json(['message' => 'You Have Already Sent Check In Request']);
                    }
                }else{
                    $attendance->create([
                        'user_id' => $user_id,
                        'present' => $present,
                        'day' => $check_in_day,
                        'check_in_by' => Auth::user()->name,
                        'check_in' => $check_in,
                        'check_in_time' => $now,
                        //'total_work_hour' => 1
                        'checkin_remarks' => $employeeReason,
                        'checkin_location' => $request->get('gMap'),
                        'is_leave_auto' => $request->get('dl')
                    ]); 
                
                
                    return response()->json(['message' => 'You Have Checked In', 'date' => $check_in_day]);
                }
            } else {
                return response()->json(['message' => 'You Have Already Checked In']);
            }
        }
    }

    private function generateDateRange(Carbon $start_date, Carbon $end_date) {
        $dates = [];

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
    
    public function checkWhetherCheckOut(Request $request) {
        $user_id = Auth::user()->id;
        $selectedDate = $request->get('selDate');
        // var_dump($selectedDate);
        // , 'GMT+5:45'
        // $now = Carbon::now($selectedDate)->format('Y-m-d');
        $already_checkOut = Attendence::where('user_id', $user_id)
                ->where('day', $selectedDate)
                ->whereNull('check_out_time')
                ->count();
        echo $already_checkOut;
    }

    public function updateCheckOutWithoutAdmin(Request $request, $user_id) {

        $timeVar = Carbon::now('GMT+5:45');
        $now = new Carbon($request->get('selDate'), 'GMT+5:45');
        // $check_out = ($request->has('check_out')) ? 1 : 0;
        $check_out = 1;

        $attendance = new Attendence;
        $already_checkOut = $attendance->where('user_id', $user_id)
                            ->where('day', $now->format('Y-m-d'))->whereNotNull('check_out')->get();
        

        $check_checkOUT_request = AdminToEmployeeRequestForCheckInOut::whereRaw("user_id =". $user_id ." AND day = '".$now->format('Y-m-d')."' AND check_out_request_time != 'NULL' ")->count();

        // var_dump($user_id, $check_out, $already_checkOut, $check_checkOUT_request);

        if($check_checkOUT_request > 0){
            return response()->json(['message' => 'You Have Already Request Check Out ']);
        }

        $check_in_day = $now->format('Y-m-d');
        $leaveRequest = new LeaveRequest;
        //$holidayApplicable = new HolidayApplicable;

        $leaves = $leaveRequest->get();
        //$flag = false;
        $leaveDates = array();

        if (is_array($leaves)) {
            foreach ($leaves as $leave) {
                $period = $this->generateDateRange(new carbon($leave->from_date), new carbon($leave->to_date));
                foreach ($period as $key => $value) {
                    $leaveDates[] = $value;
                }
            }
        }
        //$holidayApplicableDay = $holidayApplicable->where('holidayDay', $check_in_day)->count();


        if (in_array($check_in_day, $leaveDates)) {

            return response()->json(['message' => 'This day is your leave day']);
        } /*else if ($holidayApplicableDay > 0) {
            return response()->json(['message' => 'This day is your Holiday']);
        } */else if (count($already_checkOut) == 0) {
            $attendance->where('user_id', $user_id)
                    ->where('day', $now->format('Y-m-d'))
                    ->whereNull('check_out')
                    ->whereNull('check_out_time')
                    ->firstOrFail()
                    ->update([
                        'check_out' => $check_out,
                        // 'check_out_time' => $now->format('H:i:s'),
                        'check_out_time' => $timeVar->format('H:i:s'),
                        //new remarks added
                        'checkout_remarks' => $request->get('remarks')
            ]);
            return response()->json(['message' => 'You Have Checked Out']);
        } else {
            return response()->json(['message' => 'You Have Already Checked Out']);
        }
    }

    public function checkIfCheckIn(Request $request) {
        $user_id = Auth::user()->id;

        $now = Carbon::now('GMT+5:45');
        $request_day = $request->get('attendance_date');

        $checkInOutRequest = new AdminToEmployeeRequestForCheckInOut;
        $attendance = new Attendence;

        $already_send_request_check_in = $checkInOutRequest->where('user_id', $user_id)
                        ->where('day', $request_day)->count();

        $already_checkIn = $attendance->where('user_id', $user_id)
                        ->where('day', $request_day)->count();

        return array('isRequestCheckedIn' => $already_send_request_check_in, 'isCheckedIn' => $already_checkIn);
    }

}
