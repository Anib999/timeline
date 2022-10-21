<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Position;
use App\Role;
use Auth;
use Carbon\Carbon;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware(['roles:AddUpdateUser|SuperAdmin|CEOAdmin|HRAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $positions = Position::whereRaw('status=1')->with('users')->get();

        $roles = Role::where('id', '!=', 1)->where('name', '!=', 'CEOAdmin')->get();

        if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin')){
            $roles = Role::where('id', '!=', 1)->get();
        }

        $users = User::get();
        // $user = User::where('username', '=' ,'anib')->get();

        return view('pages.userList', compact('users', 'positions','roles'));
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
     * user Role Assign.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
//        $roles = $request->get('roles');
//        if (count($roles) >= 1) {
//            $user = User::where('id', $request->get('user_id'))->first();
//            $user->roles()->detach();
//
//
//            foreach ($roles as $role) {
//                $user->roles()->attach($role);
//            }
//        } else {
//            return response(['message', ' You must  select one role']);
//        }
//        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id) {
        $positions = Position::get();
        $roles = Role::where('id', '!=', 1)->get();
        $user = User::find($user_id);
        // var_dump($user);exit;
        return view('pages.userEdit', ['positions' => $positions, 'user' => $user, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::find($id);
        $joinDa = Carbon::parse(Input::get('dateOfJoin'), '+5:45')->format('Y-m-d');
        $now = Carbon::now('+5:45')->format('H:m:s');
//        var_dump($request->has('roles'));exit();
        $user->update([
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'password' => bcrypt(Input::get('password')),
            'address' => Input::get('address'),
            'phone' => Input::get('phone'),
            'contactPerson' => Input::get('contactPerson'),
            'isAdmin' => Input::has('isAdmin'),
            'date_of_join' => $joinDa.' '.$now
        ]);
        $user->positions()->detach();
        $user->positions()->attach(Input::get('position'));



        if (Input::has('isAdmin') == false) {
            //if (count($request->get('roles')) >= 1) {
                $user->roles()->detach();
            //}
        } elseif (Input::has('isAdmin') == true) {
            if ($request->has('roles')) {
                if (count($request->get('roles')) >= 1) {
                    $user->roles()->detach();
                    foreach ($request->get('roles') as $role) {
                        $user->roles()->attach($role);
                    }
                }
            }
        }

//        $user = User::where('id', $id)->first();



        return redirect()->route('user.index')->with('message', 'User Successfully Updated !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id) {
        $user = User::find($user_id);
        if (!$user) {
            return redirect()->back();
        }
        $user->delete();
        return redirect()->route('user.index')->with('message', 'User Successfully deleted !');
    }

    public function ajaxCheckUserNameAndReturn(Request $request) {
        $checkName = $request->get('uname');
        $u = User::where('username', 'like', $checkName.'%')->count();
        $autoU = $checkName;
        if($u > 0)
            $autoU .= $u;
        return response()->json($autoU);
    }

}
