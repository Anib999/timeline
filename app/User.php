<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Position;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'phone', 'contactPerson','isAdmin','username', 'date_of_join'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function isAdmin() {
        return (bool) $this->attributes['isAdmin']; // or however you determine whether user is admin
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function projects() {
        return $this->hasMany('App\Project');
    }

    public function subCategories() {
        return $this->hasMany('App\SubCategory');
    }

    public function jobs() {
        return $this->hasMany('App\Job');
    }

    public function documents() {
        return $this->hasMany('App\UserDoc');
    }

    public function dayWorkEntries() {

        return $this->belongsToMany('App\DayWorkEntry');
    }

    public function attendances() {
        return $this->hasMany(Attendence::class);
    }

    public function CheckInOutRequests() {
        return $this->hasMany(AdminToEmployeeRequestForCheckInOut::class, 'user_id');
    }
    
     public function leaveApplicable() {
        return $this->hasMany(LeaveApplicable::class, 'user_id');
    }
    
    
    
    

    /**
     * Get the positions a user has
     */
    public function positions() {

        return $this->belongsToMany('App\Position', 'users_positions','user_id','position_id');
    }
    
    
     /**
     * Get the roles a user has
     */
    public function roles() {

        return $this->belongsToMany('App\Role', 'users_roles','user_id','role_id');
    }
    
    
     /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles) {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                    abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
                abort(401, 'This action is unauthorized.');
        
    }

    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles) {
        
         if (is_array($roles)) {
             
             foreach($roles as $role){
                 if($this->hasRole( $role)){
                     return true;
                 }
             }
        }else{
            if($this->hasRole($roles)){
                     return true;
                 }
        }
        return false;
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role) {

        if($this->roles()->where('name', $role)->first()){
            return true;
        }else{
            return false;
        }
    }

}
