<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DayWorkEntry;
use Auth;
use App\Project;
use App\SubCategory;
use App\Job;
use App\WorkDetails;
use App\Attendence;
use App\FieldVisit;
use App\User;
use App\Setting;
use DB;

use Carbon\Carbon;

use Illuminate\Support\Facades\Input;


class DayWorkEntryController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function index() {

        /*$workDetails = DayWorkEntry::whereRaw('user_id = ' . Auth::user()->id)
                ->whereRaw('Date(created_at) = CURDATE()')
                ->with('subCategories')
                ->with('projects')
                ->with('WorkDetails')
                ->get();

        $totalHour = DayWorkEntry::whereRaw('user_id = ' . Auth::user()->id)
                ->whereRaw('Date(created_at) = CURDATE()')
                ->select('workHour')
                ->sum('workHour');*/
        $workDetails = array();
        $users = array();
        //dd( $users = User::get(['id','name','username']) );
        if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin')){
            $users = User::get(['id','name','username']);
        }
        $totalHour = 0;
        $user_id = Auth::user()->id;
        // $projects = Project::orderBy('created_at', 'asc')->get();
        $projects = Project::leftJoin('project_users AS PU', 'PU.project_id', '=', 'projects.id')
                            ->where('PU.user_id', '=', DB::raw($user_id))
                            ->orderBy('projects.created_at', 'asc')
                            ->get();
        
        return view('dayWorkHour', compact( 'workDetails', 'totalHour', 'now', 'projects','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        $user_id = Auth::user()->id;
        $request->validate([
            'workDayDetails' => 'required',
            'totalWorkHour' => 'required',
            ]);

        $workDayDetails = $request->get('workDayDetails');
        //var_dump( $workDayDetails[0][0]['user'] );exit;
        $totalWorkHour = $request->get('totalWorkHour');
        $workDayDate = $request->get('workDayDate');
        $totalRow = count( $workDayDetails );
        $already_exist_workDetail = array();
        $inserted_workDetail = array();

        $now = Carbon::now('GMT+5:45')->format('Y-m-d');
        //var_dump( $now==$workDayDate ); exit();
        if(!isset($workDayDetails[0][0]['user'])){
            $check_attendence = Attendence::whereRaw('user_id='.$user_id.' AND day="'.$workDayDate.'"')->get();
            //dd( count($check_attendence) ); exit();
            if( count($check_attendence) == 0 )
                return response()->json(['msg'=>'Please check in before submitting your day work on this day.']);
                $totalWorkHour_att = $check_attendence[0]->total_work_hour;
        }else{
            $totalWorkHour_att = 0;
            //$check_attendence = [['total_work_hour' => 0]];
        }
        for($i = 0; $i < $totalRow; $i++){
            $workDayDetail = $workDayDetails[$i][0];
            //var_dump( $workDayDetail );
            $workDetailTable_row = $workDayDetail['workDetail_row'];

            /*$already_exist = DayWorkEntry::where('user_id', $user_id)
                ->whereRaw('Date(created_at) = CURDATE()')
                ->where('project_id',$workDayDetail['project'])
                ->where( 'subcat_id' ,$workDayDetail['subCategory'] )
                ->where( 'workDetail_id' ,$workDayDetail['workDetail'] )
                ->count();*/
            //if ($already_exist <= 0) {
                // if(isset($workDayDetail['user'])){

                // }
                $dayWorkEntryStatus = DayWorkEntry::create([
                    'user_id' => (!isset($workDayDetail['user']))?$user_id:$workDayDetail['user'],
                    'project_id' => $workDayDetail['project'],
                    //'project_name' => $workDayDetail['project_name'],
                    'subcat_id' => $workDayDetail['subCategory'] ,
                    //'subcat_name' =>$workDayDetail['subCategory_name'] ,
                    'workDetail_id' => $workDayDetail['workDetail'] ,
                    //'workDetail_name' => $workDayDetail['workDetail_name'] ,
                    'workComment' => $workDayDetail['workComment'],
                    'workHour' => $workDayDetail['workHour'],
                    'workEntryDate' => $workDayDate,
                    'dayWorkEntryUser_id' => $user_id,
                ]);
                
                $totalWorkHour_att += $workDayDetail['workHour'];
                $inserted_workDetail[$workDetailTable_row] = $dayWorkEntryStatus->id;
                //var_dump( $dayWorkEntryStatus->id );
            /*}else{
                $already_exist_workDetail[] = $workDetailTable_row;
            }*/
        }
        if(isset( $check_attendence ) && $totalWorkHour_att > $check_attendence[0]->total_work_hour){
            Attendence::whereRaw('user_id='.$user_id.' AND day="'.$workDayDate.'"')
                    ->update([
                        'total_work_hour' => $totalWorkHour_att
                    ]);
        }
        //exit();
            
        return response()->json(['inserted'=>$inserted_workDetail,'alreadyExists'=>$already_exist_workDetail]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DayWorkEntry  $dayWorkEntry
     * @return \Illuminate\Http\Response
     */
    public function show(DayWorkEntry $dayWorkEntry) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DayWorkEntry  $dayWorkEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(DayWorkEntry $dayWorkEntry) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DayWorkEntry  $dayWorkEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DayWorkEntry $dayWorkEntry) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DayWorkEntry  $dayWorkEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(DayWorkEntry $dayWorkEntry) {
        //
    }

    public function projectAPI() {

        $input = Input::get('option');
        $project = Project::find($input);
        $subcategory = $project->subCategories();

        return \Response::make($subcategory->get(['id', 'name']));
    }

    public function subcatAPI() {

        $input = Input::get('option');
        $subcat = SubCategory::find($input);
        $job = $subcat->jobs();

        return \Response::make($job->get(['id', 'name']));
    }

    public function workDetailsAPI(Request $request){
        $project_id = $request->get('project');
        $subCat_id = $request->get('subCat');
        if(Auth::user()->hasRole('AddFieldVisit'))
            $workDetails = WorkDetails::whereRaw("project_id=$project_id AND sub_category_id=$subCat_id");
        else{
            $work_detail_user_accessable_setting = Setting::where('setting_name','work_detail_user_accessable_setting')->pluck('status')->first();
            $query_userAccessable = "";
            if($work_detail_user_accessable_setting)
                $query_userAccessable = "AND isUserAccessable=1";
            
            $workDetails = WorkDetails::whereRaw("project_id=$project_id AND sub_category_id=$subCat_id ".$query_userAccessable);
        }
        return \Response::make( $workDetails->get(['id','name']) );
    }

    public function deleteWorkEntryAPI(Request $request){
        $user_id = Auth::user()->id;
        $workDetailId = (int)$request->get('workDetailId');
        $workHour = (float)$request->get('workHour');
        $workDayDate = $request->get('workDayDate');
        $now = Carbon::now('GMT+5:45');


        $check_attendence = Attendence::whereRaw('user_id='.$user_id.' AND day="'.$workDayDate.'"')->get();

        $workDetailsDeleteStatus = DayWorkEntry::whereRaw("id=$workDetailId")->delete();
        if($workDetailsDeleteStatus == 1){
            Attendence::whereRaw('user_id='.$user_id.' AND day="'.$workDayDate.'"')
                ->update([
                    'total_work_hour' => $check_attendence[0]->total_work_hour - $workHour
                ]);
            return response()->json(['delete'=>true]);
        }else{
            return response()->json(['delete'=>false]);
        }
        //dd( DayWorkEntry::whereRaw("id=$workDetailId")->get() );
        //dd( $workDetailsDeleteStatus );
    }

    public function getWorkEntriesByDate(Request $request){
        $workDate = $request->get('workDate');
        
        $queryUserId = Auth::user()->id;
        if(Auth::user()->hasRole('AddFieldVisit')){
            if($request->get('userId') != null)
                $queryUserId = $request->get('userId');

        }
        $workEntries = DayWorkEntry::whereRaw('user_id = ' . $queryUserId.' AND workEntryDate = "'.$workDate.'"')
                ->with('subCategories')
                ->with('projects')
                ->with('WorkDetails')
                ->get();

        $table_row = array();
//dd($workEntries);

        foreach($workEntries as $workEntry){
            $table_row[] = array(
                'workEntryId' => $workEntry->id,
                'projectId' => $workEntry->projects->id,
                'projectName' => $workEntry->projects->name,
                'subCategoryId' => $workEntry->subCategories->id,
                'subCategoryName' => $workEntry->subCategories->name,
                'workDetailId' => $workEntry->WorkDetails->id,
                'workDetailName' => $workEntry->WorkDetails->name,
                'workHour' => $workEntry->workHour,
                'workComment' => $workEntry->workComment
            );
        }

        return response()->json( $table_row );
    }

    public function addFieldVisit() {
        $workDetails = array();
        $users = array();
        // if(Auth::user()->hasRole('AddFieldVisit')){
        //     $users = User::get(['id','name','username']);
        // }
        $totalHour = 0;
        $user_id = Auth::user()->id;
        // $projects = Project::orderBy('created_at', 'asc')->get();
        $projects = Project::leftJoin('project_users AS PU', 'PU.project_id', '=', 'projects.id')
                            ->where('PU.user_id', '=', DB::raw($user_id))
                            ->orderBy('projects.created_at', 'asc')
                            ->get();

        return view('fieldVisit', compact( 'workDetails', 'totalHour', 'now', 'projects','users'));
    }

    public function storeFieldVisit(Request $request) {
        // var_dump($request);exit;
        
        $user_id = Auth::user()->id;
        $request->validate([
            'workDayDetails' => 'required',
            'totalWorkHour' => 'required',
            ]);

        $workDayDetails = $request->get('workDayDetails');
        $totalWorkHour = $request->get('totalWorkHour');
        $workDayDate = $request->get('workDayDate');
        $totalRow = count( $workDayDetails );
        $already_exist_workDetail = array();
        $inserted_workDetail = array();
        $now = Carbon::now('GMT+5:45')->format('Y-m-d');
        if(!isset($workDayDetails[0][0]['user'])){
            $check_attendence = Attendence::whereRaw('user_id='.$user_id.' AND day="'.$workDayDate.'"')->get();
            if( count($check_attendence) == 0 )
                return response()->json(['msg'=>'Please check in before submitting your day work on this day.']);
                $totalWorkHour_att = $check_attendence[0]->total_work_hour;
        }else{
            $totalWorkHour_att = 0;
        }
        for($i = 0; $i < $totalRow; $i++){
            $workDayDetail = $workDayDetails[$i][0];
            $workDetailTable_row = $workDayDetail['workDetail_row'];

                $dayWorkEntryStatus = FieldVisit::create([
                    'user_id' => (!isset($workDayDetail['user']))?$user_id:$workDayDetail['user'],
                    'project_id' => $workDayDetail['project'],
                    //'project_name' => $workDayDetail['project_name'],
                    // 'subcat_id' => $workDayDetail['subCategory'] ,
                    //'subcat_name' =>$workDayDetail['subCategory_name'] ,
                    // 'workDetail_id' => $workDayDetail['workDetail'] ,
                    'workDetail_name' => $workDayDetail['workDetail'] ,
                    'workComment' => $workDayDetail['workComment'],
                    'workHour' => $workDayDetail['workHour'],
                    'workEntryDate' => $workDayDate,
                    'dayWorkEntryUser_id' => $user_id,
            ]);
                
                $totalWorkHour_att += $workDayDetail['workHour'];
                $inserted_workDetail[$workDetailTable_row] = $dayWorkEntryStatus->id;
        }
        if(isset( $check_attendence ) && $totalWorkHour_att > $check_attendence[0]->total_work_hour){
            Attendence::whereRaw('user_id='.$user_id.' AND day="'.$workDayDate.'"')
                    ->update([
                        'total_work_hour' => $totalWorkHour_att
                    ]);
        }
            
        return response()->json(['inserted'=>$inserted_workDetail,'alreadyExists'=>$already_exist_workDetail]);
        
    }

    public function getFieldVisitEntriesByDate(Request $request) {
        $workDate = $request->get('workDate');

        $queryUserId = Auth::user()->id;
        if (Auth::user()->hasRole('AddFieldVisit')) {
            if ($request->get('userId') != null)
                $queryUserId = $request->get('userId');
        }
        $workEntries = FieldVisit::whereRaw('user_id = ' . $queryUserId . ' AND workEntryDate = "' . $workDate . '"')
            // ->with('subCategories')
            ->with('projects')
            // ->with('WorkDetails')
            ->get();

        $table_row = array();

        foreach ($workEntries as $workEntry) {
            $table_row[] = array(
                'workEntryId' => $workEntry->id,
                'projectId' => $workEntry->projects->id,
                'projectName' => $workEntry->projects->name,
                // 'subCategoryId' => $workEntry->subCategories->id,
                // 'subCategoryName' => $workEntry->subCategories->name,
                // 'workDetailId' => $workEntry->WorkDetails->id,
                'workDetailName' => $workEntry->workDetail_name,
                'workHour' => $workEntry->workHour,
                'workComment' => $workEntry->workComment
            );
        }

        return response()->json($table_row);
    }

    public function ajaxAssignedUserProject(Request $request) {
        $projects = Project::leftJoin('project_users AS PU', 'PU.project_id', '=', 'projects.id')
                            ->where('PU.user_id', '=', DB::raw($request->get('uId')))
                            ->orderBy('projects.created_at', 'asc')
                            ->get();
        return response()->json($projects);
    }

}
