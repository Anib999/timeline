<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\WorkDetails;
use App\User;
use App\Project;
use App\SubCategory;

use Auth;


class WorkDetailsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware(['roles:SuperAdmin|CEOAdmin']);
    }

    public function createWorkDetail_view($project_id,$subcategory_id){
        $project = Project::find($project_id);
        $subcategory = SubCategory::find($subcategory_id);
        $user_id = Auth::user()->id;

        return view('pages.workDetail', array(
            'subcategory_id'=>$subcategory_id,
            'user_id'=>$user_id,
            'project_id'=>$project_id));
    }

    public function createWorkDetail(Request $request){

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'project_id' => 'required',
            'subcategory_id' => 'required'
        ]);
//var_dump( $request->get('isUserAccessable') ); exit();
        $user_id = Auth::user()->id;
        $workDetail_name = $request->get('name');
        $description = $request->get('description');
        $subcategory_id = $request->get('subcategory_id');
        $project_id = $request->get('project_id');

        //$work_detail_user_accessable_setting = Setting::where('setting_name','work_detail_user_accessable_setting')->pluck('status')->first();
        
        $checkWorkDetail = WorkDetails::whereRaw('project_id='.$project_id.' AND sub_category_id='.$subcategory_id)->get()->count();
        if($checkWorkDetail == 0){
            WorkDetails::create([
                'name' => 'Field Visit',
                'description' => 'Field Visit Details',
                'project_id' => $project_id,
                'sub_category_id' => $subcategory_id,
                'user_id' => $user_id,
                'isUserAccessable' => false
            ]);
        }
        $addWorkDetailStatus = WorkDetails::create([
            'name' => $workDetail_name,
            'description' => $description,
            'project_id' => $project_id,
            'sub_category_id' => $subcategory_id,
            'user_id' => $user_id,
            'isUserAccessable' => ($request->get('isUserAccessable') == null)?0:1
        ]);

        if($addWorkDetailStatus->id){
            return redirect()->route('project.index')->with('message', 'Successfully created Work Detail');
        }else{
            return redirect()->route('project.index')->with('message', 'Could not create Work Detail. Please try again later.');
        }

    }

    public function editWorkDetail(Request $request){
        $request->validate([
            'workDetail_name' => 'required',
            'workDetail_description' => 'required',
            'workDetail_ID' => 'required'
        ]);

        $workDetail_name = $request->get('workDetail_name');
        $workDetail_description = $request->get('workDetail_description');
        $workDetail_ID = $request->get('workDetail_ID');

        $editState = WorkDetails::whereRaw('id='.$workDetail_ID)
                    ->update([
                        'name' => $workDetail_name,
                        'description' => $workDetail_description,
                        'isUserAccessable' => $request->get('userAccessable')
                    ]);
        
        if($editState == 1){
            return response()->json(['edit'=>true]);
        }else{
            return response()->json(['edit'=>false]);
        }
    }
}
