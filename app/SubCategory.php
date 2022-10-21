<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model {
    
    protected $table = 'sub_categories';

    protected $fillable = ['user_id','project_id','name','incharge','fromDate', 'toDate','description','subCategory_status'];
    
    public function project() {
        return $this->belongsTo('App\Project','project_id');
    }
    
    public function jobs() {
        
        return $this->hasMany('App\Job');
    }

    public function workDetails() {
        
        return $this->hasMany('App\WorkDetails');
    }

    public function user() {
        return $this->belongsTo('App\User','incharge');
    }
    


}
