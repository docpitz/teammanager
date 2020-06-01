<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\PermissionEnum;
use App\Group;
use App\Http\Requests\GroupRequest;
use App\User;

class GroupController extends Controller
{
    public function __construct()
    {
        //FIXME: 403 - Forbidden
        //$this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the groups
     *
     * @param  \App\Group  $model
     * @return \Illuminate\View\View
     */
    public function index(Group $model)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::GroupManagement)->key, User::class);
        return view('groups.index', ['groups' => Group::orderBy('name','asc')->paginate(25)]);
    }

    /**
     * Show the form for creating a new Group
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::GroupManagement)->key, User::class);
        return view('groups.create', ['users' => User::allUserSorted()->getModels()]);
    }

    /**
     * Store a newly created group in storage
     *
     * @param  \App\Http\Requests\GroupRequest  $request
     * @param  \App\Group  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GroupRequest $request, Group $model)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::GroupManagement)->key, User::class);
        $createdModel = $model->create($request->all());
        $arrayOfUserIds = $request->get('group_users');
        $createdModel->users()->sync($arrayOfUserIds);
        return redirect()->route('group.index')->withStatus(__('Gruppe erfolgreich erstellt.'));
    }

    /**
     * Show the form for editing the specified group
     *
     * @param  \App\Group  $group
     * @return \Illuminate\View\View
     */
    public function edit(Group $group)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::GroupManagement)->key, User::class);

        $allUserWithGroupInfo = array();
        $i = 0;
        foreach(User::allUserSorted()->getModels() as $user) {

            $allUserWithGroupInfo[$i]["id"] = $user->id;
            $allUserWithGroupInfo[$i]["surname"] = $user->surname;
            $allUserWithGroupInfo[$i]["firstname"] = $user->firstname;
            $selected = false;
            foreach($group->users()
                        ->getModels() as $userFoundInGroup) {
                if($user->id == $userFoundInGroup->id) {
                    $selected = true;
                    continue;
                }
            }
            $allUserWithGroupInfo[$i]["selected"] = $selected;
            $i++;
        }
        return view('groups.edit', ['group' => $group, 'userWithGroupInfo' => $allUserWithGroupInfo]);
    }

    /**
     * Update the specified group in storage
     *
     * @param  \App\Http\Requests\GroupRequest  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(GroupRequest $request, Group $group)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::GroupManagement)->key, User::class);

        $group->update($request->all());

        $arrayOfUserIds = $request->get('group_users');
        $group->users()->sync($arrayOfUserIds);

        return redirect()->route('group.index')->withStatus(__('Gruppe erfolgreich geändert.'));
    }

    /**
     * Remove the specified group from storage
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Group $group)
    {
        $this->authorize(PermissionEnum::getInstance(PermissionEnum::GroupManagement)->key, User::class);
        $group->delete();
        return redirect()->route('group.index')->withStatus(__('Gruppe erfolgreich gelöscht'));
    }
}
