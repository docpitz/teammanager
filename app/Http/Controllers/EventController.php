<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\PermissionEnum;
use App\Event;
use App\Http\Requests\EventRequest;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EventRequest $request, Event $model)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        $createdModel = $model->create($request->all());
        $arrayOfUserIds = $request->get('event_users');
        $createdModel->users()->attach($arrayOfUserIds,
            ['participation_status_id' => $request->get('participation_status_id'),
                'changed_by_user_id' => auth()->user()->getIdentifier(),
                'date_user_changed_participation_status' => Carbon::now() ]);

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
        foreach(User::orderBy('surname')->orderBy('firstname')->get() as $user) {

            $allUserWithEventInfo[$i]["id"] = $user->id;
            $allUserWithEventInfo[$i]["surname"] = $user->surname;
            $allUserWithEventInfo[$i]["firstname"] = $user->firstname;
            $allUserWithEventInfo[$i]["groups"] = implode(', ', array_column($user->groups()->getModels(['name']),'name'));
            $selected = false;
            foreach($event->users()->getModels() as $userFoundInEvent) {
                if($user->id == $userFoundInEvent->id) {
                    $selected = true;
                    continue;
                }
            }
            $allUserWithEventInfo[$i]["selected"] = $selected;
            $i++;
        }
        return view('events.edit', ['event' => $event, 'userWithEventInfo' => $allUserWithEventInfo]);
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
        $arrayDeleted = array_diff($arrayEventUsersFromDB, $arrayOfUserIds);
        $arrayAdded = array_diff($arrayOfUserIds, $arrayEventUsersFromDB);
        $event->users()->detach($arrayDeleted);
        $event->users()->attach($arrayAdded,
            ['participation_status_id' => $request->get('participation_status_id'),
                'changed_by_user_id' => auth()->user()->getIdentifier(),
                'date_user_changed_participation_status' => Carbon::now()]);

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
