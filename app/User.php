<?php

namespace App;

use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPassword;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','firstname','surname', 'email', 'email_optional', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token, $this));
    }

    public function getRole() : ?RoleEnum {
        if(is_null($this->getRoleName()))
        {
            return NULL;
        }
        return RoleEnum::getInstance(RoleEnum::getValue($this->getRoleName()));
    }

    private function getRoleName() : ?String {
        $roleArray = $this->getRoleNames();
        if(count($roleArray) > 0)
        {
            return $roleArray[0];
        }
        return NULL;
    }

    public function profilePicture() : String
    {
        return 'http://i.pravatar.cc/200';
    }

    public function groups() {
        return $this->belongsToMany('App\Group')
            ->withTimestamps();
    }

    public function events() {
        return $this->belongsToMany('App\Event')
            ->withPivot('participation_status_id', 'date_user_changed_participation_status')
            ->orderBy('date_event_start')
            ->withTimestamps();
    }

    public function eventsToBook() {
        return $this->belongsToMany('App\Event')
            ->select(DB::raw('*, date_sign_up_start <= CURRENT_DATE() AND date_sign_up_end >= CURRENT_DATE() AS booking_possible'))
            ->whereDate('date_publication', '<', Carbon::now())
            ->whereDate('date_event_end', '>', Carbon::now())
            ->withPivot('participation_status_id', 'date_user_changed_participation_status')
            ->orderBy('date_event_start')
            ->withTimestamps();
    }

    public function countNoAnswer() {
        return $this->belongsToMany('App\Event')
            ->whereDate('date_publication', '<', Carbon::now())
            ->whereDate('date_event_end', '>', Carbon::now())
            ->whereDate('date_sign_up_start', '<', Carbon::now())
            ->whereDate('date_sign_up_end', '>', Carbon::now())
            ->wherePivot('participation_status_id', ParticipationStatusEnum::NoAnswer)
            ->count();
    }

}
