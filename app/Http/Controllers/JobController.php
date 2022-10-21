<?php

namespace App\Http\Controllers;

use App\User;
use App\Job;
use App\Project;
use App\SubCategory;
use Illuminate\Http\Request;

class JobController extends Controller {
  public function __construct() {
        $this->middleware('auth');
        $this->middleware(['roles:SuperAdmin|CEOAdmin']);
    }

    public function index() {
        $jobs = Job::with('project')->with('subcategory')->get();
        return view('pages.jobList', compact('jobs'));
    }

    public function getCreate($pro, $cat) {

        $jobStatus = ['running', 'pending', 'Completed'];
        $project = Project::find($pro);
        $subcategory = SubCategory::find($cat);
        $users = User::get();

        return view('pages.jobCreate', compact('project', 'subcategory', 'jobStatus', 'users'));
    }

    public function store(Request $request) {

        $request->validate([
            'project_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required',
            'detail'=>'required',
            'fromDate' => 'required',
             'toDate' =>'required|after:fromDate',
             'hourTime'=>'required|int',
             'incharge'=>'required',
            'assignedto'=>'required',
             'description'=>'required',
            'job_status'=>'required'
        ]);
        
        Job::create([
            'user_id' => $request->get('user_id'),
            'project_id' => $request->get('project_id'),
            'sub_category_id' => $request->get('sub_category_id'),
            'name' => $request->get('name'),
            'detail' => $request->get('detail'),
            'fromDate' => $request->get('fromDate'),
            'toDate' => $request->get('toDate'),
            'hourTime' => $request->get('hourTime'),
            'incharge' => $request->get('incharge'),
            'assignedto' => $request->get('assignedto'),
            'description' => $request->get('description'),
            'job_status' => $request->get('job_status'),
        ]);
        return redirect()->route('project.index')->with('message', 'Job Successfully Created!');
    }

    public function show($id) {
        $job = Job::find($id);
        return view('pages.singleJob', compact('job'));
    }

    public function edit($id) {

        $users = User::get();
        $job = Job::find($id);
        
        $jobStatus = ['running', 'pending', 'Completed'];

        return view('pages.jobEdit', compact('job', 'jobStatus', 'users'));
    }

    public function update(Request $request, $id) {

        $job = Job::find($id);
        
        $request->validate([
            'project_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required',
            'detail'=>'required',
            'fromDate' => 'required',
             'toDate' =>'required|after:fromDate',
             'hourTime'=>'required|int',
             'incharge'=>'required',
            'assignedto'=>'required',
             'description'=>'required',
            'job_status'=>'required'
        ]);
        $job->user_id = $request->get('user_id');
        $job->project_id = $request->get('project_id');
        $job->sub_category_id = $request->get('sub_category_id');
        $job->name = $request->get('name');
        $job->detail = $request->get('detail');
        $job->fromDate = $request->get('fromDate');
        $job->toDate = $request->get('toDate');
        $job->hourTime = $request->get('hourTime');
        $job->incharge = $request->get('incharge');
        $job->assignedto = $request->get('assignedto');
        $job->description = $request->get('description');
        $job->job_status = $request->get('job_status');
        $job->save();


        return redirect()->route('project.index')->with('message', 'Job Successfully updated');
    }

//    public function projectAPI() {
//
//        $input = Input::get('option');
//
//        $project = Project::find($input);
//
//        $subcategory = $project->subCategories();
//
//        return \Response::make($subcategory->get(['id', 'name']));
//    }
    public function destroy(Job $job) {
        //
    }
}
