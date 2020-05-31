<?php


namespace App\Helper;


use App\Helper\Singleton;
use App\User;
use Illuminate\Support\Arr;

class UserCache
{
    protected $users = [];

    public function getUserById($id) : User {
        if(Arr::exists($this->users, $id)) {
            return Arr::get($this->users, $id);
        }
        else {
            $user = User::find($id);
            $this->users[$id] = $user;
            return $user;
        }
    }
}
