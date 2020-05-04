<?php
declare(strict_types=1);

namespace App;

use Altek\Accountant\Contracts\Recordable;
use Altek\Accountant\Models\Ledger;
use Altek\Eventually\Eventually;
use App\Buisness\Enum\ParticipationStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
        return $this->belongsToMany('App\User')
            ->withPivot('participation_status_id', 'date_user_changed_participation_status', 'changed_by_user_id')
            ->withTimestamps();
    }

    public function countPromise() {
        return $this->users()->wherePivot('participation_status_id', ParticipationStatusEnum::Promised)->count();
    }

    public function getUsersByParticipation(int $participationStatus) {
        return $this->users()
            ->addSelect('users.*', 'event_user.*', 'changed_by_user.firstname as changed_by_user_firstname', 'changed_by_user.surname as changed_by_user_surname')
            ->withPivot('changed_by_user_id')
            ->wherePivot('participation_status_id', $participationStatus)
            ->leftJoin('users as changed_by_user', 'event_user.changed_by_user_id', '=', 'changed_by_user.id');
    }

    public function getParticipationChanges(): Array
    {
        $ledgers = $this->ledgers()->whereIn('event', [
            'existingPivotUpdated',
            'attached',
        ])->latest()->get();

        $history = $ledgers->map(function (Ledger $ledger) {

            // TODO: Brauchen wir die Metdadaten noch?
            $ledger->getMetadata();
            return $ledger->getPivotData()["properties"];
        });
        return collect($history)->collapse()->groupBy('user_id')->all();
    }

    public function saveParticipation(User $user, int $participationStatus, User $changedByUser) {
        $this->users()->updateExistingPivot($user->id, ['participation_status_id' => $participationStatus, 'date_user_changed_participation_status' => Carbon::now(), 'changed_by_user_id' => $changedByUser->id]);
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

    public function hasQuietByUser(User $user) {
        return $this->hasStateByUser(ParticipationStatusEnum::Quiet, $user);
    }

    private function hasStateByUser(int $participationStatus, User $user) {
        return $this->getParticipationState($user) == $participationStatus;
    }
}
