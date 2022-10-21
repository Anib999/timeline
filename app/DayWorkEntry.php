<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayWorkEntry extends Model {

    protected $fillable = ['user_id',
                            'project_id',
                            //'project_name',
                            'subcat_id',
                            //'subcat_name',
                            'workDetail_id',
                            //'workDetail_name',
                            'workComment',
                            'workHour',
                            'workEntryDate',
                            'dayWorkEntryUser_id'
                        ];

    public function users() {
        return $this->belongsToMany('App\User','user_id');
    }
    
    public function projects() {
        return $this->belongsTo('App\Project','project_id');
    }
    
     public function subCategories() {
        return $this->belongsTo('App\SubCategory','subcat_id');
    }

    public function WorkDetails(){
        return $this->belongsTo('App\WorkDetails','workDetail_id');
    } 
    

}
