<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('Teilnehmer') }}</th>
            <th scope="col">{{ __('Veranstaltungszeitraum') }}</th>
            <th scope="col">{{ __('Veröffentlichung') }}</th>
            <th scope="col">{{ __('Anmeldezeitraum') }}</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($events as $event)
            <tr>
                <td>{{ $event->name }}</td>
                <td><a class="text-decoration-none" data-boundary="window" data-toggle="tooltip" data-html="true" data-placement="top" @if($event->countPromise()>0)title="<b>Angemeldet:</b><br>@foreach($event->getUsersByParticipation(\App\Buisness\Enum\ParticipationStatusEnum::Promised)->getModels() as $participant){{$participant->firstname}} {{$participant->surname}}<br>@endforeach"@endif>{{__("Teilnehmer: ")}}</a>{{$event->countPromise()}} /
                    {!! $event->max_participant > 0 ? $event->max_participant : "&infin;" !!}
                    <br>
                    <a class="text-decoration-none" data-boundary="window" data-toggle="tooltip" data-html="true" data-placement="top" @if($event->countWaitlist()>0)title="<b>Warteliste:</b><br>@foreach($event->getUsersByParticipation(\App\Buisness\Enum\ParticipationStatusEnum::Waitlist)->getModels() as $participant){{$participant->firstname}} {{$participant->surname}}<br>@endforeach"@endif>{{__("Warteliste: ")}}</a>{{$event->max_participant > 0 ? $event->countWaitlist() : "-" }} |
                    <a class="text-decoration-none" data-boundary="window" data-toggle="tooltip" data-html="true" data-placement="top" @if($event->countCancel()>0)title="<b>Abgesagt:</b><br>@foreach($event->getUsersByParticipation(\App\Buisness\Enum\ParticipationStatusEnum::Canceled)->getModels() as $participant){{$participant->firstname}} {{$participant->surname}}<br>@endforeach"@endif>{{__("Ablehnung: ")}}</a> {{($event->countCancel()) }} |
                    {{__("Anfragen: ").count($event->users) }}</td>
                <td>
                    {{ \Jenssegers\Date\Date::parse($event->date_event_start)->format('D d.m.Y H:i') }} - <br>
                    {{ \Jenssegers\Date\Date::parse($event->date_event_end)->format('D d.m.Y H:i') }}
                </td>
                <td>{{ \Jenssegers\Date\Date::parse($event->date_publication)->format('D d.m.Y') }}</td>
                <td>
                    {{ \Jenssegers\Date\Date::parse($event->date_sign_up_start)->format('D d.m.Y') }} - <br>
                    {{ \Jenssegers\Date\Date::parse($event->date_sign_up_end)->format('D d.m.Y') }}
                </td>
                <td class="text-right">
                    <form action="{{ route('event.destroy', $event) }}" method="post">
                        @csrf
                        <a href="{{ route('eventBookingOverview.edit', $event) }}" data-toggle="tooltip" title="Teilnehmer einteilen">
                            <i style="padding: 5px" class="fas fa-user-check"></i>
                        </a>
                        <a href="{{ route('event.edit', $event) }}" data-toggle="tooltip" title="bearbeiten">
                            <i style="padding: 5px" class="fas fa-edit"></i>
                        </a>
                        @method('delete')
                        <button type="button" style="padding: 5px" class="btn btn-link" onclick="confirm('{{ __("Wollen Sie diese Veranstaltung wirklich löschen?") }}') ? this.parentElement.submit() : ''" data-toggle="tooltip" title="löschen">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">{{__("Derzeit sind keine Veranstaltungen vorhanden.")}}</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="card-footer py-4">
    <nav class="d-flex justify-content-end" aria-label="...">
        {{ $events->links() }}
    </nav>
</div>
