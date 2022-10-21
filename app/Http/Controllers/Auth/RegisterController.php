<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
        // $this->middleware(['roles:']);
        $this->middleware(['roles:SuperAdmin|CEOAdmin|HRAdmin|AddUpdateUser']);
    

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        // var_dump($data);exit;
        return Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'username' => 'required|string|max:255|unique:users',
                    //unique removed on email on user  |unique:users
                    'email' => 'required|email|max:255',
                    'password' => 'required|string|min:6|confirmed',
                    'address' => 'required|string|max:255',
                    'phone' => 'required|string|max:15',
                    'contactPerson' => 'required|string|max:255'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) {
        // var_dump($data);exit;
        $joinDa = Carbon::parse($data['dateOfJoin'], '+5:45')->format('Y-m-d');
        $now = Carbon::now('+5:45')->format('H:m:s');
//        dd(\array_has( $data, 'isAdmin'));exit();
        $user = User::create([
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'contactPerson' => $data['contactPerson'],
                    'isAdmin' => \array_has($data, 'isAdmin'),
                    'date_of_join' => $joinDa.' '.$now
        ]);

        $user->positions()->attach($data['position']);
        if (array_has($data, 'roles')) {
            $roles = $data['roles'];
            if (count($roles) >= 1) {

                foreach ($roles as $role) {
                    $user->roles()->attach($role);
                }
            }
        }

        return $user;
    }

}
