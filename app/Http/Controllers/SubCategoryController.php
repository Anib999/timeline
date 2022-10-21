<?php

namespace App\Http\Controllers;

use App\SubCategory;
use App\Project;
use DB;
use App\User;
use Illuminate\Http\Request;

class SubCategoryController extends Controller {
    
      public function __construct() {
        $this->middleware('auth');
        $this->middleware(['roles:SuperAdmin|CEOAdmin|HRAdmin']);
    }


//    public function index() {
//        $projects = Project::with('subCategories')->get();
//
//        return view('pages.subcategoryList', ['projects' => $projects]);
//    }
//    public function create() {
//       
//    }
//    
    public function show($id) {
        $subCategoryStatus = ['running', 'pending', 'Completed'];
        $project = Project::find($id);
        $users = User::get();
        return view('pages.subcategoryCreate', ['users' => $users, 'project' => $project, 'subCategoryStatus' => $subCategoryStatus]);
    }

    public function view($id) {
       
        $subCategoryStatus = ['running', 'pending', 'Completed'];
        $subcategory = SubCategory::find($id);

        return view('pages.singleSubcategory', [ 'subcategory' => $subcategory, 'subCategoryStatus' => $subCategoryStatus]);
    }

    public function store(Request $request) {
          $request->validate([
            'project_id' => 'required',
            'name' => 'required',
            'incharge' => 'required',
            'fromDate' => 'required',
             'toDate' =>'required|after:fromDate',
             'description'=>'required',
             'subCategory_status'=>'required'
        ]);
        SubCategory::create([
            'user_id' => $request->get('user_id'),
            'project_id' => $request->get('project_id'),
            'name' => $request->get('name'),
            'incharge' => $request->get('incharge'),
            'fromDate' => $request->get('fromDate'),
            'toDate' => $request->get('toDate'),
            'description' => $request->get('description'),
            'subCategory_status' => $request->get('subCategory_status'),
        ]);
        return redirect()->route('project.index')->with('message', 'Subcategory Successfully Created!');
    }

    public function edit($subcategory_id) {

        $subCategoryStatus = ['running', 'pending', 'Completed'];
        $users = User::get();
        $subcategory = SubCategory::find($subcategory_id);
        $projects = Project::all();

        return view('pages.subcategoryEdit', compact('subcategory', 'projects', 'subCategoryStatus', 'users'));
    }

    public function update(Request $request, $id) {
        

        $subcategory = SubCategory::find($id);
        
        $request->validate([
            'project_id' => 'required',
            'name' => 'required',
            'incharge' => 'required',
            'fromDate' => 'required',
             'toDate' =>'required|after:fromDate',
             'description'=>'required',
             'subCategory_status'=>'required'
        ]);

        $subcategory->user_id = $request->get('user_id');
        $subcategory->project_id = $request->get('project_id');
        $subcategory->name = $request->get('name');
        $subcategory->incharge = $request->get('incharge');
        $subcategory->fromDate = $request->get('fromDate');
        $subcategory->toDate = $request->get('toDate');
        $subcategory->description = $request->get('description');
        $subcategory->subCategory_status = $request->get('subCategory_status');
        $subcategory->save();

        return redirect()->route('project.index')->with('message', 'subcategory Successfully updated');
    }

//    public function destroy(SubCategory $subCategory) {
//        
//    }
}
