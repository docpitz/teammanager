<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'score',
        'max_participant',
        'meeting_place',
        'date_event_start',
        'date_event_end',
        'date_sign_up_start',
        'date_sign_up_end_',
        'date_publication',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_event_start' => 'datetime',
        'date_event_end' => 'datetime',
        'date_sign_up_start' => 'datetime',
        'date_sign_up_end_' => 'datetime',
        'date_publication' => 'datetime',
    ];

    public function users() {
        return $this->belongsToMany('App\User')
            ->withPivot('participation_status_id', 'date_user_changed_participation_status')
            ->withTimestamps();
    }
}
