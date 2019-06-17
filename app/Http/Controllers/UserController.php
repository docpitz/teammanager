<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\PermissionEnum;
use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
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
        return view('users.index', ['users' => User::paginate(25)]);
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
        $createdModel = $model->create($request->merge(['password' => Hash::make($request->get('password'))])->all());
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
        $user->delete();
        return redirect()->route('user.index')->withStatus(__('Teammitglied erfolgreich gelöscht'));
    }
}
