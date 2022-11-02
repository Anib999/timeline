<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Project;
use App\SubCategory;
use App\Job;
use App\AdminToEmployeeRequestForCheckInOut;
use Illuminate\Http\Request;
use App\Setting;
use App\LeaveRequest;
use App\LeaveYear;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
       $setting = Setting::where('setting_name','check_in_without_admin_approval_setting')->pluck('status')->first();
       $user_id = Auth::user()->id;

    //    @if(Auth::user()->id == 1)

        $projects = Project::where('supervisor', '=', $user_id)
                ->orderBy('created_at', 'asc')
                ->get();

        $subcategories = SubCategory::where('incharge', $user_id)
                ->orderBy('created_at', 'asc')
                ->get();
        $getListOfAttendenceRequest = new AdminToEmployeeRequestForCheckInOut;
        $employeeInOutRequests = $getListOfAttendenceRequest->whereRaw('day > "2018-12-31"')->orderBy('status','asc')->orderBy('created_at', 'asc')->get();

        $leaveRequests = LeaveRequest::with('leaveType')->orderBy('created_at', 'desc')->get();
        $count_ = 0;
        return view('home', compact('projects', 'subcategories', 'count_' ,'employeeInOutRequests','setting','leaveRequests'));
    }
    
    public function withCheckstore(Request $request) {
        
        $setting = new Setting;
        $has_checked = $request->has('check_in');
        
        if($has_checked == true){
            $check_val =  $request->get('check_in');
            $setting->where('setting_name','check_in_without_admin_approval_setting')->update(['status' =>$check_val]);
        }
        
        return redirect()->back();
        
    }

    public function checkLeaveSummary(Request $request) {
        // $leaveYear = new LeaveYear;
        $requestJSON = json_decode($request->get('requestJson'));
        // $leaveType = $requestJSON->leave_type;
        $users = $requestJSON->users;
        $leaveApplicable = $requestJSON->leave_applicable;
        $leaveYear = LeaveYear::where('id', $leaveApplicable->year_id)->get()->toArray()[0];
        $nowDate = Carbon::now('GMT+5:45');
        // ->format('Y-m-d')

        $dateOfJoin = Carbon::parse($users->date_of_join);
        $leaveFromDate = Carbon::parse($leaveYear['from_date']);
        $matPeriod = 0.5;

        $totaLeaveTaken = LeaveRequest::where('leave_applicable_id', $leaveApplicable->id)
                            ->where('status', 1)
                            ->whereBetween('request_date', [$leaveFromDate, $nowDate])
                            ->sum('no_of_days');

        if($dateOfJoin->gt($leaveFromDate)){
            $differenceInMonths = $nowDate->diffInMonths($dateOfJoin);
        }else{
            $differenceInMonths = $nowDate->diffInMonths($leaveFromDate);
        }

        // if ($dateOfJoin > $leaveFromDate){
        //     $differenceInMonths = $nowDate->diffInMonths(Carbon::parse($dateOfJoin));
        // }else{
        //     $differenceInMonths = $nowDate->diffInMonths(Carbon::parse($leaveFromDate));
        // }
        $matPat = $differenceInMonths * $matPeriod;
        return response()->json(array('mp' => $matPat, 'lt' =>$totaLeaveTaken));
    }
   

}
