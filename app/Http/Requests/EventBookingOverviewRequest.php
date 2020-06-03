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
        $promisedKey = ParticipationStatusEnum::getInstance(ParticipationStatusEnum::Promised)->description;
        $waitlistKey = ParticipationStatusEnum::getInstance(ParticipationStatusEnum::Waitlist)->description;
        $waitList = $this->request->get($waitlistKey);
        $promiseList = $this->request->get($promisedKey);
        $rules = array($promisedKey => ["array",
            function ($attribute, $promiseList, $fail) use ($waitList, $max_participant) {
                $errorMessage = [];
                if($max_participant > 0 && !empty($promiseList) && count($promiseList) > $max_participant) {
                    array_push($errorMessage, "Es dürfen maximal ".$max_participant." Mitglieder teilnehmen.");;
                }

                if(!empty($waitList) && count($waitList) > 0 && $max_participant > 0 ) {
                    if(empty($promiseList) || (!empty($promiseList) && count($promiseList) < $max_participant)) {
                        array_push($errorMessage, "Die max. erlaubten Teilnehmer sind noch nicht ausgeschöpft. Es dürfen somit keine Personen in die Warteliste.");
                    }
                }

                if(!empty($errorMessage)) {
                    $fail($errorMessage);
                }
            }],
            $waitlistKey => ["array",
                function ($attribute, $waitList, $fail) use ($promiseList, $max_participant) {
                    $errorMessage = [];
                    if(!empty($waitList) && count($waitList) > 0 && $max_participant > 0 ) {
                        if(empty($promiseList) || (!empty($promiseList) && count($promiseList) < $max_participant)) {
                            array_push($errorMessage, "Die max. erlaubten Teilnehmer sind noch nicht ausgeschöpft. Es dürfen somit keine Personen in die Warteliste.");
                        }
                    }

                    if($max_participant == 0 && !empty($waitList) && count($waitList) > 0) {
                        array_push($errorMessage, "Es dürfen unbegrentzt Mitglieder teilnehmen. Es dürfen somit keine Personen in die Warteliste.");
                    }

                    if(!empty($errorMessage)) {
                        $fail($errorMessage);
                    }
                }]
            );
        return $rules;
    }
}
