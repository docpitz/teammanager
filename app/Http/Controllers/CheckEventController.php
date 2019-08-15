<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\PermissionEnum;
use App\User;
use Illuminate\Http\Request;
use App\Event;

class CheckEventController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Show the form for editing the specified user
     *
     * @param  \App\Event  $event
     * @return \Illuminate\View\View
     */
    public function edit(Event $event)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        return view('events.check', ['event' => $event]);
    }
}
