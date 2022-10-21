<?php

namespace App\Http\Controllers;

use App\User;
use App\UserDoc;
use Illuminate\Http\Request;
use Input;

class UserDocController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware(['roles:SuperAdmin|CEOAdmin']);
    }

    public function index() {
        
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
     * Display the specified resource.
     *
     * @param  \App\UserDoc  $userDoc
     * @return \Illuminate\Http\Response
     */
    public function show($user_id) {
        $user = User::find($user_id);
        return view('pages.userDocument', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
//        $file = $request->file('userDoc');
////        var_dump($file);exit();
//        $user_id = $request->get('user_id');
//         //var_dump($user_id );exit();
//        $fileName = uniqid().$file->getClientOriginalName();
//        $file->move('userDocument', $fileName);
//        $user = User::find($user_id);
//       
//        $user->documents()->create([
//            'user_id' => $user_id,
//            'file_name' => $fileName,
//            'file_size' => $file->getClientSize(),
//            'file_mime' => $file->getClientMimeType(),
//            'file_path' => 'userDocument/' . $fileName,
//        ]);
    }

    public function upload(Request $request) {
        
        $file = $request->file('file');

        $user_id = $request->get('user_id');

        $fileName = uniqid() . $file->getClientOriginalName();
        
        $file->move('userDocument', $fileName);
        $user = User::find($user_id);

        $user->documents()->create([
            'user_id' => $user_id,
            'file_name' => $fileName,
            'file_size' => $file->getClientSize(),
            'file_mime' => $file->getClientMimeType(),
            'file_path' => 'userDocument/' . $fileName,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserDoc  $userDoc
     * @return \Illuminate\Http\Response
     */
    public function edit(UserDoc $userDoc) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserDoc  $userDoc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserDoc $userDoc) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserDoc  $userDoc
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDoc $userDoc) {
        //
    }

}
