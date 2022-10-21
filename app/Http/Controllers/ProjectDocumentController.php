<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use DB;

class ProjectDocumentController extends Controller {
  public function __construct() {
        $this->middleware('auth');
        $this->middleware(['roles:SuperAdmin|CEOAdmin|HRAdmin|Supervisor']);
    }

   
     public function show($product) {
         $findproduct = Project::find($product);
        return view('pages.documentUpload',['findproduct'=>$findproduct]);
    }
    

    public function store(Request $request) {
              
        $file = $request->file('file');
   
        $fileName = uniqid() . $file->getClientOriginalName();
        $file->move('ProjectDocument', $fileName);
        $project = Project::find($request->get('project_id'));
        $project->documents()->create([
            'project_id' => $request->get('project_id'),
            'file_name' => $fileName,
            'file_size' => $file->getClientSize(),
            'file_mime' => $file->getClientMimeType(),
            'file_path' => 'ProjectDocument/' . $fileName,
            'file_title' => $request->get('file_title')
        ]);
        
       
        
    }

}
