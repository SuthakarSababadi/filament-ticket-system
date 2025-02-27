<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'assigned_by',
        'comment',
        'is_resolved'

    ];

    const PRIORITY = [
        'Low' => 'Low',
        'Medium' => 'Medium',
        'High' => 'High',
    ];

    const STATUS = [
        'Open' => 'Open',
        'Closed' => 'Closed',
        'Archived' => 'Archived',
    ];

    public function assignedTo(){
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function assignedBy(){
        return $this->belongsTo(User::class,'assigned_by');
    }
}
