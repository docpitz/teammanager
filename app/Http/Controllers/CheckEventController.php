<?php

namespace App\Http\Controllers;

use App\Helper\UserCache;
use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\PermissionEnum;
use App\Http\Requests\CheckEventRequest;
use App\User;
use App\Event;

class CheckEventController extends Controller
{
    protected $userCache;
    public function __construct()
    {
        $this->userCache = new UserCache();
    }
    /**
     * Show the form for editing the specified user
     *
     * @param  \App\Event  $event
     * @return \Illuminate\View\View
     */
    public function edit(Event $checkEvent)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);

        $usersPromised = $this->getUsersByParticipation($checkEvent, ParticipationStatusEnum::Promised);
        $usersCanceled = $this->getUsersByParticipation($checkEvent, ParticipationStatusEnum::Canceled);
        $usersQuiet = $this->getUsersByParticipation($checkEvent, ParticipationStatusEnum::Quiet);
        $usersWaitlist = $this->getUsersByParticipation($checkEvent, ParticipationStatusEnum::Waitlist);

        $history = $checkEvent->getParticipationHistory();
        $this->addChanges($usersPromised, $history);
        $this->addChanges($usersCanceled, $history);
        $this->addChanges($usersQuiet, $history);
        $this->addChanges($usersWaitlist, $history);
        return view('events.check', ['event' => $checkEvent, 'usersPromised' => $usersPromised, 'usersCanceled' => $usersCanceled, 'usersQuiet' => $usersQuiet, 'usersWaitlist' => $usersWaitlist]);
    }

    public function update(CheckEventRequest $request, Event $checkEvent)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);

        $this->updateParticipation($checkEvent, $request, ParticipationStatusEnum::Promised);
        $this->updateParticipation($checkEvent, $request, ParticipationStatusEnum::Canceled);
        $this->updateParticipation($checkEvent, $request, ParticipationStatusEnum::Quiet);
        $this->updateParticipation($checkEvent, $request, ParticipationStatusEnum::Waitlist);

        return redirect()->route('event.index')->withStatus(__('Teilnehmer an Veranstaltung erfolgreich geändert.'));
    }

    private function updateParticipation(Event $checkEvent, CheckEventRequest $request, int $newParticipationStatus)
    {
        $newParticipationStatusEnum = ParticipationStatusEnum::getInstance($newParticipationStatus);
        $userIds = $request->get($newParticipationStatusEnum->description);
        if(empty($userIds))
        {
            return;
        }
        foreach ($userIds as $userId)
        {
            $user = $this->userCache->getUserById($userId);
            $oldParticipationStatusEnum = ParticipationStatusEnum::getInstance($checkEvent->getParticipationState($user));
            if(! $newParticipationStatusEnum->is($oldParticipationStatusEnum))
            {
                $checkEvent->saveParticipation($user, $newParticipationStatus, auth()->user(), true);
            }
        }

    }

    private function addChanges(Array $participations, Array $changes)
    {
        foreach ($participations as $participation)
        {
            $participation->changes = $changes[$participation->id];
        }
    }

    private function getUsersByParticipation(Event $checkEvent, int $participationStatus) {
        $validatorfails = !empty(old("_token"));
        $oldUserIds = old(ParticipationStatusEnum::getInstance($participationStatus)->description);
        $users = [];
        if(!$validatorfails) {
            $users = $checkEvent->getUsersByParticipation($participationStatus)->getModels();
        }
        elseif (!empty($oldUserIds)) {
            $users = $checkEvent->getUsersParticipationByUserIds($oldUserIds)->getModels();
        }
        return $users;
    }
}
