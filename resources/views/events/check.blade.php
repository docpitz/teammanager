@extends('layouts.app', ['title' => __('Veranstaltungen')])

@php
    $promisedDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Promised)->description;
    $quietDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Quiet)->description;
    $canceledDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Canceled)->description
@endphp


@section('content')
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
                                    <a href="{{ route('event.index') }}" class="btn btn-sm btn-primary" id="back">
                                        <i class="fas fa-level-up-alt fa-2x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <form method="post" action="{{ route('checkEvent.update', $event) }}" autocomplete="off"
                              enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <h1>{{$event->name}}</h1>
                                <small><b>Anmeldezeitraum: </b>{{$event->date_sign_up_start->format('d.m.Y')}} bis {{$event->date_sign_up_end->format('d.m.Y')}}</small><br>
                                <small><b>Veranstaltungszeitraum: </b>{{$event->date_event_start->format('d.m.Y H:i')}} bis {{$event->date_event_end->format('d.m.Y H:i').__(' Uhr')}}</small><br><br>
                                <div class="row">
                                    @include('events.participation_status_check', ['participationDescription' => $promisedDescription, 'users' => $usersPromised, 'color' => 'green'])
                                    @include('events.participation_status_check', ['participationDescription' => $canceledDescription, 'users' => $usersCanceled, 'color' => 'red'])
                                    @include('events.participation_status_check', ['participationDescription' => $quietDescription, 'users' => $usersQuiet, 'color' => ''])
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Speichern') }}</button>
                                </div>
                            </div>
                        </form>
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
        calculateHeight();
        $(function () {




            $(".sortableItems").sortable({
                connectWith: ".sortableItems",
                placeholder: "ui-state-highlight",
                over: function(event, ui) {
                    calculateHeight();

                },
                receive: function( event, ui ) {
                    let searchFor = "#hiddenInput" + ui.item.attr('id');
                    let hiddenInput = $(searchFor);
                    hiddenInput.attr('name', $(this).attr('id') + '[]');

                }

            });
            $("#{{$promisedDescription}}, #{{$canceledDescription}}, #{{$quietDescription}}").disableSelection();

        });

        function calculateHeight()
        {
            var maxHeight = Math.max($("#{{$promisedDescription}}").height(), $("#{{$canceledDescription}}").height(), $("#{{$quietDescription}}").height());
            var paddingBottomPromiseMember = maxHeight - $("#{{$promisedDescription}}").height();
            var paddingBottomCancelMember = maxHeight - $("#{{$canceledDescription}}").height();
            var paddingBottomQuietMember = maxHeight - $("#{{$quietDescription}}").height();
            $("#{{$promisedDescription}}").css('padding-bottom', paddingBottomPromiseMember + 'px');
            $("#{{$canceledDescription}}").css('padding-bottom', paddingBottomCancelMember + 'px');
            $("#{{$quietDescription}}").css('padding-bottom', paddingBottomQuietMember + 'px');
        }

    </script>
@endpush
