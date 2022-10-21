<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use App\SubCategory;
use App\Job;
use App\DayWorkEntry;

class AdminUserViewDetails extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware(['roles:ViewEmployee|SuperAdmin|CEOAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
//        $request->user()->authorizeRoles(['ViewEmployee', 'SuperAdmin']);
        //$request->user()->authorizeRoles('SuperAdmin');

        $users = User::orderBy('name', 'asc')->get();
        $projects = Project::get();
        return view('pages.adminUserView', compact('users', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = User::find($id);
        $dayWorks = DayWorkEntry::where('user_id', '=', $user->id)
                ->groupBy('created_at')
                ->orderBy('created_at', 'asc')
                ->get();
 
//        echo'<pre>';
//        print_r($dayWorks);exit();
        $projects = Project::where('supervisor', $id)->get();

        $subCategories = SubCategory::where('incharge', $id)->get();
        $jobs = Job::where(function($query) use ($id) {
                    $query->where('incharge', $id)
                            ->orWhere('assignedto', $id);
                })->get();

//        var_dump($jobs);        exit();


        return view('pages.adminsingleUserView', compact('user', 'projects', 'subCategories', 'jobs', 'dayWorks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
