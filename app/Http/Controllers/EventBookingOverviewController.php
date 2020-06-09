<?php

namespace App\Http\Controllers;

use App\Helper\UserCache;
use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\PermissionEnum;
use App\Http\Requests\EventBookingOverviewRequest;
use App\User;
use App\Event;
use Illuminate\Support\Facades\DB;

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

        $eventResponsibles = $eventBookingOverview->responsibles()->getModels([DB::raw('CAST(`id` as CHARACTER) AS `data-id`'), DB::raw('CONCAT(firstname, " ", surname) AS value')]);
        return view('events.booking_overview', ['event' => $eventBookingOverview,
            'usersPromised' => $usersPromised,
            'usersCanceled' => $usersCanceled,
            'usersQuiet' => $usersQuiet,
            'usersWaitlist' => $usersWaitlist,
            'event_responsible' => $eventResponsibles]);
    }

    public function update(EventBookingOverviewRequest $request, Event $eventBookingOverview)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);

        $this->sendMailWaitlistToActiv($eventBookingOverview, $request);

        $this->updateParticipation($eventBookingOverview, $request, ParticipationStatusEnum::Promised);
        $this->updateParticipation($eventBookingOverview, $request, ParticipationStatusEnum::Canceled);
        $this->updateParticipation($eventBookingOverview, $request, ParticipationStatusEnum::Quiet);
        $this->updateParticipation($eventBookingOverview, $request, ParticipationStatusEnum::Waitlist);

        // Renew Responsibles
        $eventBookingOverview->responsibles()->detach();
        if(!empty($request->get('event_responsible'))) {
            $arrayResonsibles = array_column(json_decode($request->get('event_responsible')),'data-id');
            $eventBookingOverview->responsibles()->attach($arrayResonsibles);
        }

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

    private function sendMailWaitlistToActiv(Event $eventBookingOverview, EventBookingOverviewRequest $request)
    {
        $userIdsWithNewStatusPromised = $request->get(ParticipationStatusEnum::getInstance(ParticipationStatusEnum::Promised)->description);
        if(empty($userIdsWithNewStatusPromised))
        {
            return;
        }
        foreach ($userIdsWithNewStatusPromised as $userId)
        {
            $user = $this->userCache->getUserById($userId);
            $oldParticipationStatusEnum = ParticipationStatusEnum::getInstance($eventBookingOverview->getParticipationState($user));
            if($oldParticipationStatusEnum->value == ParticipationStatusEnum::Waitlist)
            {
                $user->sendWaitlistToActivNotification($eventBookingOverview);
            }
        }
    }
}
