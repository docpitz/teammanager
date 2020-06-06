<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\PermissionEnum;
use App\Event;
use App\Http\Requests\EventRequest;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function __construct()
    {
        //FIXME: 403 - Forbidden
        //$this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(Event $model)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        return view('events.index', ['events' => Event::orderBy('date_event_start','asc')->paginate(25)]);
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        return view('events.create');
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EventRequest $request, Event $event)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        $createdModel = $event->create($request->all());
        $arrayOfUserIds = $request->get('event_users');
        $createdModel->users()->attach($arrayOfUserIds,
            ['participation_status_id' => $request->get('participation_status_id'),
                'changed_by_user_id' => auth()->user()->getIdentifier(),
                'date_user_changed_participation_status' => Carbon::now() ]);

        if(!empty($request->get('event_responsible'))) {
            $arrayResonsibles = array_column(json_decode($request->get('event_responsible')),'data-id');
            $createdModel->responsibles()->attach($arrayResonsibles);
        }

        return redirect()->route('event.index')->withStatus(__('Veranstaltung erfolgreich erstellt.'));
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
        $event['date_event_range'] = $event->date_event_start->format('d.m.Y H:i').' - '.$event->date_event_end->format('d.m.Y H:i');
        $event['date_sign_up_range'] = $event->date_sign_up_start->format('d.m.Y').' - '.$event->date_sign_up_end->format('d.m.Y');

        $allUserWithEventInfo = array();
        $i = 0;
        $eventFromUser = $event->users()->getModels();

        foreach(User::allUserSorted()->get() as $user) {

            $allUserWithEventInfo[$i]["id"] = $user->id;
            $allUserWithEventInfo[$i]["surname"] = $user->surname;
            $allUserWithEventInfo[$i]["firstname"] = $user->firstname;
            $allUserWithEventInfo[$i]["groups"] = implode(', ', array_column($user->groups()->getModels(['name']),'name'));
            $selected = false;
            foreach($eventFromUser as $userFoundInEvent) {
                if($user->id == $userFoundInEvent->id) {
                    $selected = true;
                    continue;
                }
            }
            $allUserWithEventInfo[$i]["selected"] = $selected;
            $i++;
        }
        $eventResponsibles = $event->responsibles()->getModels([DB::raw('CAST(`id` as CHARACTER) AS `data-id`'), DB::raw('CONCAT(firstname, " ", surname) AS value')]);

        return view('events.edit', ['event' => $event, 'userWithEventInfo' => $allUserWithEventInfo, 'event_responsible' => $eventResponsibles]);
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EventRequest $request, Event $event)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        $event->update($request->all());

        // Users that already exist will not be changed or deleted
        // Users that have been added will be re-entered with the current default response
        // Users that have been deleted will simply be deleted.
        $arrayEventUsersFromDB = array_column($event->users()->getModels(['id']), 'id');
        $arrayOfUserIds = $request->get('event_users');
        $arrayDeleted = is_null($arrayOfUserIds) ? $arrayEventUsersFromDB : array_diff($arrayEventUsersFromDB, $arrayOfUserIds);
        $arrayAdded = is_null($arrayOfUserIds) ? [] : array_diff($arrayOfUserIds, $arrayEventUsersFromDB);
        if(count($arrayDeleted) > 0) {
            $event->users()->detach($arrayDeleted);
        }
        if(count($arrayAdded) > 0) {
            $event->users()->attach($arrayAdded,
                ['participation_status_id' => $request->get('participation_status_id'),
                    'changed_by_user_id' => auth()->user()->getIdentifier(),
                    'date_user_changed_participation_status' => Carbon::now()]);

        }

        // Renew Responsibles
        $event->responsibles()->detach();
        if(!empty($request->get('event_responsible'))) {
            $arrayResonsibles = array_column(json_decode($request->get('event_responsible')),'data-id');
            $event->responsibles()->attach($arrayResonsibles);
        }

        return redirect()->route('event.index')->withStatus(__('Veranstaltung erfolgreich geändert.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        $event->delete();
        return redirect()->route('event.index')->withStatus(__('Veranstaltung erfolgreich gelöscht'));
    }
}
