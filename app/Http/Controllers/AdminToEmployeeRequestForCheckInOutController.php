<?php

namespace App\Http\Controllers;

use App\AdminToEmployeeRequestForCheckInOut;
use Illuminate\Http\Request;
use Auth;
use App\Attendence;
use App\Notifications;
use App\LeaveRequest;
use App\LeaveApplicable;
//use Carbon\Carbon;

class AdminToEmployeeRequestForCheckInOutController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(Request $request)
    {
        $attendance = new Attendence;
        $adminRequestforCInOut = new AdminToEmployeeRequestForCheckInOut;

        $user_id = $request->get('user_id');
        $user_name = Auth::user()->name;
        $requestDay = $request->get('checkInOutday');
        $checkInOutRequest_id = $request->get('checkInOutRequest_id');

        $already_checkOut = $attendance->where('user_id', $user_id)
            ->where('day', $requestDay)->whereNotNull('check_out')->count();


        $already_checkIn = $attendance->where('user_id', $user_id)
            ->where('day', $requestDay)->count();

        $request_type = $request->get('request_type');

        switch ($request_type) {

            case 0:

                if ($already_checkIn == 0) {

                    $attendance->user_id = $user_id;
                    $attendance->present = 9;
                    $attendance->day = $requestDay;
                    $attendance->check_in = true;
                    $attendance->check_in_by = $user_name;
                    $attendance->check_in_time = $request->get('checkInTime');

                    //$attendance->save();
                    if (!$attendance->save()) {
                        return redirect()->back()->with('message', 'There was an error while Approve()');
                    } else {
                        /*$adminRequestforCInOut->where('user_id', $user_id)
                                ->where('day', $requestDay)
                                ->update([
                                    'aprove_by' => $user_name,
                                    'ap_comment' => $request->get('ap_comment'),
                                    'status' => 1
                        ]);*/
                        $adminRequestforCInOut->where('id', $checkInOutRequest_id)
                            ->update([
                                'aprove_by' => $user_name,
                                'ap_comment' => $request->get('ap_comment'),
                                'status' => 1
                            ]);

                        Notifications::create([
                            'user_id' => $user_id,
                            'isAdminMessage' => 0,
                            'messageType' => 1,
                            //'message' => $request->get('ap_comment'),
                            'message' => 'Your requested checkIn has been appproved<br>CheckIn: ' . $requestDay . ' ' . $request->get('checkInTime'),
                            'createdBy' =>  Auth::user()->id,
                            'status' => 1
                        ]);
                    }
                    
                    $this->giveLeaveOnCheckInCheckOut($request);

                    return redirect()->back()->with('message', 'You Approve Check In Request');
                    //                   return response()->json(['message' => 'You Approve Check In Request', 'date' => $requestDay]);
                } else {
                    return redirect()->back()->with('message', 'Already Checked In ');
                }

            case 1:

                if ($already_checkOut == 0) {

                    $update = $attendance->where('user_id', $user_id)
                        ->where('day', $requestDay)
                        ->whereNull('check_out')
                        ->update([
                            'check_out' => true,
                            'check_out_time' => $request->get('checkOutTime')
                        ]);

                    if (!$update) {
                        return redirect()->back()->with('message', 'There was an error ocuur , while Approve');
                    } else {
                        /*$adminRequestforCInOut->where('user_id', $user_id)
                                ->where('day', $requestDay)
                                ->WhereNotNull('check_out_request_time')
                                ->update([
                                    'aprove_by' => $user_name,
                                    'ap_comment' => $request->get('ap_comment'),
                                    'status' => 1
                        ]);*/
                        $adminRequestforCInOut->where('id', $checkInOutRequest_id)
                            ->update([
                                'aprove_by' => $user_name,
                                'ap_comment' => $request->get('ap_comment'),
                                'status' => 1
                            ]);
                        Notifications::create([
                            'user_id' => $user_id,
                            'isAdminMessage' => 0,
                            'messageType' => 2,
                            //'message' => $request->get('ap_comment'),
                            'message' => 'Your requested checkOut has been appproved<br> CheckOut: ' . $requestDay . ' ' . $request->get('checkOutTime'),
                            'createdBy' =>  Auth::user()->id,
                            'status' => 1
                        ]);
                    }
                    return redirect()->back()->with('message', 'You Approve Check Out Request');
                    //                   return response()->json(['message' => 'You Approve Check Out Request', 'date' => $requestDay]);
                } else {
                    return redirect()->back()->with('message', 'Already Checked Out ');
                }


            case 2:

                if ($already_checkOut == 0) {

                    $attendance->user_id = $user_id;
                    $attendance->present = 9;
                    $attendance->day = $requestDay;
                    $attendance->check_in = true;
                    $attendance->check_in_by = $user_name;
                    $attendance->check_out = true;
                    $attendance->check_in_time = $request->get('checkInTime');
                    $attendance->check_out_time = $request->get('checkOutTime');
                    $attendance->save();
                    if (!$attendance->save()) {
                        return redirect()->back()->with('message', 'There was an error ocuur , while Approve');
                    } else {
                        /*$adminRequestforCInOut->where('user_id', $user_id)
                                ->where('day', $requestDay)
                                ->WhereNotNull('check_out_request_time')
                                ->update([
                                    'aprove_by' => $user_name,
                                    'ap_comment' => $request->get('ap_comment'),
                                    'status' => 1
                        ]);*/
                        $adminRequestforCInOut->where('id', $checkInOutRequest_id)
                            ->update([
                                'aprove_by' => $user_name,
                                'ap_comment' => $request->get('ap_comment'),
                                'status' => 1
                            ]);
                        Notifications::create([
                            'user_id' => $user_id,
                            'isAdminMessage' => 0,
                            'messageType' => 3,
                            //'message' => $request->get('ap_comment'),
                            'message' => 'Your requested checkIn and checkOut has been appproved<br> CheckIn: ' . $requestDay . ' ' . $request->get('checkInTime') . '<br>CheckOut: ' . $request->get('checkOutTime'),
                            'createdBy' =>  Auth::user()->id,
                            'status' => 1
                        ]);
                    }
                    return redirect()->back()->with('message', 'You Approve CheckIn/Out Request');
                    //                   return response()->json(['message' => 'You Approve Check in/Out Request', 'date' => $requestDay]);
                } else {
                    return redirect()->back()->with('message', 'Already Checked In/Out ');
                }
        }
    }


    /**
     * Reject check in  check in/out request rejected
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdminToEmployeeRequestForCheckInOut  $adminToEmployeeRequestForCheckInOut
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {

        $adminRequestforCInOut = new AdminToEmployeeRequestForCheckInOut;

        $user_name = Auth::user()->name;
        $requestDay = $request->get('checkInOutday');
        $checkInOutRequest_id = $request->get('checkInOutRequest_id');
        //        $u_id = $request->get('user_id');

        /*$rejected = $adminRequestforCInOut->where('user_id', $user_id)
                ->where('day', $requestDay)
                ->update([
            'aprove_by' => $user_name,
            'ap_comment' => $request->get('ap_comment_reject'),
            'status' => 2
        ]);*/
        $rejected = $adminRequestforCInOut->where('id', $checkInOutRequest_id)
            ->update([
                'aprove_by' => $user_name,
                'ap_comment' => $request->get('ap_comment_reject'),
                'status' => 2
            ]);
        Notifications::create([
            'user_id' => $user_id,
            'isAdminMessage' => 0,
            'messageType' => 3,
            'message' => $request->get('ap_comment_reject'),
            'createdBy' =>  Auth::user()->id,
            'status' => 0
        ]);
        if (!$rejected) {
            return redirect()->back()->with('message', 'There was an error ocuur , while reject ! try again later');
        } else {
            return redirect()->back()->with('message', 'You Have Reject  Check In/out Request');
            //return response()->json(['message' => 'You Have Reject  Check In/out Request', 'date' => $requestDay]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdminToEmployeeRequestForCheckInOut  $adminToEmployeeRequestForCheckInOut
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $adminRequestforCInOut = new AdminToEmployeeRequestForCheckInOut;

        $makeReadMessage = $adminRequestforCInOut->find($id)
            ->update([
                'status' => 4
            ]);
        if ($makeReadMessage) {
            return response()->json(['id' => $adminRequestforCInOut->id]);
        }
    }

    /*
     * Make notification message  read
     */

    public function makeRead($id)
    {

        $adminRequestforCInOut = new AdminToEmployeeRequestForCheckInOut;


        $makeReadMessage = $adminRequestforCInOut->find($id)
            ->update([
                'status' => 4
            ]);
        if ($makeReadMessage) {
            return response()->json(['id' => $id]);
        }
    }

    public function giveLeaveOnCheckInCheckOut(Request $request)
    {
        $isLeaveGiven = $request->get('isLeaveGiven');
        if($isLeaveGiven != 0){
            $autoCheckInAttendance = $this->autoCheckInAttendance($request);
        }
    }

    public function autoCheckInAttendance(Request $request)
    {
        $leaveRequest = new LeaveRequest;
        $leaveApplicable = new LeaveApplicable;
        $user_id = $request->get('user_id');
        $user_name = Auth::user()->name;
        $inputAppTypes = $request->get('reasonLeave');
        if ($inputAppTypes != '') {

            $value = explode('|', $inputAppTypes);
            $leaveType_id = $value[0];
            $applicable_id = $value[1];
            $currentDays = $value[2];
            $leave_id = $value[3];
            $update = $leaveRequest->where('id', $leaveType_id)->get()->toArray();

            $new_remaining_days = $currentDays - 1;

            $newData = [
                'user_id' => $user_id,
                'leave_applicable_id' => $applicable_id,
                'leave_type_id' => $leaveType_id,
                'request_date' => $request->get('checkInOutday'),
                'no_of_days' => 1, // half half full now
                'from_date' => $request->get('checkInOutday'),
                'to_date' => $request->get('checkInOutday'),
                'remarks' => $request->get('ap_comment'),
                'paid_unpaid_status' => 1,
                'leave_details' => '',
                'leave_time' => '',
            ];

            $success = $leaveRequest->create($newData);
            
            if($success){
                $leaveTypeIdHere = $success->id;
                $update = $leaveRequest->where('id', $leaveTypeIdHere)
                ->update([
                    'aprove_by' => $user_name,
                    'ap_remarks' => $request->get('ap_comment'),
                    'status' => 1
                ]);
                if (!$update) {
                    return redirect()->back()->with('message', 'Could not request for leave!Please try again later');
                } else {
                    $updateSuccess = $leaveApplicable->where('user_id', $user_id)
                            ->where('leave_id', $leave_id)
                            ->where('isActive',1)
                            ->update([
                                'remaining_days' => $new_remaining_days
                            ]
                    );
                    if (!$updateSuccess) {
                        return redirect()->back()->with('message', 'There was some error occur! try again');
                    }
                }
            }
        }
    }
}
