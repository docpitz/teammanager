@extends('layouts.app', ['title' => __('Veranstaltungsverwaltung')])

@section('content')
    @component('layouts.headers.auth')
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Veranstaltung bearbeiten') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('checkEvent.edit', $event) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="right" title="Teilnehmer einteilen">
                                    <i class="fas fa-user-check fa-2x"></i>
                                </a>
                                <a href="{{ route('event.index') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="right" title="zurück zur Übersicht">
                                    <i class="fas fa-level-up-alt fa-2x"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('event.update', $event) }}" autocomplete="off"
                              enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Veranstaltung bearbeiten') }}</h6>
                            <div class="pl-lg-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Veranstaltungsname') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Veranstaltungsname') }}" value="{{ old('name', $event->name) }}" required autofocus>
                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-description">{{ __('Beschreibung') }}</label>
                                    <textarea rows="3" name="description" id="input-description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Beschreibung') }}" >{{ old('description', $event->description) }}</textarea>
                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>
                                <div class="form-group{{ $errors->has('score') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-score">{{ __('Mitmachpunkte') }}</label>
                                    <input type="number" min="0" name="score" id="input-score" class="form-control{{ $errors->has('score') ? ' is-invalid' : '' }}" placeholder="{{ __('Mitmachpunkte') }}" value="{{ old('score', $event->score) }}">
                                    @include('alerts.feedback', ['field' => 'score'])
                                </div>
                                <div class="form-group{{ $errors->has('max_participant') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-max_participant">{{ __('Max. Teilnehmerzahl') }}</label>
                                    <input type="number" min="0" name="max_participant" id="input-max_participant" class="form-control{{ $errors->has('max_participant') ? ' is-invalid' : '' }}" placeholder="{{ __('Max. Teilnehmerzahl') }}" value="{{ old('max_participant', $event->max_participant) }}" required>
                                    @include('alerts.feedback', ['field' => 'max_participant'])
                                </div>
                                <div class="form-group{{ $errors->has('meeting_place') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-meeting_place">{{ __('Treffpunkt') }}</label>
                                    <input type="text" name="meeting_place" id="input-meeting_place" class="form-control{{ $errors->has('meeting_place') ? ' is-invalid' : '' }}" placeholder="{{ __('Treffpunkt') }}" value="{{ old('meeting_place', $event->meeting_place) }}" required>
                                    @include('alerts.feedback', ['field' => 'meeting_place'])
                                </div>
                                <div class="form-group{{ $errors->has('participation_status_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-participation_status_id">{{ __('Voreingestellte Antwort') }}</label>
                                    <select name="participation_status_id" id="input-participation_status_id" class="form-control{{ $errors->has('participation_status_id') ? ' is-invalid' : '' }}" placeholder="{{ __('Voreingestellte Antwort') }}" required>
                                        <option value="">-</option>
                                        @foreach (\App\Buisness\Enum\ParticipationStatusEnum::getInstances() as $participationStatus)
                                            <option value="{{ $participationStatus->value }}" {{ $participationStatus->value == old('participation_status_id', $event->participation_status_id) ? 'selected' : ''}}>{{ $participationStatus->key }} </option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'role_name'])
                                </div>
                                <div class="form-group{{ $errors->has('date_event_range') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-date_event_range">{{ __('Veranstaltungszeitraum') }}</label>
                                    <input type="text" name="date_event_range" id="input-date_event_range" class="form-control{{ $errors->has('date_event_range') ? ' is-invalid' : '' }}" placeholder="{{ __('Veranstaltungszeitraum') }}" value="{{ old('date_event_range', $event->date_event_range) }}" required>
                                    @include('alerts.feedback', ['field' => 'date_event_range'])
                                </div>
                                <div class="form-group{{ $errors->has('date_sign_up_range') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-date_sign_up_rangee">{{ __('Anmeldezeitraum') }}</label>
                                    <input type="text" name="date_sign_up_range" id="input-date_date_sign_up_range" class="form-control{{ $errors->has('date_sign_up_range') ? ' is-invalid' : '' }}" placeholder="{{ __('Anmeldezeitraum') }}" value="{{ old('date_sign_up_range', $event->date_sign_up_range) }}" required>
                                    @include('alerts.feedback', ['field' => 'date_sign_up_range'])
                                </div>
                                <div class="form-group{{ $errors->has('date_publication') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-date_publication">{{ __('Veröffentlichungsdatum') }}</label>
                                    <input type="text" name="date_publication" id="input-date_publication" class="form-control{{ $errors->has('date_publication') ? ' is-invalid' : '' }}" placeholder="{{ __('Veröffentlichungsdatum') }}" value="{{ old('date_publication', $event->date_publication->format('d.m.Y')) }}" required>
                                    @include('alerts.feedback', ['field' => 'date_publication'])
                                </div>
                                <div class="form-group{{ $errors->has('event_users') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-event_user">{{ __('Teilnehmer') }}
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-default"><i class="fas fa-info"></i></button>
                                    </label>
                                    <select multiple="multiple" size="10" name="event_users[]" title="users">
                                        @foreach ($userWithEventInfo as $user)
                                            <option value="{{$user['id']}}" {{ ! is_null(old("event_users")) ? in_array($user['id'], old("event_users")) ? 'selected' : '' : $user['selected'] ? "selected":""}}>
                                                {{$user['surname']}} {{$user['firstname']}} ({{ $user['groups'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
                                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="modal-title-default">{{ __('Suche nach Gruppen') }}</h6>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <p>Innerhalb der Suche können auch Gruppen gesucht werden. Umso eindeutiger die Suche, um so eindeutiger die Ergebnisse</p>
                                                <p>Folgende Gruppen sind derzeit verfügbar:</p>
                                                @foreach(\App\Group::orderBy('name')->get() as $group)
                                                    {{$group['name']}}<br>
                                                @endforeach
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">{{ __('Schließen') }}</button>
                                            </div>
                                        </div>
                                    </div>
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

@include('groups.duallistbox', ['name_of_select_multiple' => 'event_users'])

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $('input[name="date_event_range"]').daterangepicker({
            @if(old('date_event_range', $event['date_event_range']))
            startDate: moment('{{ explode(' - ',old('date_event_range', $event['date_event_range']))[0] }}','DD.MM.YYYY HH:mm'),
            endDate: moment('{{ explode(' - ',old('date_event_range', $event['date_event_range']))[1] }}','DD.MM.YYYY HH:mm'),
            @endif
            timePicker: true,
            timePicker24Hour: true,
            drops: "up",
            showISOWeekNumbers: true,
            timePickerIncrement: 15,
            autoUpdateInput: false,
            locale: {
                "format": "DD.MM.YYYY HH:mm",
                "separator": " bis ",
                "applyLabel": "Annehmen",
                "cancelLabel": "Löschen",
                "fromLabel": "von",
                "toLabel": "bis",
                "daysOfWeek": [
                    "So",
                    "Mo",
                    "Di",
                    "Mi",
                    "Do",
                    "Fr",
                    "Sa"
                ],
            }
        });

        $('input[name="date_event_range"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD.MM.YYYY HH:mm') + ' - ' + picker.endDate.format('DD.MM.YYYY HH:mm'));
        });

        $('input[name="date_event_range"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('input[name="date_sign_up_range"]').daterangepicker({
            @if(old('date_sign_up_range', $event['date_sign_up_range']))
            startDate: moment('{{ explode(' - ',old('date_sign_up_range', $event['date_sign_up_range']))[0] }}','DD.MM.YYYY'),
            endDate: moment('{{ explode(' - ',old('date_sign_up_range', $event['date_sign_up_range']))[1] }}','DD.MM.YYYY'),
            @endif
            drops: "up",
            showISOWeekNumbers: true,
            autoUpdateInput: false,
            locale: {
                "format": "DD.MM.YYYY",
                "separator": " bis ",
                "applyLabel": "Annehmen",
                "cancelLabel": "Löschen",
                "fromLabel": "von",
                "toLabel": "bis",
                "daysOfWeek": [
                    "So",
                    "Mo",
                    "Di",
                    "Mi",
                    "Do",
                    "Fr",
                    "Sa"
                ],
            }
        });

        $('input[name="date_sign_up_range"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
        });

        $('input[name="date_event_range"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('input[name="date_publication"]').daterangepicker({
            drops: "up",
            showISOWeekNumbers: true,
            singleDatePicker: true,
            autoUpdateInput: false,
            locale: {
                "format": "DD.MM.YYYY",
                "separator": " bis ",
                "applyLabel": "Annehmen",
                "cancelLabel": "Löschen",
                "fromLabel": "von",
                "toLabel": "bis",
                "daysOfWeek": [
                    "So",
                    "Mo",
                    "Di",
                    "Mi",
                    "Do",
                    "Fr",
                    "Sa"
                ],
            }
        });
        $('input[name="date_publication"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD.MM.YYYY'));
        });

        $('input[name="date_publication"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    </script>

@endpush


