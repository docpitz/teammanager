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
        $maxParticipant = $this->request->get('max_participant');
        $participantStatusId = $this->request->get('participation_status_id');

        if($isNew) {
            // create
            if($maxParticipant > 0 && $participantStatusId == ParticipationStatusEnum::Promised) {
                // more users than maximum allowed
                $rules['event_users'] = 'array|max:'.$maxParticipant;
            }
        }
        else {
            // update
            $id = $routeEvent->id;
            $event = Event::where('id', $id)->first();
            $currentCountWaitlist = $event->countWaitlist();
            $currentCountPromises = $this->getCurrentCountPromises($event, $participantStatusId);

            if ($maxParticipant > 0) {
                if($maxParticipant < $currentCountPromises) {
                    // empty waiting list, but too many active participants
                    $rules['max_participant'] = 'numeric|min:'.$currentCountPromises;
                }
                else if ($currentCountWaitlist > 0 && $maxParticipant != $currentCountPromises) {
                    // full waiting list, although participants not exhausted
                    $rules['max_participant'] = 'numeric|size:'.$currentCountPromises;
                }
            } else if ($maxParticipant == 0 && $currentCountWaitlist > 0) {
                // full waiting list, although infinitely many participants
                $rules['max_participant'] = 'numeric|size:'.$currentCountPromises;
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'max_participant.min' => "Aktuell sind :min Teilnehmer gemeldet. Die max. Teilnehmerzahl darf nicht darunter liegen.",
            'max_participant.size' => "Aktuell sind Teilnehmer in der Warteliste, aber die max. Teilnehmerzahl noch nicht ausgeschöpft.",
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

    private function getCurrentCountPromises(Event $event, int $participant_status_id) : int {
        // add new users
        $arrayEventUsersFromDB = array_column($event->users()->getModels(['id']), 'id'); // ids from all users for this event
        $arrayOfUserIds = $this->request->get('event_users');

        // ids from all promised users for this event
        $arrayPromisedEventUsersFromDB = array_column($event->getUsersByParticipation(ParticipationStatusEnum::Promised)->getModels(['id']), 'id');
        if($participant_status_id == ParticipationStatusEnum::Promised) {
            $arrayAdded = is_null($arrayOfUserIds) ? [] : array_diff($arrayOfUserIds, $arrayEventUsersFromDB);
            $arrayPromisedEventUsersFromDB = array_merge($arrayPromisedEventUsersFromDB, $arrayAdded);
        }
        // remove old users
        $arrayDeleted = is_null($arrayOfUserIds) ? $arrayEventUsersFromDB : array_diff($arrayEventUsersFromDB, $arrayOfUserIds);
        $arrayPromisedEventUsersFromDB = is_null($arrayDeleted) ? $arrayPromisedEventUsersFromDB : array_diff($arrayPromisedEventUsersFromDB, $arrayDeleted);
        return count($arrayPromisedEventUsersFromDB);
    }

}
