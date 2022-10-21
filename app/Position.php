<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Position extends Model
{
    
     protected $fillable = ['name','details','rank','status'];
     
     public function users() {
        
        return $this->belongsToMany('App\User','users_positions','position_id', 'user_id');
        
    }
    
    public function project() {
        
        return $this->belongsTo('App\Project','name');
        
    }
    public function subCategory() {
        
        return $this->belongsTo('App\SubCategory','name');
        
    }
    public function job() {
        
        return $this->belongsTo('App\Job','name');
        
    }
}
