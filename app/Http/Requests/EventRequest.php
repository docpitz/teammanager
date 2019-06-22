<?php

namespace App\Http\Requests;

use App\Role;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'name' => [
                'required', 'min:3'
            ],
            'description' => [
                'nullable'
            ],
            'score' => [
                'nullable'
            ],
            'max_participant' => [
                'required'
            ],
            'meeting_place' => [
                'required'
            ],
            'date_event_range' => [
                'required'
            ],
            'date_sign_up_range' => [
                'required'
            ],
            'date_publication' => [
                'required'
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();

        var_dump($this->get('participation_status_id'));

        $dateEvent = explode(' - ',$input['date_event_range']);
        $input['date_event_start'] = Carbon::parse($dateEvent[0]);
        $input['date_event_end'] = Carbon::parse($dateEvent[1]);

        $dateEvent = explode(' - ',$input['date_sign_up_range']);
        $input['date_sign_up_start'] = Carbon::parse($dateEvent[0]);
        $input['date_sign_up_end'] = Carbon::parse($dateEvent[1]);

        $input['date_publication'] = Carbon::parse($input['date_publication']);
        $this->replace($input);
    }

}
