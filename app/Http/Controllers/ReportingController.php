<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendence;
use App\LeaveRequest;
use Barryvdh\DomPDF\Facade as PDF;
use App\Setting;
use App\DayWorkEntry;
use App\User;
use DB;
use Auth;

class ReportingController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function viewReporting(Request $request) {
        
        $request->user()->authorizeRoles(['ViewReport','SuperAdmin', 'CEOAdmin', 'Supervisor']);

        $setting = Setting::where('setting_name','check_in_without_admin_approval_setting')->pluck('status')->first();
        //$setting = Setting::where('id', 1)->pluck('check_in_setting_status')->first();
        return view('pages.reporting.viewReporting', compact('setting'));
    }

    public function employeeAttendance() {
        //$setting = Setting::where('id', 1)->pluck('check_in_setting_status')->first();
        $setting = Setting::where('setting_name','check_in_without_admin_approval_setting')->pluck('status')->first();
        $user_id = auth()->user()->id;
        // var_dump(auth()->user()->role_id);exit;
        if(Auth::user()->hasRole('Supervisor')) {
            $allUserDetails = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                                ->leftJoin('project_users', 'users.id', '=', 'project_users.user_id')
                                ->leftJoin('projects as P', 'project_users.project_id', '=', 'P.id')
                                ->leftJoin('projects', 'users.id', '=', 'projects.supervisor')
                                ->leftJoin('users_roles as UR1', 'UR1.user_id', '=', DB::raw($user_id))
                                ->whereRaw('IFNULL(`users_roles`.`role_id`, 9999) >= `UR1`.`role_id`')
                                ->get(['users.id', 'users.username'])->toArray();
                            }elseif(Auth::user()->hasRole('HRAdmin')) {
                                $allUserDetails = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                                                    ->leftJoin('roles as R', 'users_roles.role_id', '=', 'R.id')
                                                    ->leftJoin('users_roles as UR1', 'UR1.user_id', '=', DB::raw($user_id))
                                                    ->whereRaw('IFNULL(`R`.`id`, 9999) >= `UR1`.`role_id`')
                                                    ->orderBy('R.id', 'desc')
                                                    ->get(['users.id', 'users.username'])->toArray();
                            }else{
            $allUserDetails = User::get()->toArray();
        }
        $user_attendances = Attendence::where('user_id', $user_id)->get();

        return view('employeeAttendanceReport', compact('user_attendances', 'setting', 'allUserDetails'));
    }

    public function employeeLeave() {

        $user_id = auth()->user()->id;
        if(Auth::user()->hasRole('Supervisor')) {
            $allUserDetails = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                                ->leftJoin('project_users', 'users.id', '=', 'project_users.user_id')
                                ->leftJoin('projects as P', 'project_users.project_id', '=', 'P.id')
                                ->leftJoin('projects', 'users.id', '=', 'projects.supervisor')
                                ->leftJoin('users_roles as UR1', 'UR1.user_id', '=', DB::raw($user_id))
                                ->whereRaw('IFNULL(`users_roles`.`role_id`, 9999) >= `UR1`.`role_id`')
                                ->get(['users.id', 'users.username'])->toArray();
                            }elseif(Auth::user()->hasRole('HRAdmin')) {
                                $allUserDetails = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                                                    ->leftJoin('roles as R', 'users_roles.role_id', '=', 'R.id')
                                                    ->leftJoin('users_roles as UR1', 'UR1.user_id', '=', DB::raw($user_id))
                                                    ->whereRaw('IFNULL(`R`.`id`, 9999) >= `UR1`.`role_id`')
                                                    ->orderBy('R.id', 'desc')
                                                    ->get(['users.id', 'users.username'])->toArray();
                            }else{
            $allUserDetails = User::get()->toArray();
        }



        $user_leves = LeaveRequest::where('user_id', $user_id)->where('status', 1)->get();
        
        return view('employeeLeaveReport', compact('user_leves', 'allUserDetails'));
    }

    public function employeeDayWorkReport() {


        $user_id = auth()->user()->id;
        
        if(Auth::user()->hasRole('Supervisor')) {
            $allUserDetails = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                                ->leftJoin('project_users', 'users.id', '=', 'project_users.user_id')
                                ->leftJoin('projects as P', 'project_users.project_id', '=', 'P.id')
                                ->leftJoin('projects', 'users.id', '=', 'projects.supervisor')
                                ->leftJoin('users_roles as UR1', 'UR1.user_id', '=', DB::raw($user_id))
                                ->whereRaw('IFNULL(`users_roles`.`role_id`, 9999) >= `UR1`.`role_id`')
                                ->get(['users.id', 'users.username'])->toArray();
        }elseif(Auth::user()->hasRole('HRAdmin')) {
            $allUserDetails = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                                ->leftJoin('roles as R', 'users_roles.role_id', '=', 'R.id')
                                ->leftJoin('users_roles as UR1', 'UR1.user_id', '=', DB::raw($user_id))
                                ->whereRaw('IFNULL(`R`.`id`, 9999) >= `UR1`.`role_id`')
                                ->orderBy('R.id', 'desc')
                                ->get(['users.id', 'users.username'])->toArray();
        }else{
            $allUserDetails = User::get()->toArray();
        }

        $user_dayWorks = DayWorkEntry::where('user_id', $user_id)
                ->with('subCategories')
                ->with('projects')
                ->with('WorkDetails')
                ->get();
 
        return view('employeeDayWorkReport', compact('user_dayWorks', 'allUserDetails'));
    }

    public function downloadAttendancePDF($id) {

        $setting = Setting::where('setting_name','check_in_without_admin_approval_setting')->pluck('status')->first();
        //$setting = Setting::where('id', 1)->pluck('check_in_setting_status')->first();
        $user_attendances = Attendence::where('user_id', $id)->get();
        $pdf = PDF::loadView('pdf.attendancePDF', compact('user_attendances', 'setting'));
        return $pdf->download('attendance-report.pdf');
    }

    public function downloadLeavePDF($id) {


        $user_leaves = LeaveRequest::where('user_id', $id)->get();
        $pdf = PDF::loadView('pdf.leavePDF', compact('user_leaves'));
        return $pdf->download('leave-report.pdf');
    }

    public function ajaxAttendanceOfUser(Request $request) {
        // $user_attendances = Attendence::where('user_id', 10)->get();
        $fromDate = $request->get('datepicker_from');
        $toDate = $request->get('datepicker_to');
        $user_id = $request->get('user_id');

        $user_attendances = Attendence::whereBetween('day', [$fromDate, $toDate])
                        ->where('user_id', $user_id)->get(['day', 'check_in_time', 'check_out_time', 'total_work_hour', 'check_in_by', 'checkin_location'])->toArray();
        return response()->json($user_attendances);
    }

    public function ajaxLeaveOfUser(Request $request) {
        // $user_attendances = Attendence::where('user_id', 10)->get();
        $fromDate = $request->get('datepicker_from');
        $toDate = $request->get('datepicker_to');
        $user_id = $request->get('user_id');
        // var_dump($fromDate, $toDate, $user_id);

        $user_attendances = LeaveRequest::whereBetween('from_date', [$fromDate, $toDate])
                        ->where('user_id', $user_id)
                        ->get([
                            'request_date', 
                            'no_of_days', 
                            'from_date', 
                            'to_date', 
                            'aprove_by', 
                            'ap_remarks'
                        ])
                        ->toArray();
        return response()->json($user_attendances);
    }

    public function ajaxDayworkOfUser(Request $request) {
        // $user_attendances = Attendence::where('user_id', 10)->get();
        $fromDate = $request->get('datepicker_from');
        $toDate = $request->get('datepicker_to');
        $user_id = $request->get('user_id');
        // var_dump($fromDate, $toDate, $user_id);

        $user_attendances = DayWorkEntry::whereBetween('workEntryDate', [$fromDate, $toDate])
                        ->where('user_id', $user_id)
                        ->with('subCategories')
                        ->with('projects')
                        ->with('WorkDetails')
                        ->get()
                        ->toArray();
        return response()->json($user_attendances);
    }
}



//         select U.id, U.username, IFNULL(UR.role_id, 0) As role_id
// from users U 
// LEFT JOIN users_roles UR
// ON UR.user_id = U.id
// LEFT JOIN project_users PU
// ON PU.user_id = U.id
// LEFT JOIN projects P 
// ON P.id = PU.project_id
// LEFT JOIN projects P1 
// ON P1.supervisor = U.id

        // select U.id, U.username
        // from users U 
        // LEFT JOIN project_users PU
        // ON PU.user_id = U.id
        // LEFT JOIN projects P 
        // ON P.id = PU.project_id
        // LEFT JOIN projects P1 
        // ON P1.supervisor = U.id
        // WHERE IFNULL(P.name, P1.name) IS NOT NULL

//         select U.id, U.username, R.name
// from users U 
// LEFT JOIN users_roles UR
// ON UR.user_id = U.id
// LEFT JOIN roles R
// ON UR.role_id = R.id
// WHERE R.name IS NOT NULL