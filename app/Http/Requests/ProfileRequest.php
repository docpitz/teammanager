<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'username' => ['required', 'min:3', Rule::unique((new User)->getTable())->ignore(auth()->id())],
            'email' => 'required|email:filter|differentIgnoreCase:email_optional',
            'email_optional' => 'nullable|email:filter|differentIgnoreCase:email',
            'photo' => 'nullable|image',
        ];
    }
}
