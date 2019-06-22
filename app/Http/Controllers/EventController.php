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
            ['participation_status_id' => $request->get('participation_status_id')]);

        return redirect()->route('event.index')->withStatus(__('Veranstaltung erfolgreich erstellt.'));
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(Event $user)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        return view('events.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EventRequest $request, Event $user)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        $user->roles()->sync(Role::findByName($request['role_name']));

        if(is_null($request->get('password'))) {
            $expectedRequest = $request->except('password');
            $user->update($expectedRequest);
        }
        else {
            $mergedRequest = $request->merge(['password' => Hash::make($request->get('password'))]);
            $user->update($mergedRequest->all());
        }
        return redirect()->route('event.index')->withStatus(__('Teammitglied erfolgreich geändert.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $user)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::EventManagement)->key, User::class);
        $user->delete();
        return redirect()->route('event.index')->withStatus(__('Teammitglied erfolgreich gelöscht'));
    }
}
