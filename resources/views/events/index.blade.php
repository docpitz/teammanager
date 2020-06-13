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

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#actual" role="tab" aria-controls="home" aria-selected="true">Aktuell</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#archiv" role="tab" aria-controls="profile" aria-selected="false">Archiv</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="actual" role="tabpanel" aria-labelledby="home-tab">
                            @include('events.index_table', ['events'=> $events])
                        </div>
                        <div class="tab-pane fade" id="archiv" role="tabpanel" aria-labelledby="profile-tab">
                            @include('events.index_table', ['events'=> $eventsOld])
                        </div>
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
