@extends('layouts.app', ['title' => __('Veranstaltungen')])

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
                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled tooltip">
                                    <a href="{{ route('event.index') }}" class="btn btn-sm btn-primary" id="back" title="zurück zur Übersicht" data-toggle="tooltip" data-placement="right" >
                                        <i class="fas fa-level-up-alt fa-2x"></i>
                                    </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h1>{{$event->name}}</h1>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div>
                                        <h3>Zusage</h3>
                                        <ul id="promiseMember" class="droptrue sortableItems bg-green">
                                            @for ($i = 0; $i < 10; $i++)
                                                <li class="ui-state-default" id='i{{$i}}'>
                                                    <div class="row">
                                                        <div class="col-2">
                                                        <span class="avatar avatar-sm rounded-circle">
                                                            <img src="http://i.pravatar.cc/20{{$i}}">
                                                        </span>
                                                        </div>
                                                        <div class="col-10">
                                                            <h4 class="mb--1">Karl Hammer {{$i}}</h4><h6>zugesagt am 18.09.2019 - 20:14</h6>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div>
                                        <h3>Absage</h3>
                                        <ul id="cancelMember" class="droptrue sortableItems bg-red">
                                            @for ($i = 10; $i < 20; $i++)
                                                <li class="ui-state-default" id='i{{$i}}'>
                                                    <div class="row">
                                                        <div class="col-2">
                                                        <span class="avatar avatar-sm rounded-circle">
                                                            <img src="http://i.pravatar.cc/2{{$i}}">
                                                        </span>
                                                        </div>
                                                        <div class="col-10">
                                                            <h4 class="mb--1">Karl Hammer {{$i}}</h4><h6>abgesagt am 18.09.2019 - 20:14</h6>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div>
                                        <h3>Keine Antwort</h3>
                                        <ul id="noAnswerMember" class="droptrue sortableItems bg-yellow">
                                            @for ($i = 20; $i < 30; $i++)
                                                <li class="ui-state-default" id='i{{$i}}'>
                                                    <div class="row">
                                                        <div class="col-2">
                                                        <span class="avatar avatar-sm rounded-circle">
                                                            <img src="http://i.pravatar.cc/2{{$i}}">
                                                        </span>
                                                        </div>
                                                        <div class="col-10">
                                                            <h4 class="mb--1">Karl Hammer {{$i}}</h4><h6>zugesagt: 18.09.2019 - 20:14</h6>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>
                            </div>
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
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $(".back").title="zurück zur Übersicht";
            $(".sortableItems").sortable({
                connectWith: ".sortableItems",
                placeholder: "ui-state-highlight",
                over: function(event, ui) {
                    var maxHeight = Math.max($("#promiseMember").height(), $("#cancelMember").height(), $("#noAnswerMember").height());
                    var paddingBottomPromiseMember = maxHeight - $("#promiseMember").height();
                    var paddingBottomCancelMember = maxHeight - $("#cancelMember").height();
                    var paddingBottomNoAnswerMember = maxHeight - $("#noAnswerMember").height();
                    $("#promiseMember").css('padding-bottom', paddingBottomPromiseMember + 'px');
                    $("#cancelMember").css('padding-bottom', paddingBottomCancelMember + 'px');
                    $("#noAnswerMember").css('padding-bottom', paddingBottomNoAnswerMember + 'px');
                }
            });

            $("#promiseMember, #cancelMember, #noAnswerMember").disableSelection();

        });

    </script>
@endpush