<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        auth()->user()->update(
            $request->merge(['picture' => $request->photo ? $request->photo->store('profile', 'public') : null])
                ->except([$request->hasFile('photo') ? '' : 'picture'])
        );

        return back()->withStatus(__('Mein Spielerprofil erfolgreich aktualisiert'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Passwort erfolgreich aktualisiert'));
    }

    public function avatar(Request $request)
    {
        if($request->hasFile('avatar'))
        {
            $avatar = $request->file('avatar');
            $newFilename = Uuid::uuid4().".".$avatar->getClientOriginalExtension();
            $newCompleteFilename = public_path('/files/avatar/'.$newFilename);
            Image::make($avatar)->resize(400,null, function ($constraint) {
                $constraint->aspectRatio();
            })->crop(300,300)->save($newCompleteFilename);
            $user = Auth::user();
            $oldCompleteFilename = public_path('/files/avatar/'.$user->avatar);
            $oldUuid4 = explode(".", $user->avatar)[0];
            if(!is_null($user->avatar) && File::exists($oldCompleteFilename) && Uuid::isValid($oldUuid4))
            {
                File::delete($oldCompleteFilename);
            }
            $user->avatar = $newFilename;
            $user->save();
            return back()->withStatus(__('Profilbild erfolgreich aktualisiert'));
        }
        return back()->withErrors(__("Profilbild konnte nicht gespeichert werden"));
    }
}
