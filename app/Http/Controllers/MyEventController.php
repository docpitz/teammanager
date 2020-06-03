<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\PermissionEnum;
use App\Event;
use App\Http\Requests\MyEventRequest;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


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

    public function canceled(MyEventRequest $request, Event $event) {
        $isConsultationNecessary = false;
        $success = false;
        $isUserPromised = $event->isPromisedByUser(auth()->user());
        $isUserOnWaitlist = $event->isWaitlistByUser(auth()->user());
        $isSomeoneOnWait = $event->countWaitlist() > 0 && $isUserPromised || $event->countWaitlist() > 1 && $isUserOnWaitlist;

        if((!empty($request["confirmDelete"]) && $request["confirmDelete"] === "false") || empty($request["confirmDelete"])) {
            $isConsultationNecessary = $isSomeoneOnWait;
        }
        if(!$isConsultationNecessary) {
            // now we can save, confirm question not necessary or already done
            $success = $this->changeParticipation($event, ParticipationStatusEnum::Canceled);
        }
        if($success && $isSomeoneOnWait && $isUserPromised) {
            // update next user from waitlist to promis
            $userFromWaitListToPromise = User::find($event->waitlistOrderbyHighestWish()->first()->getModel()->id)->getModel();
            $systemUser = User::getSystemUser()->getModel();
            $event->saveParticipation($userFromWaitListToPromise, ParticipationStatusEnum::Promised, $systemUser);
            $userFromWaitListToPromise->sendWaitlistToActivNotification($event);
        }
        return $this->createResponse($success, $isConsultationNecessary, $event);
    }

    public function promised(MyEventRequest $request, Event $event) {
        $success = $this->changeParticipation($event, ParticipationStatusEnum::Promised);
        return $this->createResponse($success, false, $event);
    }

    public function waitlist(MyEventRequest $request, Event $event) {
        $success = $this->changeParticipation($event, ParticipationStatusEnum::Waitlist);
        return $this->createResponse($success, false, $event);
    }

    private function createResponse(bool $success, bool $isConsulationNecessaryByCanceled, Event $event) {
        return response()->json(['success'=> $success,
            'hideParticipationStatus' => $this->getHideParticipationStatusForAjaxResponse($event),
            'participationStatus' => $this->getParticipationStatusForAjaxResponse($event),
            $this->getParticipationStatusForAjaxResponse($event) => true, // the new state
            'countPromises' => $event->countPromise(),
            'countQuiet' => auth()->user()->countQuiet(),
            'isConsulationNecessaryByCanceled' => $isConsulationNecessaryByCanceled]);
    }

    private function changeParticipation(Event $event, int $participationStatus) {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventBookingImmediate)->key, User::class);
        return $event->saveParticipation(auth()->user(), $participationStatus, auth()->user());
    }

    private function getParticipationStatusForAjaxResponse($event) {
        return Str::lower(ParticipationStatusEnum::getDescription($event->getParticipationState(auth()->user())));
    }

    private function getHideParticipationStatusForAjaxResponse($event) {
        return Str::lower(ParticipationStatusEnum::getDescription($event->getHideParticipationState(auth()->user())));
    }
}
