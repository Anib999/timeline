<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

    protected $fillable = ['user_id', 'name', 'projectClientName', 'projectLocation', 'fromDate', 'toDate', 'supervisor', 'project_status'];

    public function user() {
        return $this->belongsTo('App\User','supervisor');
    }
    
    
//     public function projectSupervisor() {
//        return $this->belongsTo('App\Position','supervisor');
//    }

    public function documents() {
        return $this->hasMany('App\ProjectDocument');
    }

    public function subCategories() {

        return $this->hasMany('App\SubCategory');
    }

    public function jobs() {

        return $this->hasMany('App\Job');
    }
    
    public function workDetails() {

        return $this->hasMany('App\WorkDetails');
    }
    
    
    

}
