<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
  protected $fillable = ['user_id','project_id','sub_category_id','name','detail','description','incharge','assignedto','fromDate','toDate','hourTime','job_status'];
  
   public function project() {
        return $this->belongsTo('App\Project','project_id');
    }
    
     public function subcategory() {
        return $this->belongsTo('App\SubCategory','sub_category_id');
    }
     public function user_incharge() {
        return $this->belongsTo('App\User','incharge');
    }

    public function user_assignedto() {
        return $this->belongsTo('App\User','assignedto');
    }

    public function jobIncharge() {
        return $this->belongsTo('App\Position','incharge');
    }
    public function jobAssignedto() {
        return $this->belongsTo('App\Position','assignedto');
    }
   
}
