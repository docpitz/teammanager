<?php

namespace App;

use App\Buisness\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

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
            ->withTimestamps();
    }

}
