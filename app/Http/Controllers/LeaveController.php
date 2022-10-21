<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveType;
use App\LeaveDays;
use App\LeaveYear;
use App\User;
use App\LeaveApplicable;
use App\LeaveRequest;
use App\UserLeave;
use Auth;
use App\Notifications;

class LeaveController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $totalLeaveDays = LeaveDays::select('no_of_days')
                ->sum('no_of_days');
        $leaveTypes = LeaveType::whereRaw('isActive=1')->with('leaveDays')->get();
        $leaveYears = LeaveYear::whereRaw('isActive=1')->get();
        $users = User::where('isSuperAdmin', false)->orderBy('name')->get();
        $leaveApplicables = LeaveApplicable::whereRaw('isActive=1')->with('user')->with('leaveYear')->with('leaveType')->get();

        return view('pages.leaveIndex', compact('leaveTypes', 'totalLeaveDays', 'leaveYears', 'users', 'leaveApplicables'));
    }

    /**
     * Leave Type create.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'type' => 'required',
            'description' => 'required',
        ]);
        
        $hasLeaveId = $request->get('leaveTypeId');

        if($hasLeaveId != null){

            $LeaveDays = new LeaveDays;
            $LeaveDays->where('leave_type_id',$hasLeaveId)
                        ->update([
                            'no_of_days' => $request->get('no_of_days')
                        ]);
            $leaveType = new LeaveType;
            $leaveType->where('id',$request->get('leaveTypeId'))
                        ->update([
                            'type' => $request->get('type'),
                            'description' => $request->get('description'),            
                        ]);
        }else{
            $leaveStore = LeaveType::create([
                'type' => $request->get('type'),
                'description' => $request->get('description'),
                'isActive' => true,
            ]);

            if (!$leaveStore) {
                return response(['message', 'There was some error , Try Again']);
            } else {
                LeaveDays::create([
                    'leave_type_id' => $leaveStore->id,
                    'no_of_days' => $request->get('no_of_days'),
                    'isActive' => true
                ]);
            }

        }
        return redirect()->back();   
    }

    /* Leave year Create */

    public function creteLeaveYear(Request $request) {

      $request->validate([
            'leaveYear' => 'required',
            'leaveFromDate' => 'required',
            'leaveToDate' => 'required|after:leaveFromDate'
        ]);

        $leaveYearId = $request->get('leaveYearId');
        if($leaveYearId != null){

            $leaveYear = new LeaveYear;

            // if($leaveYear->whereRaw('year = "'.$request->get('leaveYear').'"')->get()->count() > 0){
            //     return response()->json([0]);
            // }

            $leaveYear->where('id',$leaveYearId)
                        ->update([
                            'year' => $request->get('leaveYear'),
                            'from_date' => $request->get('leaveFromDate'),
                            'to_date' => $request->get('leaveToDate'),
                        ]);

        }else{
            //dd(LeaveYear::whereRaw('year = '.$request->get('leaveYear'))->get()->count() ); exit();
            if(LeaveYear::whereRaw('year = "'.$request->get('leaveYear').'"')->get()->count() > 0){
                return redirect()->back()->with('message','This leave year ('.$request->get('leaveYear').') is already created.');
            }

            $leaveYearCreate = LeaveYear::create([
                        'year' => $request->get('leaveYear'),
                        'from_date' => $request->get('leaveFromDate'),
                        'to_date' => $request->get('leaveToDate'),
                        'isActive' => true,
            ]);

            if (!$leaveYearCreate) {
                return redirect()->back()->with('message','There was some error , Try Again');

            }
        }

        return redirect()->back();
    }

    /* Leave appicable Create */

    public function makeLeaveApplicable(Request $request) {
        $request->validate([
            'userName' => 'required',
            'leaveYear' => 'required',
            'leaveType' => 'required'
        ]);
        
        $leavTypeDay = $request->get('leaveType');
        $leaveApplicable = new LeaveApplicable;

        $user_ids = $request->get('userName');
        $errors_ = array();
        $alreadyApplicables_ = array();
        foreach($user_ids as $user_id){
            foreach ($leavTypeDay as $allLeave) {
                $value = explode('|', $allLeave);
                $leaveType_id = $value[0];
                if ($value[1] !== 0)
                    $leaveday = $value[1];
                else
                    $leaveday = null;

                $already_applicable = $leaveApplicable->whereRaw("user_id = $user_id AND leave_id = $leaveType_id AND isActive = 1")->count();

                if ($already_applicable <= 0) {
                    $success = $leaveApplicable->create([
                        'user_id' => $user_id,
                        'leave_id' => $leaveType_id,
                        'year_id' => $request->get('leaveYear'),
                        'remaining_days' => $leaveday,
                        'isActive' => 1
                    ]);
        
                    if (!$success) {
                        array_push($errors_,[$user_id ,$request->get('userNameForResponse_'.$user_id), false]);
                    }
                }else {
                    array_push($alreadyApplicables_,[$user_id ,$request->get('userNameForResponse_'.$user_id), false]);
                }
            }

        }
        if(count($errors_) > 0){
            $userNameList = '';
            foreach($errors_ as $error_name){
                $userNameList .= ', '.$error_name[1];
            }
            return redirect()->back()->with('message','Some user may not be applicable.('.substr($userNameList,1).' ) Please check and Try Again');
        }

        if(count($alreadyApplicables_) > 0){
            $userNameList = '';
            foreach($alreadyApplicables_ as $already_applicable){
                $userNameList .= ', '.$already_applicable[1];
            }
            return redirect()->back()->with('message','Some users are already applicable.('.substr($userNameList,1).' )');
        }
        return redirect()->back()->with('message','Successfully made the user applicable for leave.');
    }

    public function makeLeaveApplicableOld(Request $request) {


        $request->validate([
            'userName' => 'required',
            'leaveYear' => 'required',
            'leaveType' => 'required'
        ]);

        //$user_id = Auth::user()->id;
        
        $leavTypeDay = $request->get('leaveType');
        //$value = array_map('intval', explode('|', $leavTypeDay));
        $value = explode('|', $leavTypeDay);
        
        /*var_dump($request->get('userName'));
        var_dump( $value );
        exit();*/

        $leaveType_id = $value[0];
        if ($value[1] !== 0) {
            $leaveday = $value[1];
        } else {
            $leaveday = null;
        }
        $leaveApplicable = new LeaveApplicable;

        $user_ids = $request->get('userName');
        $errors_ = array();
        $alreadyApplicables_ = array();
        foreach($user_ids as $user_id){
            //$already_applicable = $leaveApplicable->where('user_id',$user_id)->where('leave_id', '=', $leaveType_id)->count();
            $already_applicable = $leaveApplicable->whereRaw("user_id = $user_id AND leave_id = $leaveType_id AND isActive = 1")->count();

            if ($already_applicable <= 0) {
                $success = $leaveApplicable->create([
                    'user_id' => $user_id,
                    'leave_id' => $leaveType_id,
                    'year_id' => $request->get('leaveYear'),
                    'remaining_days' => $leaveday,
                    'isActive' => 1
                ]);
    
                if (!$success) {
                    array_push($errors_,[$user_id ,$request->get('userNameForResponse_'.$user_id), false]);
                }
            }else {
                array_push($alreadyApplicables_,[$user_id ,$request->get('userNameForResponse_'.$user_id), false]);
                //return response(['message', 'This leave type, is already Applicable']);
            }

        }
        //var_dump($errors_,$alreadyApplicables_);exit();
        if(count($errors_) > 0){
            $userNameList = '';
            foreach($errors_ as $error_name){
                $userNameList .= ', '.$error_name[1];
            }
            //return response(['message', 'Some user may not be applicable. Please check and Try Again']);
            return redirect()->back()->with('message','Some user may not be applicable.('.substr($userNameList,1).' ) Please check and Try Again');
        }

        if(count($alreadyApplicables_) > 0){
            //return response(['message', 'Some users are already applicable.']);
            $userNameList = '';
            foreach($alreadyApplicables_ as $already_applicable){
                $userNameList .= ', '.$already_applicable[1];
            }
            //substr($userNameList,1,-1);
            return redirect()->back()->with('message','Some users are already applicable.('.substr($userNameList,1).' )');
        }
        /*if ($already_applicable <= 0) {
            $success = $leaveApplicable->create([
                'user_id' => $request->get('userName'),
                'leave_id' => $leaveType_id,
                'year_id' => $request->get('leaveYear'),
                'remaining_days' => $leaveday
            ]);

            if (!$success) {
                return response(['message', 'There was some error , Try Again']);
            }
        }else {
            
            return response(['message', 'This leave type, is already Applicable']);
        }*/

        return redirect()->back()->with('message','Successfully made the user applicable for leave.');
    }

    /*
     *  Employee leave Request Approve/Reject
     */

    public function leaveRequestAction(Request $request) {
        $request->validate([
            'leaveRequest' => 'required',
            'remarks' => 'required|max:100'
        ]);

        $leaveRequest = new LeaveRequest;
        $leaveApplicable = new LeaveApplicable;
        $user_name = Auth::user()->name;
        $user_id = $request->get('user_id');
        //$leave_id = $request->get('leave_type_id');
        $remaining_days = $request->get('remaining_days');
        $request_days = $request->get('request_days');
        $new_remaining_days = $remaining_days - $request_days;

        $leave_id = $request->get('leave_id');

        $leaveRequestVal = $request->get('leaveRequest');
        // var_dump($leave_id, $leaveRequestVal, $request->get('leave_type_id'));exit;
        switch ($leaveRequestVal) {


            // Accept employee leave request
            case 1:

                /*$update = $leaveRequest->where('user_id', $user_id)
                        ->where('leave_type_id', $leave_id)
                        ->whereNull('aprove_by')
                        ->update([
                        'aprove_by' => $user_name,
                        'ap_remarks' => $request->get('remarks'),
                        'status' => 1
                    ]);*/
                $update = $leaveRequest->where('id', $request->get('leave_type_id'))
                    ->update([
                        'aprove_by' => $user_name,
                        'ap_remarks' => $request->get('remarks'),
                        'status' => 1
                    ]);
                Notifications::create([
                    'user_id' => $user_id,
                    'isAdminMessage' => 0,
                    'messageType' => 4,
                    'message' => $request->get('remarks'),
                    'createdBy' =>  Auth::user()->id,
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
                return redirect()->back()->with('message','Successfully performed leave action');


            // Reject employee leave request
            case 0:

                /*$update = $leaveRequest->where('user_id', $user_id)
                        ->where('leave_type_id', $leave_id)
                        ->whereNull('aprove_by')
                        ->update([
                    'aprove_by' => $user_name,
                    'ap_remarks' => $request->get('remarks'),
                    'status' => 2
                ]);*/
                
                // $update = $leaveRequest->where('id', $leave_id)
                $update = $leaveRequest->where('id', $request->get('leave_type_id'))
                ->update([
                    'aprove_by' => $user_name,
                    'ap_remarks' => $request->get('remarks'),
                    'status' => 2
                ]);

                Notifications::create([
                    'user_id' => $user_id,
                    'isAdminMessage' => 0,
                    'messageType' => 4,
                    'message' => $request->get('remarks'),
                    'createdBy' =>  Auth::user()->id,
                    'status' => 0
                ]);
                if (!$update) {
                    return response('message', 'There was some error occur! try again');
                }
                return redirect()->back();
        }
    }

    /**
     * edit leave type
     * ajax call()
     */
    public function editLeaveType(Request $request){
        //var_dump($request->get('updateLeave'));
        $updateData = $request->get('updateLeave');

        if( $updateData['key'] == 'no_of_days' ){
            $LeaveDays = new LeaveDays;
            $LeaveDays->where('leave_type_id',$request->get('leaveTypeId'))
                        ->update([
                            $updateData['key'] => $updateData['value']
                        ]);
            
            if($LeaveDays)
                return response()->json([1]);
            else
                return response()->json([0]);
        }else{
            $leaveType = new LeaveType;
            $leaveType->where('id',$request->get('leaveTypeId'))
                        ->update([
                            $updateData['key'] => $updateData['value']
                        ]);

            if($leaveType)
                return response()->json([1]);
            else
                return response()->json([0]);
        }

    }

    /**
     * edit leave year
     * ajax call()
     */
    public function editLeaveYear(Request $request){
        //var_dump($request->get('updateLeave'));
        $updateData = $request->get('updateLeaveYear');
        
        $leaveYear = new LeaveYear;

        if($updateData['key'] == 'year' && $leaveYear->whereRaw('year = "'.$updateData['value'].'"')->get()->count() > 0){
            return response()->json([0]);
        }

        $leaveYear->where('id',$request->get('leaveYearId'))
                    ->update([
                        $updateData['key'] => $updateData['value']
                    ]);

        if($leaveYear)
            return response()->json([1]);
        else
            return response()->json([0]);
        

    }

    public function inactive_leave_applicable(Request $request){
        $applicableUser = new LeaveApplicable;
        $leaveRequest = new LeaveRequest;

        $leaveApplicableId = $request->get('leaveApplicableId');

        if($leaveRequest->whereRaw('leave_applicable_id='.$leaveApplicableId)->count() >= 1){
            return response()->json([0,'Could not remove the leave applicable user (User already sent the leave request)']);
            //return redirect()->back()->with('message','Could not remove the leave applicable user (User already sent the leave request)');
        }else{
            $removeLeaveApplicable = $applicableUser->where('id',$leaveApplicableId)->update([
                'isActive' => 0
            ]);
            if($removeLeaveApplicable){
                return response()->json([1,'Successfully removed the applicable user for leave.']);
                //return redirect()->back()->with('message','Successfully removed the applicable user for leave.');
            }else{
                return response()->json([0,'Something went wrong. Please try again later !']);
                //return redirect()->back()->with('message','Something went wrong. Please try again later !');
            }
        }
    }

    public function inactive_leave_year(Request $request){
        $leaveYear = new LeaveYear;
        $applicableUser = new LeaveApplicable;

        $leaveYearId = $request->get('leaveYearId');

        //checks if @leave_years is present in @leave_applicables
        if($applicableUser->whereRaw('year_id = '.$leaveYearId.' AND isActive = 1 ')->count() >= 1){
            return response()->json([0,'Could not remove the leave year (Leave Year is used in Applicable Users)']);
            //return redirect()->back()->with('message', 'Could not remove the leave year (Leave Year is used in Applicable Users)');
        }else{
            //remove leave year
            $removeLeaveYear = $leaveYear->where('id',$leaveYearId)->update([
                'isActive' => 0
            ]);
            if($removeLeaveYear){
                return response()->json([1,'Successfully removed the leave year']);
                //return redirect()->back()->with('message','Successfully removed the leave year');
            }else{
                return response()->json([0,'Something went wrong please try again later !']);
                //return redirect()->back()->with('message','Something went wrong please try again later !.');
            }
        }
    }

    public function inactive_leave_types(Request $request){
        $leaveTypes = new LeaveType;
        //$leaveYear = new LeaveYear;
        $applicableUser = new LeaveApplicable;

        $leaveTypeId = $request->get('leaveTypeId');
        if($applicableUser->whereRaw('leave_id='.$leaveTypeId.' AND isActive = 1')->count() >= 1){
            return response()->json([0,'Could not remove the leave type (Leave is used in Applicable Users)']);
            //return redirect()->back()->with('message','Could not remove the leave type (Leave is used in Applicable Users)');
        }else{
            $removeLeaveType = $leaveTypes->where('id',$leaveTypeId)->update([
                'isActive' => 0
            ]);
            if($removeLeaveType){
                return response()->json([1,'Successfully removed the leave type']);
                //return redirect()->back()->with('message','Successfully removed the leave type');
            }else{
                return response()->json(0,'Something went wrong. Please try again later !');
                //return redirect()->back()->with('message','Something went wrong. Please try again later !');
            }
        }
    }
}
