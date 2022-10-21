<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkDetails extends Model
{
    protected $fillable = ['user_id','project_id','sub_category_id','name','description','isUserAccessable'];
  
    public function project() {
        return $this->belongsTo('App\Project','project_id');
    }
    
    public function subcategory() {
        return $this->belongsTo('App\SubCategory','sub_category_id');
    }

    public function subcategory_(){
        return $this->hasOne('App\SubCategory','','sub_category_id');
    }
}
