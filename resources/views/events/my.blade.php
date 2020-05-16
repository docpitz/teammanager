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
                                        <li id="indicator{{$event->id}}" data-target="#carouselEventIndicators" data-slide-to="{{$indexKey}}" class="{{$indexKey == 0 ? 'active ': ' '}} {{$event->isPromisedByUser(auth()->user()) ? 'promise' : ($event->isCanceledByUser(auth()->user()) ? 'cancel' : '') }}"></li>
                                    @endforeach
                                </ol>
                                <div class="carousel-inner pb-6">
                                    @foreach($events as $indexKey => $event)
                                        <div class="carousel-item {{$indexKey == 0 ? 'active': ''}} align-content-center">
                                            <div class="col-lg-12">
                                                <fieldset id="fieldset{{$event->id}}">
                                                    <div >
                                                        <!-- Card image -->
                                                        <div style="position: relative; text-align: center;color: white;">
                                                            <img class="card-img" src="{{ asset('hofolding') }}/tt-event.jpg" alt="Image placeholder">
                                                            <div style="position: absolute; bottom: 15px; right: -20px;">
                                                                <h3 id="stamp{{$event->id}}" class="{{! $event->isPromisedByUser(auth()->user()) ? 'd-none ' : ''}} stamp-text">{{__('Angemeldet')}}</h3>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="row align-items-center ">
                                                                <div class="col-8">
                                                                    <h5 class="h2 card-title mb-0">{{$event->name}}</h5>
                                                                    <small class="text-muted">{{__('Veröffentlichung: ').$event->date_publication->format('d.m.Y').__(' - Anmeldeschluss: ').$event->date_sign_up_end->format('d.m.Y')}}</small>
                                                                </div>
                                                                <div class="col-4 text-right">
                                                                    <h5 class="h2 card-title mb-0">&nbsp;</h5>
                                                                    <small class="text-muted">{{$event->score.__(' TWM-Points - ')}}<span id="countPromises{{$event->id}}">{{$event->countPromise()}}</span>{{__(' von max. ').$event->max_participant.__(' Teilnehmer')}}</small>
                                                                </div>
                                                            </div>
                                                            <div class="row mt--2">
                                                                <div class="col">
                                                                    <div class="card-profile-stats d-flex justify-content-center mb--5">
                                                                        <div>
                                                                            <span class="heading">{{$event->date_event_start->format('d.m.Y H:i').__(' Uhr')}}</span>
                                                                            <span class="description">Start</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="card-profile-stats d-flex justify-content-center mb--5">
                                                                        <div>
                                                                            <span class="heading">{{$event->date_event_end->format('d.m.Y H:i').__(' Uhr')}}</span>
                                                                            <span class="description">Ende</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="card-profile-stats d-flex justify-content-center mb--5">
                                                                        <div>
                                                                            <span class="heading">{{$event->meeting_place}}</span>
                                                                            <span class="description">Treffpunkt</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="card-text mt-4">{{$event->description}}</p>

                                                                @if($event->booking_possible)
                                                                <div class="btn-group center mt-3 pb-4">
                                                                    <button onclick="onClickPromise({{$event->id}})" type="button" class="btn save_button btn-outline-success{{$event->isPromisedByUser(auth()->user()) ? ' active' : ''}}" id="promise{{$event->id}}"><i id="ajaxLoadPromise{{$event->id}}" class="fas fa-spinner fa-spin d-none"></i><div id="noAjaxPromise{{$event->id}}">{{ __('Teilnehmen') }}</div></button>
                                                                    <button onclick="onClickCancel({{$event->id}})" type="button" class="btn save_button btn-outline-danger{{$event->isCanceledByUser(auth()->user()) ? ' active' : ''}}" id="cancel{{$event->id}}"><i id="ajaxLoadCancel{{$event->id}}" class="fas fa-spinner fa-spin d-none"></i><div id="noAjaxCancel{{$event->id}}">{{ __('Absagen') }}</div></button>
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
        $('#carouselEventIndicators').bcSwipe({ threshold: 50 });

        function onClickPromise(eventId){
            beforeAjaxCall(eventId);
            ajaxCall(eventId, 'Promise');
        };
        function onClickCancel(eventId){
            beforeAjaxCall(eventId);
            ajaxCall(eventId, 'Cancel');
        };

        function beforeAjaxCall(eventId) {
            $("#fieldset"+eventId).attr('disabled', 'disabled');
            $("#ajaxLoadPromise"+eventId).removeClass('d-none');
            $("#ajaxLoadCancel"+eventId).removeClass('d-none');
            $("#noAjaxPromise"+eventId).addClass('d-none');
            $("#noAjaxCancel"+eventId).addClass('d-none');
        }

        function afterAjaxCall(eventId) {
            $("#fieldset"+eventId).removeAttr('disabled');
            $("#ajaxLoadPromise"+eventId).addClass('d-none');
            $("#ajaxLoadCancel"+eventId).addClass('d-none');
            $("#noAjaxPromise"+eventId).removeClass('d-none');
            $("#noAjaxCancel"+eventId).removeClass('d-none');
        }

        function animationAfterAjaxCall(eventId, method, theOtherMethod, animation) {
            $("#"+theOtherMethod+eventId).removeClass("active");
            $("#"+method+eventId).addClass("active");
            $("#indicator"+eventId).removeClass(theOtherMethod);
            $("#indicator"+eventId).addClass(method);
            $("#stamp"+eventId).removeClass().addClass('stamp-text ' + animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                var stampClass = 'stamp-text';
                if(method == 'cancel') {
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

        function ajaxCall(eventId, method){
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
                },
                success: function(result){
                    afterAjaxCall(eventId)
                    if(result.success) {
                        updateCountPromises(eventId, result.countPromises);
                        updateCountQuiet(result.countQuiet);
                        if(result.promise) {
                            animationAfterAjaxCall(eventId, 'promise', 'cancel', 'heartBeat');
                        }
                        else if(result.cancel) {
                            animationAfterAjaxCall(eventId, 'cancel', 'promise', 'medium flash');
                        }
                    }
                }});
        };
    </script>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css') }}/animate.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css') }}/myevent.css">
@endpush
