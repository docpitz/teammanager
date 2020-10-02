@extends('layouts.app', ['title' => __('Veranstaltungen')])
@section('content')
    @php
        $promisedDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Promised)->description;
        $waitlistDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Waitlist)->description;
        $quietDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Quiet)->description;
        $canceledDescription = \App\Buisness\Enum\ParticipationStatusEnum::getInstance(\App\Buisness\Enum\ParticipationStatusEnum::Canceled)->description;
        $headerPromised = $event->max_participant > 0 ? 'Zusage (max. '.$event->max_participant.' Teilnehmer)' : 'Zusage';
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
                        <form method="post" action="{{ route('eventBookingOverview.update', $event) }}" autocomplete="off"
                              enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input type="hidden" name="max_participant" value="{{$event->max_participant}}">
                                <h1>{{$event->name}}</h1>
                                <small><b>Anmeldezeitraum: </b>{{$event->date_sign_up_start->format('d.m.Y')}} bis {{$event->date_sign_up_end->format('d.m.Y')}}</small><br>
                                <small><b>Veranstaltungszeitraum: </b>{{$event->date_event_start->format('d.m.Y H:i')}} bis {{$event->date_event_end->format('d.m.Y H:i').__(' Uhr')}}</small><br><br>
                                <fieldset id="fieldset">
                                    <div class="form-group{{ $errors->has('event_responsible') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-event_responsible">{{ __('Verantwortliche') }}</label>
                                        <input name="event_responsible"  id="input-event_responsible" placeholder="{{ __('Verantwortliche') }}">
                                        <div class="text-center">
                                            <button onclick="ajaxCall({{$event->id}},false)" type="button" class="btn btn-sm btn-success mt-4"><i id="ajaxSave" class="fas fa-spinner fa-spin d-none"></i><i id="noAjaxSave" class="fas fa-save"></i> {{ __('Verantw. speichern') }}</button>
                                            <button onclick="ajaxCall({{$event->id}},true)" type="button" class="btn btn-sm btn-success mt-4"><i id="ajaxEmail" class="fas fa-spinner fa-spin d-none"></i><i id="noAjaxEmail" class="fas fa-envelope"></i> {{ __('Verantw. speichern & eMail senden') }}</button>
                                        </div>
                                        @include('alerts.feedback', ['field' => 'event_responsible'])
                                    </div>
                                </fieldset>

                                <div class="row">
                                    <div class="col-sm-4">
                                        @include('events.booking_overview_participation_status', ['header'=> $headerPromised, 'participationDescription' => $promisedDescription, 'users' => old('usersPromised', $usersPromised), 'color' => 'green'])
                                        <br>
                                        @include('events.booking_overview_participation_status', ['header'=> 'Warteliste', 'participationDescription' => $waitlistDescription, 'users' => old('usersWaitlist', $usersWaitlist), 'color' => 'blue'])
                                    </div>
                                    <div class="col-sm-4">
                                        @include('events.booking_overview_participation_status', ['header'=> 'Absage', 'participationDescription' => $canceledDescription, 'users' => old('usersCanceled', $usersCanceled), 'color' => 'red'])
                                    </div>
                                    <div class="col-sm-4">
                                        @include('events.booking_overview_participation_status', ['header'=> 'Keine Antwort', 'participationDescription' => $quietDescription, 'users' => old('usersQuiet', $usersQuiet), 'color' => ''])
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
    <link rel="stylesheet" type="text/css" href="../../css/event_booking_overview.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css')."/tagify.css" }}"/>
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{ asset('js')."/tagify.js"}}"></script>
    <script>
        var input = document.querySelector('input[name="event_responsible"]'),
            // init Tagify script on the above inputs
            tagify = new Tagify(input, {
                whitelist: [
                        @foreach(\App\User::allUserSorted()->get() as $user)
                    {'value':'{{$user->firstname}} {{$user->surname}}','data-id':'{{$user->id}}'},
                    @endforeach
                ],
                enforceWhitelist: true,
                loading: true,
                editTags: false,
                dropdown: {
                    maxItems: 5,           // <- mixumum allowed rendered suggestions
                    classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
                    enabled: 0,             // <- show suggestions on focus
                    closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
                }
            })


        $( document ).ready(function() {
            calculateHeight();
            calculateHeight();
            addTagify();
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

        function addTagify()
        {
            tagify.addTags({!! old('event_responsible', json_encode($event_responsible)) !!})
        }

        function beforeAjaxCall() {
            tagify.loading(true);
            $("#fieldset").attr('disabled', 'disabled');
            $("#ajaxSave").removeClass('d-none');
            $("#ajaxEmail").removeClass('d-none');
            $("#noAjaxSave").addClass('d-none');
            $("#noAjaxEmail").addClass('d-none');
        }

        function afterAjaxCall() {
            tagify.loading(false);
            $("#fieldset").removeAttr('disabled');
            $("#ajaxSave").addClass('d-none');
            $("#ajaxEmail").addClass('d-none');
            $("#noAjaxSave").removeClass('d-none');
            $("#noAjaxEmail").removeClass('d-none');
        }


        function ajaxCall(eventId, sendMail) {
            beforeAjaxCall();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: '{{ url("/eventBookingOverview") }}/'+eventId+'/ajax',
                method: 'post',
                data: {
                    id: eventId,
                    sendMail: sendMail,
                    responsibles:$('#input-event_responsible').val()
                },
                success: function(result){
                    afterAjaxCall();
                }
            });
        };

    </script>
@endpush
