@extends('layouts.app')

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Willkommmen') }}
            @endslot
        @endcomponent
    @endcomponent
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Veranstaltung</h5>
                                <span class="h2 font-weight-bold mb-0">{{$countFutureQuietEvents}} <a href="{{route('myEvent')}}">Veranstaltung(en) ohne Antwort</a></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                    <i class="ni ni-bell-55"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-success mr-2">{{$countFuturePromisedAndWaitlistEvents}}</span>
                            <span class="text-black-50">zukünftige Veranstaltungen zugesagt (inkl. Warteliste)</span>
                        </p>
                        <table class="mt-3">
                            @foreach($futureEvents as $event)
                                <tr class="text-sm">
                                    <td>
                                        <span class="text-black-50">{{\Jenssegers\Date\Date::parse($event->date_event_start)->format("D")}}</span>
                                    </td>
                                    <td>
                                        <span class="text-black-50 ml-1">{{\Jenssegers\Date\Date::parse($event->date_event_start)->format("d.m.Y H:i")}} Uhr</span>
                                    </td>
                                    <td>
                                        @if($event->isPromisedByUser(auth()->user()))
                                            <span class="ml-2 badge badge-pill badge-success"><i class="ni ni-like-2"></i></span>
                                        @else
                                            <span class="ml-2 badge badge-pill badge-primary"><i class="ni ni-send"></i></span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="text-sm">
                                    <td colspan="3" class="pb-2">
                                        <a class="ml-2" href="{{route('showEvent', [$event->id])}}">{{$event->name}}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Hygiene-Konzept</h5>
                                <span class="h2 font-weight-bold mb-0"><a target="_blank" href="https://www.tsv-hofolding.de/admin/files/Tischtennis/Hygienekonzept.pdf">Regeln für die Wiederaufnahme</a></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                    <i class="ni ni-active-40"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-warning mr-2">Bitte zum ersten Training mitbringen</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-md-6">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">DTTB</h5>
                                <span class="h2 font-weight-bold mb-0">Tischtennis-Schutzkonzept als Video</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/74oF3xDR7Mw" allowfullscreen="allowfullscreen"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
