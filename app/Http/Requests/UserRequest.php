<?php

namespace App\Http\Requests;

use App\Role;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'min:3', Rule::unique((new User)->getTable())->ignore($this->route()->user->id ?? null)],
            'firstname' => 'required|min:3',
            'surname' => 'required|min:3',
            'email' => 'required|email|different:email_optional',
            'email_optional' => 'nullable|email|different:email',
            'password' => 'nullable|confirmed|min:6',
            'role_name' => 'required',
        ];
    }
}
