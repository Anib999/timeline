<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{

    protected $fillable = [
        'user_id',
        'project_id',
        'added_by',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_id');
    }

    public function projects()
    {
        return $this->belongsTo('App\Project', 'project_id');
    }

}
