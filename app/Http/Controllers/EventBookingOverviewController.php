<?php

namespace App\Http\Controllers;

use App\Helper\UserCache;
use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\PermissionEnum;
use App\Http\Requests\EventBookingOverviewRequest;
use App\User;
use App\Event;

class EventBookingOverviewController extends Controller
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
    public function edit(Event $eventBookingOverview)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);

        $usersPromised = $this->getUsersByParticipation($eventBookingOverview, ParticipationStatusEnum::Promised);
        $usersCanceled = $this->getUsersByParticipation($eventBookingOverview, ParticipationStatusEnum::Canceled);
        $usersQuiet = $this->getUsersByParticipation($eventBookingOverview, ParticipationStatusEnum::Quiet);
        $usersWaitlist = $this->getUsersByParticipation($eventBookingOverview, ParticipationStatusEnum::Waitlist);

        $history = $eventBookingOverview->getParticipationHistory();
        $this->addChanges($usersPromised, $history);
        $this->addChanges($usersCanceled, $history);
        $this->addChanges($usersQuiet, $history);
        $this->addChanges($usersWaitlist, $history);
        return view('events.booking_overview', ['event' => $eventBookingOverview, 'usersPromised' => $usersPromised, 'usersCanceled' => $usersCanceled, 'usersQuiet' => $usersQuiet, 'usersWaitlist' => $usersWaitlist]);
    }

    public function update(EventBookingOverviewRequest $request, Event $eventBookingOverview)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);

        $this->updateParticipation($eventBookingOverview, $request, ParticipationStatusEnum::Promised);
        $this->updateParticipation($eventBookingOverview, $request, ParticipationStatusEnum::Canceled);
        $this->updateParticipation($eventBookingOverview, $request, ParticipationStatusEnum::Quiet);
        $this->updateParticipation($eventBookingOverview, $request, ParticipationStatusEnum::Waitlist);

        return redirect()->route('event.index')->withStatus(__('Teilnehmer an Veranstaltung erfolgreich geändert.'));
    }

    private function updateParticipation(Event $eventBookingOverview, EventBookingOverviewRequest $request, int $newParticipationStatus)
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
            $oldParticipationStatusEnum = ParticipationStatusEnum::getInstance($eventBookingOverview->getParticipationState($user));
            if(! $newParticipationStatusEnum->is($oldParticipationStatusEnum))
            {
                $eventBookingOverview->saveParticipation($user, $newParticipationStatus, auth()->user(), true);
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

    private function getUsersByParticipation(Event $eventBookingOverview, int $participationStatus) {
        $validatorfails = !empty(old("_token"));
        $oldUserIds = old(ParticipationStatusEnum::getInstance($participationStatus)->description);
        $users = [];
        if(!$validatorfails) {
            $users = $eventBookingOverview->getUsersByParticipation($participationStatus)->getModels();
        }
        elseif (!empty($oldUserIds)) {
            $users = $eventBookingOverview->getUsersParticipationByUserIds($oldUserIds)->getModels();
        }
        return $users;
    }
}