<?php

namespace App\Http\Requests;

use App\Buisness\Enum\ParticipationStatusEnum;
use App\Event;
use App\Role;
use App\User;
use Illuminate\Support\Arr;
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
        $rules = [
            'name' => 'required|min:3',
            'description' => 'nullable',
            'score' => 'nullable',
            'max_participant' => 'required',
            'meeting_place' => 'required',
            'date_event_range' => 'required',
            'date_sign_up_range' => 'required',
            'date_publication' => 'required',
            'participation_status_id' => 'required',
        ];

        $routeEvent = $this->route('event');
        $isNew = empty($routeEvent);

        $max_participant = $this->request->get('max_participant');
        $participant_status_id = $this->request->get('participation_status_id');

        if($max_participant > 0 && $participant_status_id == ParticipationStatusEnum::Promised) {
            if (! $isNew) {
                $id = $routeEvent->id;
                $event = Event::where('id', $id);
                $event = $event->first();
                $arrayPromisedEventUsersFromDB = array_column($event->getUsersByParticipation(ParticipationStatusEnum::Promised)->getModels(['id']), 'id'); // ids from all promised users for this event

                // add new users
                $arrayEventUsersFromDB = array_column($event->users()->getModels(['id']), 'id'); // ids from all users for this event
                $arrayOfUserIds = $this->request->get('event_users');
                $arrayAdded = is_null($arrayOfUserIds) ? [] : array_diff($arrayOfUserIds, $arrayEventUsersFromDB);
                $arrayPromisedEventUsersFromDB = array_merge($arrayPromisedEventUsersFromDB, $arrayAdded);

                // remove old users
                $arrayDeleted = is_null($arrayOfUserIds) ? $arrayEventUsersFromDB : array_diff($arrayEventUsersFromDB, $arrayOfUserIds);
                Arr::forget($arrayPromisedEventUsersFromDB, $arrayDeleted);

                $currentCountPromises = count($arrayPromisedEventUsersFromDB);
                if($currentCountPromises > $max_participant) {
                    $rules['event_users'] = 'array|max:'.$max_participant;
                }
            }
            else {
                $rules['event_users'] = 'array|max:'.$max_participant;
            }

        }
        return $rules;
    }

    public function messages()
    {
        return [
            'event_users.max' => "Es dürfen an dieser Veranstaltung maximal :max Mitglied(er) teilnehmen.
            Durch die voreingestellte Teilnahme mit 'Promise' ist die Gesamtteilnehmerzahl bereits überschritten.
            Bitte ändere entweder die 'maximale Teilnehmerzahl', die Anzahl der teilnehmenden Mitglieder
            oder die 'voreingestellte Antwort'",
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();

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
