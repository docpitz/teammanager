@extends('layouts.app', ['title' => __('Veranstaltungen')])
@section('content')
    @php
        $promisedDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Promised)->description;
        $waitlistDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Waitlist)->description;
        $quietDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Quiet)->description;
        $canceledDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Canceled)->description;
        $headerPromised = 'Zusage (max. '.$event->max_participant.' Teilnehmer)';
    @endphp
    @component('layouts.headers.auth')
    @endcomponent
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="col-xl-12 order-xl-1">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">{{ __('Teilnehmer einteilen') }}</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="{{ route('event.edit', $event) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit fa-2x"></i>
                                    </a>
                                    <a href="{{ route('event.index') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-level-up-alt fa-2x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            @include('alerts.success')
                            @include('alerts.errors')
                        </div>
                        <div class="card-body">
                        <form method="post" action="{{ route('checkEvent.update', $event) }}" autocomplete="off"
                              enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input type="hidden" name="max_participant" value="{{$event->max_participant}}">
                                <h1>{{$event->name}}</h1>
                                <small><b>Anmeldezeitraum: </b>{{$event->date_sign_up_start->format('d.m.Y')}} bis {{$event->date_sign_up_end->format('d.m.Y')}}</small><br>
                                <small><b>Veranstaltungszeitraum: </b>{{$event->date_event_start->format('d.m.Y H:i')}} bis {{$event->date_event_end->format('d.m.Y H:i').__(' Uhr')}}</small><br><br>
                                <div class="row">
                                    <div class="col-sm-4">
                                        @include('events.participation_status_check', ['header'=> $headerPromised, 'participationDescription' => $promisedDescription, 'users' => old('usersPromised', $usersPromised), 'color' => 'green'])
                                        <br>
                                        @include('events.participation_status_check', ['header'=> 'Warteliste', 'participationDescription' => $waitlistDescription, 'users' => old('usersWaitlist', $usersWaitlist), 'color' => 'blue'])
                                    </div>
                                    <div class="col-sm-4">
                                        @include('events.participation_status_check', ['header'=> 'Absage', 'participationDescription' => $canceledDescription, 'users' => old('usersCanceled', $usersCanceled), 'color' => 'red'])
                                    </div>
                                    <div class="col-sm-4">
                                        @include('events.participation_status_check', ['header'=> 'Keine Antwort', 'participationDescription' => $quietDescription, 'users' => old('usersQuiet', $usersQuiet), 'color' => ''])
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Speichern') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="../../css/sortable.css">
    <link rel="stylesheet" type="text/css" href="../../css/eventcheck.css">
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $( document ).ready(function() {
            calculateHeight();
            calculateHeight();
        });
        $(function () {
            $(".sortableItems").sortable({
                connectWith: ".sortableItems",
                placeholder: "ui-state-highlight",
                forcePlaceholderSize: true,
                cursor: "move",
                delay: 100,
                opacity: 0.85,
                over: function(event, ui) {
                    calculateHeight();
                },
                receive: function( event, ui ) {
                    let searchFor = "#hiddenInput" + ui.item.attr('id');
                    let hiddenInput = $(searchFor);
                    hiddenInput.attr('name', $(this).attr('id') + '[]');
                    calculateHeight();
                }
            });
            $("#{{$promisedDescription}},#{{$waitlistDescription}}, #{{$canceledDescription}}, #{{$quietDescription}}").disableSelection();

        });

        function calculateHeight()
        {
            $("#{{$waitlistDescription}}").css('min-height', '30');

            var heightWaitlistHeader = 60;
            var heightPromisedAndWaitlist = $("#{{$promisedDescription}}").height() + $("#{{$waitlistDescription}}").height() + heightWaitlistHeader;
            var maxHeightComplete = Math.max(heightPromisedAndWaitlist, $("#{{$canceledDescription}}").height() , $("#{{$quietDescription}}").height());

            var paddingBottomPromiseMember = maxHeightComplete - $("#{{$promisedDescription}}").height() - $("#{{$waitlistDescription}}").height() - heightWaitlistHeader;
            var paddingBottomCancelMember = maxHeightComplete - $("#{{$canceledDescription}}").height();
            var paddingBottomQuietMember = maxHeightComplete - $("#{{$quietDescription}}").height();
            $("#{{$promisedDescription}}").css('padding-bottom', paddingBottomPromiseMember + 'px');
            $("#{{$canceledDescription}}").css('padding-bottom', paddingBottomCancelMember + 'px');
            $("#{{$quietDescription}}").css('padding-bottom', paddingBottomQuietMember + 'px');
        }

    </script>
@endpush
