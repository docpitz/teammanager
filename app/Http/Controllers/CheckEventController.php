<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\PermissionEnum;
use App\Http\Requests\CheckEventRequest;
use App\User;
use App\Event;
use function PHPUnit\Framework\isEmpty;

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
}
