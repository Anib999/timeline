<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtendSubCategory extends Model
{

    protected $fillable = [
        'user_id',
        'subcategory_id',
        'project_id',
        'toDate',
        'extendDate',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_id');
    }

    public function projects()
    {
        return $this->belongsTo('App\Project', 'project_id');
    }

    public function subcategories()
    {
        return $this->belongsTo('App\SubCategory', 'subcategory_id');
    }

}
