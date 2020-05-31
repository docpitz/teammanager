<?php


namespace App\Http\Requests;


use App\Buisness\Enum\ParticipationStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class EventBookingOverviewRequest extends FormRequest
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
        $max_participant = $this->request->get('max_participant');
        $waitlist = $this->request->get('Waitlist');
        $rules = [
            ParticipationStatusEnum::getInstance(ParticipationStatusEnum::Promised)->description => 'array|max:'.$max_participant,
        ];
        if(!empty($waitlist) && count($waitlist) > 0 ) {
            $rules[ParticipationStatusEnum::getInstance(ParticipationStatusEnum::Promised)->description] = 'array|size:'.$max_participant;
        }
        return $rules;
    }

    public function messages()
    {
        return [
            ParticipationStatusEnum::getInstance(ParticipationStatusEnum::Promised)->description.'.max' => 'Es dürfen maximal :max Mitglieder teilnehmen.',
            ParticipationStatusEnum::getInstance(ParticipationStatusEnum::Promised)->description.'.size' => 'Die max. erlaubten Teilnehmer sind noch nicht ausgeschöpft. Es dürfen somit keine Personen in die Warteliste.',
        ];
    }
}
