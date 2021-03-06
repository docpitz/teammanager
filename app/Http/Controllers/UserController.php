<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\PermissionEnum;
use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
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
    public function index(User $model)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::UserManagement)->key, User::class);
        return view('users.index', ['users' => User::allUserSorted()->paginate(100)]);
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::UserManagement)->key, User::class);
        return view('users.create');
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::UserManagement)->key, User::class);
        $password = $request->get('password');
        if(Str::of($password)->trim()->isNotEmpty())
        {
            $request = $request->merge(['password' => Hash::make($password)]);
        }
        $createdModel = $model->create($request->all());
        $createdModel->roles()->sync(Role::findByName($request['role_name']));
        return redirect()->route('user.index')->withStatus(__('Teammitglied erfolgreich erstellt.'));
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::UserManagement)->key, User::class);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::UserManagement)->key, User::class);
        $user->roles()->sync(Role::findByName($request['role_name']));

        if(is_null($request->get('password'))) {
            $expectedRequest = $request->except('password');
            $user->update($expectedRequest);
        }
        else {
            $mergedRequest = $request->merge(['password' => Hash::make($request->get('password'))]);
            $user->update($mergedRequest->all());
        }
        return redirect()->route('user.index')->withStatus(__('Teammitglied erfolgreich geändert.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::UserManagement)->key, User::class);
        $countFuturePromisedAndWaitlistEvents = $user->countFuturePromisedAndWaitlistEvents();
        if($countFuturePromisedAndWaitlistEvents == 0) {
            $user->deleteProfilePicture();
            $user->delete();
            return redirect()->route('user.index')->withStatus(__('Teammitglied erfolgreich gelöscht'));
        } else {
            return redirect()->route('user.index')->withErrors("Das Teammitglied hat noch ".$countFuturePromisedAndWaitlistEvents." zukünftige Veranstaltung mit 'Promised' oder 'Waitlist' und kann deshalb nicht gelöscht werden");
        }
    }
}
