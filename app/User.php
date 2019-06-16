<?php

namespace App;

use App\Buisness\Enum\RoleEnum;
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
        'name', 'email', 'password',
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

    public function getRole() : RoleEnum
    {
        return RoleEnum::getInstance(RoleEnum::getValue($this->getRoleName()));
    }

    public function getRoleName() : String
    {
        $roleArray = $this->getRoleNames();
        if(count($roleArray) > 0)
        {
            return $roleArray[0];
        }
        return "";
    }

    public function getRoleNameFormatted() : String
    {
        $roleName = ucwords($this->getRoleName());
        $data = preg_split('/(?=[A-Z])/', $roleName);
        return implode(' ', $data);
    }

    public function profilePicture() : String
    {
        return 'http://i.pravatar.cc/200';
    }

}
