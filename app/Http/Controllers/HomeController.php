<?php

namespace App\Http\Controllers;

use Auth;
use App\Project;
use App\SubCategory;
use App\Job;
use App\AdminToEmployeeRequestForCheckInOut;
use Illuminate\Http\Request;
use App\Setting;
use App\LeaveRequest;

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
   

}
