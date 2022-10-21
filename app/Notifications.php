<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $fillable = [
        'user_id',
        'isAdminMessage',
        'messageType',
        'message',
        'createdBy',
        'deliveredOn',
        'readOn',
        'readBy',
        'status',
        'viewStatus'
    ];
}
