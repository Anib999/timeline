<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\LeaveType;
use App\LeaveApplicable;
use Auth;
use App\HolidayApplicable;
use App\LeaveRequest;

class UserLeaveController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * return leave page
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user_id = Auth::user()->id;
        $leaveCapables = LeaveApplicable::where('user_id', $user_id)->with('leaveType')->orderBy('created_at', 'asc')->get();
        $nowDate = Carbon::now('GMT+5:45')->format('Y / n / j');
        $leaveRequests = LeaveRequest::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
        
        return view('leaveEmployee', compact('nowDate', 'leaveCapables', 'leaveRequests'));
    }

    // user leve Request Send
    public function store(Request $request) {

        $request->validate([
            'leaveTypes' => 'required',
            'leave_from_date' => 'required',
            'leave_to_date' => 'required',
            'remarks' => 'required'
        ]);


        $leaveRequest = new LeaveRequest;
        $user_id = Auth::user()->id;
        $inputAppTypes = $request->get('leaveTypes');
        $value = explode('|', $inputAppTypes);
        $leaveType_id = $value[0];
        $applicable_id = $value[1];
        $currentDays = $value[2];
        $currentDate = Carbon::now('GMT+5:45')->format('Y-m-d');
        $from_date = $request->get('leave_from_date');
        $to_date = $request->get('leave_to_date');
        $fromDate = new Carbon($from_date);
        $toDate = new Carbon($to_date);
        $no_of_days_between_from_and_to_date = $toDate->diffInDays($fromDate) + 1;

        $paid_unpaid_status = $request->get('paid_unpaid_status');

        $remarks = str_replace(['"', "'"], "`", $request->get('remarks'));

        //new leave details added
        $leaveDetails = $request->get('leaveDetails');
        $leaveTime = $request->get('leaveTime');
        $totalLeaveDaysCut = 1;
        if($leaveTime == 'first' || $leaveTime == 'second')
            $totalLeaveDaysCut = 0.5;
        //new leave details added

        $periods = $this->generateDateRange(new carbon($from_date), new carbon($to_date));

        $holidayDays = HolidayApplicable::pluck('holidayDay');

        $holidayDays_array = $holidayDays->toArray();

        if (is_array($holidayDays_array) && is_array($periods)) {

            $find_holidays = array_intersect($holidayDays_array, $periods);

            if (is_array($find_holidays)) {
                
               $holiday_count = count($find_holidays);
            }
        }
        $no_of_days = $no_of_days_between_from_and_to_date == 1 ? $totalLeaveDaysCut : ($no_of_days_between_from_and_to_date - $holiday_count);

        $already_send_leave_request = $leaveRequest->where('user_id', $user_id)
                                        //->where('request_date', $periods)
                                        //->where('from_date','<=',$from_date)
                                        //->where('to_date','>=',$from_date)
                                        //->orWhere('from_date','<=',$to_date)
                                        //->orWhere('to_date','>=',$to_date)
                                        ->whereRaw("(from_date <= '$from_date' AND to_date >= '$from_date' OR from_date <= '$to_date' AND to_date >= '$to_date')")
                                        ->where('status', '!=', 2)
                                        //->get();
                                        ->count();

        if ($already_send_leave_request <= 0) {

            if ($no_of_days > 0) {

                if ($currentDays !== '' && $currentDays >= 0) {

                    $remainingDays = $currentDays - $no_of_days;

                    if ($remainingDays >= 0) {


                        $success = $leaveRequest->create([
                            'user_id' => $user_id,
                            'leave_applicable_id' => $applicable_id,
                            'leave_type_id' => $leaveType_id,
                            'request_date' => $currentDate,
                            'no_of_days' => $no_of_days,
                            // 'from_date' => $from_date,
                            // 'to_date' => $to_date,
                            'from_date' => $fromDate->format('Y-m-d'),
                            'to_date' => $toDate->format('Y-m-d'),
                            'remarks' => $remarks,
                            'paid_unpaid_status' => $paid_unpaid_status,
                            'leave_details' => $paid_unpaid_status == 0 ? $leaveDetails : '',
                            'leave_time' => $leaveTime ,
                            // $paid_unpaid_status == 0 ? : ''
                        ]);
                        if (!$success) {
                            return response()->json(['message' => 'There was some error occured, while sending request', 'stat' => 0]);
                        } else {
                            return response()->json(['message' => 'Your Leave Request Successfully submitted', 'stat' => 1]);
                        }
                    } else if (($remainingDays >= 0) == false) {

                        return response()->json(['message' => 'Exceeds no of allowable days ']);
                    }
                } else {
                    $success = $leaveRequest->create([
                        'user_id' => $user_id,
                        'leave_applicable_id' => $applicable_id,
                        'leave_type_id' => $leaveType_id,
                        'request_date' => $currentDate,
                        'no_of_days' => $no_of_days,
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                        'remarks' => $remarks,
                        'paid_unpaid_status' => $paid_unpaid_status,
                        'leave_details' => $paid_unpaid_status == 0 ? $leaveDetails : '',
                        'leave_time' => $leaveTime,
                        // $paid_unpaid_status == 0 ? : ''
                    ]);
                    if (!$success) {
                        return response()->json(['message' => 'There was some error while sending request', 'stat' => 0]);
                    } else {
                        return response()->json(['message' => 'Your Leave Request Successfully submitted', 'stat' => 1]);
                    }
                }
            } else {
                return response()->json(['message' => 'To Date is must greater than or equal to From Date']);
            }
        } else {
            return response()->json(['message' => 'Your Have Already Sent Leave Request']);
        }
    }

    public function show() {
        
    }

    public function destroy($leaveRequestId) {
        $leaveRequest = LeaveRequest::find($leaveRequestId);
        $success = $leaveRequest->delete();
        if (!$success) {
            return response()->json(['message' => 'There was some error']);
        } else {
            return response()->json(['message' => 'Successfully cancelled Leave Request', 'id' => $leaveRequestId]);
        }
    }

    private function generateDateRange(Carbon $start_date, Carbon $end_date) {
        $dates = [];

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    public function ajaxLeaveApplicableByUserId(Request $request){
        $usId = $request->get('usId');
        $leaveCapables = LeaveApplicable::where('user_id', $usId)->with('leaveType')->orderBy('created_at', 'asc')->get();
        return response()->json($leaveCapables);
    }
}
