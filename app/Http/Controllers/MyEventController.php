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
        $events = auth()->user()->eventsToBook()->getModels();
        return view('events.my', ['events' => $events]);
    }

    public function delete(MyEventRequest $request, Event $event) {
        $success = false;
        if($event->getParticipationState(auth()->user()) != ParticipationStatusEnum::Canceled)
        {
            $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventBookingImmediate)->key, User::class);
            $event->saveParticipation(auth()->user(), ParticipationStatusEnum::Canceled, auth()->user());
            $success = true;
        }
        return response()->json(['success'=> $success, 'cancel' => true, 'countPromises' => $event->countPromise(), 'countQuiet' => auth()->user()->countQuiet()]);
    }

    public function save(MyEventRequest $request, Event $event) {
        $success = false;
        if($event->getParticipationState(auth()->user()) != ParticipationStatusEnum::Promised)
        {
            $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventBookingImmediate)->key, User::class);
            $event->saveParticipation(auth()->user(), ParticipationStatusEnum::Promised, auth()->user());
            $success = true;
        }
        return response()->json(['success'=> $success, 'promise' => true, 'countPromises' => $event->countPromise(), 'countQuiet' => auth()->user()->countQuiet()]);
    }
}
