<?php

namespace App;

use App\Notifications\InformationAboutParticipants;
use App\Notifications\WaitlistToActiv;
use Avatar;
use Altek\Accountant\Contracts\Identifiable;
use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\RoleEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPassword;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable implements Identifiable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','firstname','surname', 'email', 'email_optional', 'password', 'visible',
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
        'date_user_changed_participation_status' => 'datetime'
    ];

    public static function getSystemUser() {

        return User::whereHas("roles", function($q) {
            $systemRoleString = RoleEnum::getInstance(RoleEnum::System)->description;
            $q->where("name", $systemRoleString);
        })->first();
    }

    public static function allUserSorted() {
        return User::where('visible', true)->orderBy('surname')->orderBy('firstname');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token, $this));
    }

    public function sendWaitlistToActivNotification(Event $event)
    {
        $this->notify(new WaitlistToActiv($this, $event));
    }

    public function sendInformationAboutParticipants(Event $event)
    {
        $this->notify(new InformationAboutParticipants($event));
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

    public function profilePicture()
    {
        $avatar = Avatar::create($this->firstname." ".$this->surname)->toBase64();
        if(! is_null($this->avatar))
        {
            $avatar = '/files/avatar/'.$this->avatar;
        }
        return $avatar;
    }

    public function saveProfilePicture(UploadedFile $avatar): String
    {
        $newFilename = Uuid::uuid4().".".$avatar->getClientOriginalExtension();
        $newCompleteFilename = public_path('/files/avatar/'.$newFilename);
        Image::make($avatar)->resize(400,null, function ($constraint) {
            $constraint->aspectRatio();
        })->crop(300,300)->save($newCompleteFilename);
        return $newFilename;
    }

    public function deleteProfilePicture()
    {
        $oldCompleteFilename = public_path('/files/avatar/'.$this->avatar);
        $oldUuid4 = explode(".", $this->avatar)[0];
        if(!is_null($this->avatar) && File::exists($oldCompleteFilename) && Uuid::isValid($oldUuid4))
        {
            File::delete($oldCompleteFilename);
        }
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
            ->select(DB::raw('*, date_sign_up_start <= CURRENT_DATE() AND date_sign_up_end >= CURRENT_DATE() AND date_event_end > CURRENT_TIMESTAMP() AS booking_possible'))
            ->whereDate('date_publication', '<=', Carbon::now())
            ->whereDate('date_event_end', '>=', Carbon::now())
            ->withPivot('participation_status_id', 'date_user_changed_participation_status')
            ->orderBy('date_event_start')
            ->withTimestamps();
    }

    public function countFutureQuietEvents() {
        return $this->belongsToMany('App\Event')
            ->whereDate('date_publication', '<=', Carbon::now())
            ->whereDate('date_event_end', '>', Carbon::now())
            ->whereDate('date_sign_up_start', '<=', Carbon::now())
            ->whereDate('date_sign_up_end', '>=', Carbon::now())
            ->wherePivot('participation_status_id',"=", ParticipationStatusEnum::Quiet)
            ->count();
    }

    public function countFuturePromisedAndWaitlistEvents() {
         $query = $this->belongsToMany('App\Event')
             ->whereDate('date_event_end', '>', Carbon::now())
             ->withPivot('participation_status_id')
             ->wherePivotIn('participation_status_id',  [ParticipationStatusEnum::Promised, ParticipationStatusEnum::Waitlist]);
        return $query->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->getKey();
    }
}
