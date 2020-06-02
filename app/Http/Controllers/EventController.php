<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\ParticipationStatusEnum;
use App\Buisness\Enum\PermissionEnum;
use App\Event;
use App\Http\Requests\EventRequest;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    private $request;
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
        $this->request = $request;
        //$this->rules();

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
// TODO: Remove!!!!
/*

    public function rules()
    {
        $rules = [
            'name' => 'required|min:3',
            'description' => 'nullable',
            'score' => 'nullable',
            'max_participant' => 'required',
            'meeting_place' => 'required',
            'date_event_range' => 'required',
            'date_sign_up_range' => 'required',
            'date_publication' => 'required',
            'participation_status_id' => 'required',
        ];

        $id = 2;
        $isNew = empty($id);
        echo "id<br>";
        var_dump($id);
        echo "<br>+++++++++++++++++++++++++++++++++++++++++++++<br>";

        $max_participant = $this->request->get('max_participant');
        $participant_status_id = $this->request->get('participation_status_id');

        if($max_participant > 0 && $participant_status_id == ParticipationStatusEnum::Promised) {
            if (! $isNew) {
                $event = Event::where('id', $id);
                var_dump($event->toSql());
                $event = $event->first();
                var_dump($event->id);
                $arrayPromisedEventUsersFromDB = array_column($event->getUsersByParticipation(ParticipationStatusEnum::Promised)->getModels(['id']), 'id'); // ids from all promised users for this event
                $oldCountPromises = count($arrayPromisedEventUsersFromDB);
                echo "arrayPromisedEventUsersFromDB<br>";
                var_dump($arrayPromisedEventUsersFromDB);
                echo "<br>+++++++++++++++++++++++++++++++++++++++++++++<br>";
                // add new users
                $arrayEventUsersFromDB = array_column($event->users()->getModels(['id']), 'id'); // ids from all users for this event
                echo "arrayEventUsersFromDB<br>";
                var_dump($arrayEventUsersFromDB);
                echo "<br>+++++++++++++++++++++++++++++++++++++++++++++<br>";

                $arrayOfUserIds = $this->request->get('event_users');
                echo "arrayOfUserIds<br>";
                var_dump($arrayOfUserIds);
                echo "<br>+++++++++++++++++++++++++++++++++++++++++++++<br>";

                $arrayAdded = is_null($arrayOfUserIds) ? [] : array_diff($arrayOfUserIds, $arrayEventUsersFromDB);
                echo "arrayAdded<br>";
                var_dump($arrayAdded);
                echo "<br>+++++++++++++++++++++++++++++++++++++++++++++<br>";

                $arrayPromisedEventUsersFromDB = array_merge($arrayPromisedEventUsersFromDB, $arrayAdded);
                echo "arrayPromisedEventUsersFromDB<br>";
                var_dump($arrayPromisedEventUsersFromDB);
                echo "<br>+++++++++++++++++++++++++++++++++++++++++++++<br>";

                // remove old users
                $arrayDeleted = is_null($arrayOfUserIds) ? $arrayEventUsersFromDB : array_diff($arrayEventUsersFromDB, $arrayOfUserIds);
                echo "arrayDeleted<br>";
                var_dump($arrayDeleted);
                echo "<br>+++++++++++++++++++++++++++++++++++++++++++++<br>";

                Arr::forget($arrayPromisedEventUsersFromDB, $arrayDeleted);
                echo "arrayPromisedEventUsersFromDB<br>";
                var_dump($arrayPromisedEventUsersFromDB);
                echo "<br>+++++++++++++++++++++++++++++++++++++++++++++<br>";

                echo "max_participant<br>";
                var_dump($max_participant);
                echo "<br>+++++++++++++++++++++++++++++++++++++++++++++<br>";

                $currentCountPromises = count($arrayPromisedEventUsersFromDB);
                if($currentCountPromises > $max_participant) {
                    $rules['event_users'] = 'array|max:'.$max_participant;
                }
            }
            else {
                $rules['event_users'] = 'array|max:'.$max_participant;
            }

        }
        return $rules;
    }
*/
}
