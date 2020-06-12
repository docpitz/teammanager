@extends('layouts.app', ['title' => __('Veranstaltungen')])

@section('content')
    @component('layouts.headers.auth')
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Veranstaltungen') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('event.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="right" title="neue Veranstaltung hinzufügen">
                                    <i class="fas fa-plus-circle text-white fa-2x"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Teilnehmer') }}</th>
                                <th scope="col">{{ __('Veranstaltungsbeginn') }}</th>
                                <th scope="col">{{ __('Veröffentlichung') }}</th>
                                <th scope="col">{{ __('Anmeldeschluss') }}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($events as $event)
                                <tr>
                                    <td>{{ $event->name }}</td>
                                    <td><a class="text-decoration-none" data-toggle="tooltip" data-html="true" data-placement="top" @if($event->countPromise()>0)title="<b>Angemeldet:</b><br>@foreach($event->getUsersByParticipation(\App\Buisness\Enum\ParticipationStatusEnum::Promised)->getModels() as $participant){{$participant->firstname}} {{$participant->surname}}<br>@endforeach"@endif>{{__("Teilnehmer: ")}}</a>{{$event->countPromise()}} /
                                        {{ $event->max_participant > 0 ? $event->max_participant : "∞" }}
                                        <br>
                                        <a class="text-decoration-none" data-toggle="tooltip" data-html="true" data-placement="top" @if($event->countWaitlist()>0)title="<b>Warteliste:</b><br>@foreach($event->getUsersByParticipation(\App\Buisness\Enum\ParticipationStatusEnum::Waitlist)->getModels() as $participant){{$participant->firstname}} {{$participant->surname}}<br>@endforeach"@endif>{{__("Warteliste: ")}}</a>{{$event->max_participant > 0 ? $event->countWaitlist() : "-" }} &#124;
                                        <a class="text-decoration-none" data-toggle="tooltip" data-html="true" data-placement="top" @if($event->countCancel()>0)title="<b>Abgesagt:</b><br>@foreach($event->getUsersByParticipation(\App\Buisness\Enum\ParticipationStatusEnum::Canceled)->getModels() as $participant){{$participant->firstname}} {{$participant->surname}}<br>@endforeach"@endif>{{__("Ablehnung: ")}}</a> {{($event->countCancel()) }} &#124;
                                        {{__("Anfragen: ").count($event->users) }}</td>
                                    <td>{{ \Jenssegers\Date\Date::parse($event->date_event_start)->format('D d.m.Y H:i') }}</td>
                                    <td>{{ \Jenssegers\Date\Date::parse($event->date_publication)->format('D d.m.Y') }}</td>
                                    <td>{{ \Jenssegers\Date\Date::parse($event->date_sign_up_end)->format('D d.m.Y') }}</td>
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
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css') }}/text-decoration.css">
@endpush
