<?php

namespace App\Http\Controllers;

use App\ExtendProject;
use App\Project;
use App\ProjectUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;

class ProjectController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        // $this->middleware(['roles:SuperAdmin']);
        // $this->middleware(['roles:AddUpdateProject|Supervisor']);
    }

    public function index() {

        // $projects = Project::with('subCategories')->with('workDetails')->get();
        
        //if superadmin show all projects
        $isSuperAdmin = Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin');
        //if supervisior show only supervised projects
        $isSupervisor = Auth::user()->hasRole('Supervisor');

        //if someone has permission
        $hasProjectRole = Auth::user()->hasRole('AddUpdateProject');

        $currentUserId = Auth::user()->id;

        $projects = [];

        $isSuperAdminProject = $isSuperAdmin || $hasProjectRole || Auth::user()->hasRole('HRAdmin');

        $users = User::where('isSuperAdmin', false)->orderBy('name')->get();

        if($isSupervisor)
            $projects = Project::with('subCategories')->with('workDetails')->where('supervisor', $currentUserId)->get();
            // $projects = Project::with('subCategories')->with('workDetails')->get();
        if($isSuperAdminProject)
            $projects = Project::with('subCategories')->with('workDetails')->get();
        //if user show assigned projects

        return view('pages.projectList', ['projects' => $projects, 'isSuperAdmin' => $isSuperAdminProject, 'isSupervisor' => $isSupervisor, 'users' => $users]); 
    }

    public function create() {

        $projectStatus = ['running', 'pending', 'Completed'];
        $users = User::get();

        return view('pages/create-project', ['projectStatus' => $projectStatus, 'users' => $users]);
    }

    public function store(Request $request) {

        $request->validate([
            'name' => 'required',
            'projectClientName' => 'required',
            'projectLocation' => 'required',
            'fromDate' => 'required',
            'toDate' => 'required|after:fromDate',
            'supervisor' => 'required',
            'project_status' => 'required'
        ]);

        $user = User::find($request->get('user_id'));

        $user->projects()->create([
            'user_id' => Input::get('user_id'),
            'name' => Input::get('name'),
            'projectClientName' => Input::get('projectClientName'),
            'projectLocation' => Input::get('projectLocation'),
            'fromDate' => Input::get('fromDate'),
            'toDate' => Input::get('toDate'),
            'supervisor' => Input::get('supervisor'),
            'project_status' => Input::get('project_status'),
        ]);

        return redirect()->route('project.index')->with('message', 'Project Successfully Created !');
        ;
    }

    public function show($id) {

        $project = Project::find($id);
        return view('pages.singleProject', ['project' => $project]);
    }

    public function edit($id) {
        $users = User::get();
        $project = Project::find($id);
        $projectStatus = ['running', 'pending', 'Completed'];
        $projectExtensionDate = ExtendProject::where('project_id', $id)->orderBy('extendDate', 'DESC')->get();
        $lastToDate = '';
        $countDate = count($projectExtensionDate);
        if($countDate > 0)
            $lastToDate = $projectExtensionDate[$countDate - 1]->toDate;
        // var_dump($projectExtensionDate->toArray());exit;
        return view('pages.editProject', ['project' => $project, 'projectStatus' => $projectStatus, 'users' => $users, 'projectExtensionDate' => $projectExtensionDate, 'lastToDate' => $lastToDate]);
    }

    public function update(Request $request, $id) {


        $request->validate([
            'name' => 'required',
            'projectClientName' => 'required',
            'projectLocation' => 'required',
            'fromDate' => 'required',
            'toDate' => 'required|after:fromDate',
            'supervisor' => 'required',
            'project_status' => 'required'
        ]);


        $project = Project::find($id);
        $project->user_id = $request->get('user_id');
        $project->name = $request->get('name');
        $project->projectClientName = $request->get('projectClientName');
        $project->projectLocation = $request->get('projectLocation');
        $project->fromDate = $request->get('fromDate');
        $project->toDate = $request->get('extendDate') != $request->get('toDate') ? $request->get('extendDate') : $request->get('toDate');
        // $project->toDate = $request->get('toDate');
        $project->supervisor = $request->get('supervisor');
        $project->project_status = $request->get('project_status');
        $project->save();

        $isExtended = '';

        if($request->get('extendDate') != $request->get('toDate')){

            $extProject = ExtendProject::create([
                'user_id' => $request->get('user_id'),
                'project_id' => $id,
                'toDate' => $request->get('toDate'),
                'extendDate' => $request->get('extendDate')
            ]);
            
            if($extProject)
            $isExtended = 'Sucessfully extended';   
        }
        return redirect()->route('project.index')->with('message', 'Project Successfully Updated ! '.$isExtended);
    }

    public function addProjectUsers(Request $request) {
        $request->validate([
            'pid' => 'required',
            'userName' => 'required'
        ]);

        $unameList = $request->get('userName');
        $errors_ = array();

        foreach ($unameList as $value) {
            $data = array(
                'project_id' => $request->get('pid'),
                'user_id' => $value,
                'added_by' => Auth::user()->id
            );
            $success = ProjectUser::create($data);
            if (!$success) {
                array_push($errors_,[$value , 'error']);
            }
        }

        if(count($errors_) > 0){
            return redirect()->back()->with('message','Some user are not added. Please check and Try Again');
        }

        return redirect()->route('project.index')->with('message', 'User Successfully Updated ! ');
    }

    public function getProjectUserByProjectId(Request $request) {
        $projectId = $request->get('pid');
        $projectusers = ProjectUser::where('project_id', $projectId)->get()->toArray();        
        return response()->json($projectusers);
    }

    public function removeProjectUsers(Request $request) {
        $request->validate([
            'pid' => 'required',
            'userName' => 'required'
        ]);

        $unameList = $request->get('userName');
        $errors_ = array();

        foreach ($unameList as $value) {
            $success = ProjectUser::where('project_id', $request->get('pid'))->where('user_id', $value)->delete();
            if (!$success) {
                array_push($errors_,[$value , 'error']);
            }
        }

        if(count($errors_) > 0){
            return redirect()->back()->with('message','Some user are not added. Please check and Try Again');
        }

        return redirect()->route('project.index')->with('message', 'User Successfully Updated ! ');
    }

}
