<?php
declare(strict_types=1);

namespace App;

use Altek\Accountant\Contracts\Recordable;
use Altek\Accountant\Models\Ledger;
use Altek\Eventually\Eventually;
use App\Buisness\Enum\ParticipationStatusEnum;
use App\Helper\UserCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Event extends Model implements Recordable
{
    use \Altek\Accountant\Recordable;
    use Eventually;

    /**
     * Recordable events.
     *
     * @var array
     */
    protected $recordableEvents = [
        'existingPivotUpdated',
        'attached',
        'detached'
    ];

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
        return $this->belongsToMany('App\User', 'event_user')
            ->withPivot('participation_status_id', 'date_user_changed_participation_status', 'changed_by_user_id')
            ->withTimestamps();
    }

    public function responsibles() {
        return $this->belongsToMany('App\User', 'event_responsible');
    }

    public function sendedmails() {
        return $this->hasMany('App\EventSendedmail');
    }

    public function getUsersParticipation() {
        return $this->users()
            ->addSelect('users.*', 'event_user.*', 'changed_by_user.firstname as changed_by_user_firstname', 'changed_by_user.surname as changed_by_user_surname')
            ->withPivot('changed_by_user_id')
            ->leftJoin('users as changed_by_user', 'event_user.changed_by_user_id', '=', 'changed_by_user.id');
    }

    public function getUsersParticipationByUserIds(array $userIds) {
        return $this->getUsersParticipation()
            ->whereIn("users.id", $userIds);
    }

    public function getUsersByParticipation(int $participationStatus) {
        return $this->getUsersParticipation()
            ->wherePivot('participation_status_id', '=', $participationStatus)
            ->orderBy('event_user.date_user_changed_participation_status', 'asc');
    }

    public function countPromise() {
        return $this->users()->wherePivot('participation_status_id','=', ParticipationStatusEnum::Promised)->count();
    }

    public function countWaitlist() {
        return $this->waitlistOrderbyHighestWish()->count();
    }

    public function waitlistOrderbyHighestWish() {
        return $this->users()
            ->wherePivot('participation_status_id','=', ParticipationStatusEnum::Waitlist)
            ->orderBy('event_user.date_user_changed_participation_status', 'asc');
    }

    public function getParticipationHistory(): Array
    {
        $ledgers = $this->ledgers()->whereIn('event', [
            'existingPivotUpdated',
            'attached',
            'detached'
        ])->latest()->get();


        $userCache = new UserCache();
        $history = $ledgers->map(function (Ledger $ledger) use($userCache) {
            $pivotData = $ledger->getPivotData();
            if($pivotData["relation"] == "users") {
                $properties = $pivotData["properties"];
                $metadata = $ledger->getMetadata();
                $collection = collect($properties);
                $properties = $collection->map(function (Array $property) use($metadata, $userCache) {
                    if($metadata['ledger_event'] == "detached")
                    {
                        Arr::set($property, 'participation_status_name', 'Removed');
                        Arr::set($property, 'date_user_changed_participation_status', $metadata['ledger_updated_at']);
                        Arr::set($property, 'changed_by_user_id', $metadata['user_id']);
                    }
                    else
                    {
                        if(Arr::has($property, 'participation_status_id'))
                        {
                            Arr::set($property, 'participation_status_name', ParticipationStatusEnum::getInstance((int)$property['participation_status_id'])->description);
                        }
                    }
                    if(!empty($property['date_user_changed_participation_status'])) {
                        $dateUserChangedParticipationStatus = $property['date_user_changed_participation_status'];
                        $carbon = new Carbon($dateUserChangedParticipationStatus);
                        $formatedTime = $carbon->timezone(config('app.timezone'))->format('d.m.Y H:i:s');
                        Arr::set($property, 'changed_date_formatted', $formatedTime);
                    }

                    if(!empty($property['changed_by_user_id']))
                    {
                        Arr::set($property, 'user', $userCache->getUserById($property['changed_by_user_id']));
                    }
                    return $property;
                });
                return $properties->toArray();
            }
        });
        return collect($history)->collapse()->groupBy('user_id')->all();
    }

    public function saveParticipation(User $user, int $participationStatus, User $changedByUser, bool $force = false) {
        $success = false;
        if(!$force) {
            $participationStatus = $this->calculatePossibleParticipationStatus($participationStatus, $user);
        }
        if($this->getParticipationState($user) != $participationStatus || $force)
        {
            $this->users()->updateExistingPivot($user->id, ['participation_status_id' => $participationStatus, 'date_user_changed_participation_status' => Carbon::now(), 'changed_by_user_id' => $changedByUser->id]);
            $success = true;
        }
        return $success;
    }

    public function getHideParticipationState(User $user) {
        $activeStatus = $this->getParticipationState($user);
        if($activeStatus == ParticipationStatusEnum::Canceled || $activeStatus == ParticipationStatusEnum::Quiet) {
            if($this->isPromisedPossible()) {
                return ParticipationStatusEnum::Waitlist;
            }
            else {
                return ParticipationStatusEnum::Promised;
            }
        }
        else if($activeStatus == ParticipationStatusEnum::Promised) {
            return ParticipationStatusEnum::Waitlist;
        }
        else if($activeStatus == ParticipationStatusEnum::Waitlist) {
            return ParticipationStatusEnum::Promised;
        }
    }

    public function getParticipationState(User $user) {
        return $this->users()->whereKey($user->id)->first()->pivot->participation_status_id;
    }

    public function isPromisedPossible() {
        return $this->countPromise() < $this->max_participant || $this->max_participant == 0;
    }

    public function isPromisedByUser(User $user) {
        return $this->hasStateByUser(ParticipationStatusEnum::Promised, $user);
    }

    public function isWaitlistByUser(User $user) {
        return $this->hasStateByUser(ParticipationStatusEnum::Waitlist, $user);
    }

    public function isCanceledByUser(User $user) {
        return $this->hasStateByUser(ParticipationStatusEnum::Canceled, $user);
    }

    public function hasQuietByUser(User $user) {
        return $this->hasStateByUser(ParticipationStatusEnum::Quiet, $user);
    }

    public function whereNotSendedEmail(String $emailType)
    {
        return $this->whereDoesntHave('sendedmails')
            ->orWhereHas('sendedmails', function(Builder $query) use($emailType) {
                $query->where('kind','!=', $emailType);
            }
        );
    }

    private function hasStateByUser(int $participationStatus, User $user) {
        return $this->getParticipationState($user) == $participationStatus;
    }

    private function calculatePossibleParticipationStatus(int $participationStatus, User $user) {
        if($participationStatus == ParticipationStatusEnum::Promised || $participationStatus == ParticipationStatusEnum::Waitlist)
        {
            $participationStatus = ParticipationStatusEnum::Waitlist;
            if($this->isPromisedPossible() || $this->isPromisedByUser($user)) {
                $participationStatus = ParticipationStatusEnum::Promised;
            }
        }
        return $participationStatus;
    }
}
