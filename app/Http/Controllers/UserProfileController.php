<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Auth;
use App\User;
use Validator;
use Redirect;
use Hash;

class UserProfileController extends Controller
{
    
      public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index() {
        return view('profile', array('user' => Auth::user()));
        
    }
     public function store( Request $request) {
        if($request->hasFile('profile')){
    		$profile = $request->file('profile');
    		$filename = time().'.'.$profile->getClientOriginalExtension();
    		Image::make($profile)->resize(300, 300)->save( public_path('/photo/'.'/' . $filename ) );

    		$user = Auth::user();
    		$user->profile = $filename;
    		$user->save();
    	}

    	return view('profile', array('user' => Auth::user()) );
        
    }

    public function update(Request $request){
        Validator::make($request->all(),[
            'oldPassword' => 'required',
            'password' => 'required|min:6|confirmed'
        ])->validate();

        $user = Auth::user();
        //var_dump(Hash::check($request->oldPassword, $user->password ) ); exit();

        if( !Hash::check($request->oldPassword, $user->password ) )
            return Redirect::back()->withErrors(['oldPassword'=>'Your old password did not match']);
        
        if( $user->update(['password' => Hash::make($request->password)]) )
            return Redirect::back()->with(['success'=>'Your password has been changed.']);

        return Redirect::back()->withErrors(['UpdateError'=>'Could not update your password. Please try again later']); 
    }
  
}
