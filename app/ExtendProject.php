<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtendProject extends Model
{

    protected $fillable = [
        'user_id',
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

}
