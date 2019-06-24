<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\PermissionEnum;
use App\Event;
use App\Http\Requests\MyEventRequest;
use App\User;
use Illuminate\Support\Carbon;


class MyEventController extends Controller
{
    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit() {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventBookingImmediate)->key, User::class);
        return view('events.my');
    }

    public function delete(MyEventRequest $request, Event $event) {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventBookingImmediate)->key, User::class);
        $event->saveParticipation(auth()->user(), ParticipationStatusEnum::Canceled);

        return response()->json(['success'=> true, 'cancel' => true, 'countPromises' => $event->countPromise(), 'countNoAnswer' => auth()->user()->countNoAnswer()]);
    }

    public function save(MyEventRequest $request, Event $event) {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventBookingImmediate)->key, User::class);
        $event->saveParticipation(auth()->user(), ParticipationStatusEnum::Promised);
        return response()->json(['success'=> true, 'promise' => true, 'countPromises' => $event->countPromise(), 'countNoAnswer' => auth()->user()->countNoAnswer()]);
    }
}
