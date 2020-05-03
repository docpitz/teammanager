<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\PermissionEnum;
use App\Http\Requests\CheckEventRequest;
use App\ParticipationStatus;
use App\User;
use App\Event;
use Illuminate\Support\Carbon;

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
    public function edit(Event $checkEvent)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        $usersPromised = $checkEvent->getUsersByParticipation(ParticipationStatusEnum::Promised)->getModels();
        $usersCanceled = $checkEvent->getUsersByParticipation(ParticipationStatusEnum::Canceled)->getModels();
        $usersQuiet = $checkEvent->getUsersByParticipation(ParticipationStatusEnum::Quiet)->getModels();
        $changes = $checkEvent->getParticipationChanges();
        $changes = $this->upgradeChanges($changes);
        $this->addChanges($usersPromised, $changes);
        $this->addChanges($usersCanceled, $changes);
        $this->addChanges($usersQuiet, $changes);
        return view('events.check', ['event' => $checkEvent, 'usersPromised' => $usersPromised, 'usersCanceled' => $usersCanceled, 'usersQuiet' => $usersQuiet]);
    }

    public function update(CheckEventRequest $request, Event $checkEvent)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);

        $this->updateParticipation($checkEvent, $request, ParticipationStatusEnum::Promised);
        $this->updateParticipation($checkEvent, $request, ParticipationStatusEnum::Canceled);
        $this->updateParticipation($checkEvent, $request, ParticipationStatusEnum::Quiet);

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
            $user = User::findOrFail($userId);
            $oldParticipationStatusEnum = ParticipationStatusEnum::getInstance($checkEvent->getParticipationState($user));
            if(! $newParticipationStatusEnum->is($oldParticipationStatusEnum))
            {
                $checkEvent->saveParticipation($user, $newParticipationStatus, auth()->user());
            }
        }

    }

    private function upgradeChanges(Array $changesByUser)
    {
        return collect($changesByUser)->map(function ($changes)
        {
            return collect($changes)->map(function($change)
            {
                $carbon = new Carbon($change['date_user_changed_participation_status']);
                $formatedTime = $carbon->format('d.m.Y H:i');
                Arr::set($change, 'user', User::find($change['changed_by_user_id']));
                Arr::set($change, 'participation_status', ParticipationStatus::find($change['participation_status_id']));
                Arr::set($change, 'changed_date_formatted', $formatedTime);
                return $change;
            }
            );
        })->toArray();
    }

    private function addChanges(Array $participations, Array $changes)
    {
        foreach ($participations as $participation)
        {
            $participation->changes = $changes[$participation->id];
        }
    }
}
