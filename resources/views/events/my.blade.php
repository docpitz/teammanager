@extends('layouts.app')
@section('content')
    @component('layouts.headers.auth')
    @endcomponent
    <div class="container-fluid mt--6 ">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card pd--5">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h2 class="normal">Meine Veranstaltungen</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($events) == 0)
                            <h4>Derzeit sind keine Veranstaltungen geplant.</h4>
                        @else
                            <div id="carouselEventIndicators" class="carousel slide" data-ride="carousel" data-interval="false">
                                <ol class="carousel-indicators">
                                    @foreach($events as $indexKey => $event)
                                        <li id="indicator{{$event->id}}" data-target="#carouselEventIndicators" data-slide-to="{{$indexKey}}" class="{{$indexKey == 0 ? 'active ': ' '}} {{ Illuminate\Support\Str::lower(\App\Buisness\Enum\ParticipationStatusEnum::getDescription($event->getParticipationState(auth()->user()))) }}"></li>
                                    @endforeach
                                </ol>
                                <div class="carousel-inner pb-6">
                                    @foreach($events as $indexKey => $event)
                                        <div class="modal fade" id="cancelDialog{{$event->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelDialogModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="cancelDialogModalLongTitle">Achtung</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Es befinden sich weitere Personen auf der Warteliste. Durch deine Absage wird dein Platz frei bzw. wirst du aus der Warteliste entfernt und anschließend automatisch anderweitig vergeben. <br><br>Willst du wirklich absagen?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="onClickCanceled({{$event->id}}, true)">Absagen</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="item{{$event->id}}" class="carousel-item {{$indexKey == 0 ? 'active': ''}} align-content-center">

                                                <fieldset id="fieldset{{$event->id}}">
                                                    <div >
                                                        <!-- Card image -->
                                                        <div style="position: relative; text-align: center;color: white;">
                                                            <img class="card-img" src="{{ asset('hofolding') }}/tt-event.jpg" alt="Veranstaltungsbild">
                                                            <div style="position: absolute; bottom: 15px; right: -20px;">
                                                                <h3 id="stamp{{$event->id}}" class="{{ $event->isCanceledByUser(auth()->user()) || $event->hasQuietByUser(auth()->user()) ? 'd-none ' : ''}} stamp-text">
                                                                    <div class="{{$event->getHideParticipationState(auth()->user()) == \App\Buisness\Enum\ParticipationStatusEnum::Promised ? ' d-none ' : ''}}" id="stamp{{$event->id}}promised">{{__('Angemeldet')}}</div>
                                                                    <div class="{{$event->getHideParticipationState(auth()->user()) == \App\Buisness\Enum\ParticipationStatusEnum::Waitlist ? ' d-none ' : ''}}" id="stamp{{$event->id}}waitlist">{{__('Warteliste')}}</div>
                                                                </h3>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="text-right">
                                                            <small class="text-muted">{{!empty($event->score) ? $event->score.__(' TWM-Points - ') : ''}}<span id="countPromises{{$event->id}}">{{$event->countPromise()}}</span>{{__(' von ')}}{!! $event->max_participant == 0 ? "&infin;" : __('max. ').$event->max_participant !!}{{__(' Teilnehmer')}}</small>
                                                            </div>
                                                            <div class="text-center">
                                                                <span class="h1 text-center text-uppercase mb-0">{{$event->name}}</span>
                                                            </div>


                                                            <div class="row mt--2">
                                                                <div class="col-md-4">
                                                                    <div class="card-profile-stats d-flex justify-content-center mb--5">
                                                                        <div>
                                                                            <span class="heading">@include("helpers.timeperiod", ["fromDate" => $event->date_event_start, "toDate" => $event->date_event_end, "formatDate" => "D, d.m.Y", "formatTime" => "H:i"])</span>
                                                                            <span class="description">Veranstaltungsdatum</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="card-profile-stats d-flex justify-content-center mb--5">
                                                                        <div>
                                                                            <span class="heading">{{$event->meeting_place}}</span>
                                                                            <span class="description">Veranstaltungsort / Treffpunkt</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="card-profile-stats d-flex justify-content-center mb--5">
                                                                        <div>
                                                                            <span class="heading">{{$event->date_sign_up_end->format("D, d.m.Y")}}</span>
                                                                            <span class="description">Anmeldeschluss</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if(!empty($event->description))
                                                            <p class="mt-5 text-justify">
                                                                <h4>Details</h4>
                                                                <p class="description text-justify">{{$event->description}}</p>
                                                            </p>
                                                            @endif
                                                            @if($event->booking_possible)
                                                                <div class="btn-group center mt-5 pb-4 ">
                                                                    <button onclick="onClickPromised({{$event->id}})" type="button" class="btn save_button btn-outline-success{{$event->isPromisedByUser(auth()->user()) ? ' active' : ''}}{{$event->getHideParticipationState(auth()->user()) == \App\Buisness\Enum\ParticipationStatusEnum::Promised ? ' d-none ' : ''}}" id="promised{{$event->id}}"><i id="ajaxLoadPromised{{$event->id}}" class="fas fa-spinner fa-spin d-none"></i><div id="noAjaxPromised{{$event->id}}">{{ __('Teilnehmen') }}</div></button>
                                                                    <button onclick="onClickWaitlist({{$event->id}})" type="button" class="btn save_button btn-outline-info{{$event->isWaitlistByUser(auth()->user()) ? ' active' : ''}}{{$event->getHideParticipationState(auth()->user()) == \App\Buisness\Enum\ParticipationStatusEnum::Waitlist ? ' d-none ' : ''}}" id="waitlist{{$event->id}}"><i id="ajaxLoadWaitlist{{$event->id}}" class="fas fa-spinner fa-spin d-none"></i><div id="noAjaxWaitlist{{$event->id}}">{{ __('Warteliste') }}</div></button>
                                                                    <button onclick="onClickCanceled({{$event->id}}, false)" type="button" class="btn save_button btn-outline-danger{{$event->isCanceledByUser(auth()->user()) ? ' active' : ''}}" id="canceled{{$event->id}}"><i id="ajaxLoadCanceled{{$event->id}}" class="fas fa-spinner fa-spin d-none"></i><div id="noAjaxCanceled{{$event->id}}">{{ __('Absagen') }}</div></button>
                                                                </div>
                                                                @else
                                                                    <div class="text-center mt-5">
                                                                    @if($event->isPromisedByUser(auth()->user()))
                                                                        <button type="button" class="btn save_button btn-success" disabled="disabled">{{ __('Zugesagt') }}</button>
                                                                    @elseif($event->isCanceledByUser(auth()->user()))
                                                                        <button type="button" class="btn save_button btn-danger" disabled="disabled">{{ __('Abgesagt') }}</button>
                                                                    @else
                                                                        <button type="button" class="btn save_button btn-dark" disabled="disabled">{{ __('Ohne Antwort') }}</button>
                                                                    @endif
                                                                        <br><h5>Anmeldezeitraum zwischen {{$event->date_sign_up_start->format('d.m.Y')}} und {{$event->date_sign_up_end->format('d.m.Y')}}</h5>
                                                                    </div>
                                                                @endIf

                                                        </div>
                                                    </div>
                                                </fieldset>

                                        </div>
                                    @endforeach
                                </div>
                                @if(count($events) > 1)
                                    <a class="carousel-control-prev" href="#carouselEventIndicators" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselEventIndicators" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="../../js/jquery.bcswipe.js"></script>
    <script>
        $(document).ready(function(){
            let eventIds = [
                @foreach($events as $indexKey => $event)
                '{{$event->id}}',
                @endforeach
            ];
            let positionInCarousel = eventIds.indexOf(window.location.href.split("/").pop());
            if(positionInCarousel != -1) {
                $('#carouselEventIndicators').carousel(positionInCarousel);
            }
            $('#carouselEventIndicators').bcSwipe({ threshold: 50 });
            recalculateButtons();

            $('#carouselEventIndicators').on('slid.bs.carousel', function () {
                recalculateButtons();
            });
        });

        function onClickPromised(eventId){
            beforeAjaxCall(eventId);
            ajaxCall(eventId, 'Promised', false);
        };
        function onClickWaitlist(eventId){
            beforeAjaxCall(eventId);
            ajaxCall(eventId, 'Waitlist', false);
        };
        function onClickCanceled(eventId, confirmDelete){
            beforeAjaxCall(eventId);
            ajaxCall(eventId, 'Canceled', confirmDelete);
        };

        function recalculateButtons() {
            $('.btn-group').has('.btn:hidden').find('.btn').css('border-radius', 0);
            $('.btn-group').has('.btn:hidden').find('.btn:visible:first').css({
                'border-top-left-radius': '4px',
                'border-bottom-left-radius': '4px',
            });
            $('.btn-group').has('.btn:hidden').find('.btn:visible:last').css({
                'border-top-right-radius': '4px',
                'border-bottom-right-radius': '4px',
            });
        }

        function beforeAjaxCall(eventId) {
            $("#fieldset"+eventId).attr('disabled', 'disabled');
            $("#ajaxLoadPromised"+eventId).removeClass('d-none');
            $("#ajaxLoadWaitlist"+eventId).removeClass('d-none');
            $("#ajaxLoadCanceled"+eventId).removeClass('d-none');
            $("#noAjaxPromised"+eventId).addClass('d-none');
            $("#noAjaxWaitlist"+eventId).addClass('d-none');
            $("#noAjaxCanceled"+eventId).addClass('d-none');
        }

        function afterAjaxCall(eventId) {
            $("#fieldset"+eventId).removeAttr('disabled');
            $("#ajaxLoadPromised"+eventId).addClass('d-none');
            $("#ajaxLoadCanceled"+eventId).addClass('d-none');
            $("#ajaxLoadWaitlist"+eventId).addClass('d-none');
            $("#noAjaxPromised"+eventId).removeClass('d-none');
            $("#noAjaxCanceled"+eventId).removeClass('d-none');
            $("#noAjaxWaitlist"+eventId).removeClass('d-none');
        }

        function recalculateEvent(eventId, method, hideMethod) {
            $("#canceled"+eventId).removeClass("active");
            $("#waitlist"+eventId).removeClass("active");
            $("#promised"+eventId).removeClass("active");
            $("#"+method+eventId).addClass("active");

            $("#waitlist"+eventId).removeClass("d-none");
            $("#promised"+eventId).removeClass("d-none");
            $("#"+hideMethod+eventId).addClass("d-none");

            $("#indicator"+eventId).removeClass('canceled');
            $("#indicator"+eventId).removeClass('waitlist');
            $("#indicator"+eventId).removeClass('promised');
            $("#indicator"+eventId).addClass(method);

            $("#stamp"+eventId+"promised").removeClass("d-none");
            $("#stamp"+eventId+"waitlist").removeClass("d-none");
            $("#stamp"+eventId+hideMethod).addClass("d-none");
        }

        function animationAfterAjaxCall(eventId, method, animation) {
            $("#stamp"+eventId).removeClass().addClass('stamp-text ' + animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                var stampClass = 'stamp-text';
                if(method == 'canceled') {
                    stampClass = stampClass + ' d-none';
                }
                $(this).removeClass().addClass(stampClass);
            });
        }

        function updateCountPromises(eventId, countPromises) {
            $('#countPromises'+eventId).text(countPromises);
        }

        function updateCountQuiet(countQuiet) {
            if(countQuiet > 0) {
                $('#badge_quiet').removeClass('d-none');
                $('#badge_quiet').text(countQuiet);
            }
            else {
                $('#badge_quiet').addClass('d-none');
            }
        }

        function ajaxCall(eventId, method, confirmDelete){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: '{{ url("/myEvent") }}/'+eventId+'/ajax'+method+'/',
                method: 'post',
                data: {
                    id: eventId,
                    confirmDelete: confirmDelete,
                },
                success: function(result){
                    afterAjaxCall(eventId)
                    updateCountPromises(eventId, result.countPromises);
                    updateCountQuiet(result.countQuiet);
                    if(result.isConsulationNecessaryByCanceled) {
                        $('#cancelDialog'+eventId).modal()
                    }
                    else if(result.promised) {
                        recalculateEvent(eventId, 'promised', result.hideParticipationStatus);
                        if (result.success) {
                            animationAfterAjaxCall(eventId, 'promised', 'heartBeat');
                        }
                    }
                    else if(result.waitlist) {
                        recalculateEvent(eventId, 'waitlist', result.hideParticipationStatus, 'heartBeat');
                        if(result.success) {
                            animationAfterAjaxCall(eventId, 'waitlist', 'heartBeat');
                        }
                    }
                    else if(result.canceled) {
                        recalculateEvent(eventId, 'canceled', result.hideParticipationStatus, 'medium flash');
                        if(result.success) {
                            animationAfterAjaxCall(eventId, 'canceled', 'medium flash');
                        }
                    }
                    recalculateButtons();
                }});
        };
    </script>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css') }}/animate.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css') }}/event_my.css">
@endpush
