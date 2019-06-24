<?php

namespace App;

use App\Buisness\Enum\ParticipationStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
        'participation_status_id',
        'date_event_start',
        'date_event_end',
        'date_sign_up_start',
        'date_sign_up_end',
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
        'date_sign_up_end' => 'datetime',
        'date_publication' => 'datetime',
    ];

    protected $dates = ['date_event_start', 'date_event_end', 'date_sign_up_start', 'date_sign_up_end', 'date_publication'];

    public function users() {
        return $this->belongsToMany('App\User')
            ->withPivot('participation_status_id', 'date_user_changed_participation_status')
            ->withTimestamps();
    }

    public function countPromise() {
        return $this->users()->wherePivot('participation_status_id', ParticipationStatusEnum::Promised)->count();
    }

    public function saveParticipation(User $user, int $participationStatus) {
        $this->users()->updateExistingPivot($user->id, ['participation_status_id' => $participationStatus, 'date_user_changed_participation_status' => Carbon::now()]);
    }

    public function getParticipationState(User $user) {
        return $this->users()->whereKey($user->id)->first()->pivot->participation_status_id;
    }

    public function isPromisedByUser(User $user) {
        return $this->hasStateByUser(ParticipationStatusEnum::Promised, $user);
    }

    public function isCanceledByUser(User $user) {
        return $this->hasStateByUser(ParticipationStatusEnum::Canceled, $user);
    }

    public function hasNoAnswerByUser(User $user) {
        return $this->hasStateByUser(ParticipationStatusEnum::NoAnswer, $user);
    }

    private function hasStateByUser(int $participationStatus, User $user) {
        return $this->getParticipationState($user) == $participationStatus;
    }
}
