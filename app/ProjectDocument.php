<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    protected  $fillable = ['project_id','file_name','file_size','file_mime','file_path', 'file_title'];
    
    public function project() {
        
        return $this->belongsTo('App\Project');
    }
    
    
}
