<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventSendedmail extends Model
{
    protected $table = "event_sendedmails";
    protected $fillable = ['kind'];

    protected $touches = ['event'];

    public $timestamps = false;

    /**
     * Get the post that the comment belongs to.
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
