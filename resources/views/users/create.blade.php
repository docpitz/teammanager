@extends('layouts.app', ['title' => __('Benutzerverwaltung')])

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
                                <h3 class="mb-0">{{ __('Teammitglied anlegen') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="right" title="zurück zur Übersicht">
                                    <i class="fas fa-level-up-alt fa-2x"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('user.store') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <h6 class="heading-small text-muted mb-4">{{ __('Neues Teammitglied') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Benutzername') }}</label>
                                    <input type="text" name="username" id="input-name" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="{{ __('Benutzername') }}" value="{{ old('username') }}" required autofocus autocomplete="off">

                                    @include('alerts.feedback', ['field' => 'username'])
                                </div>
                                <div class="form-group{{ $errors->has('firstname') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Vorname') }}</label>
                                    <input type="text" name="firstname" id="input-name" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" placeholder="{{ __('Vorname') }}" value="{{ old('firstname') }}" required autocomplete="off">

                                    @include('alerts.feedback', ['field' => 'firstname'])
                                </div>
                                <div class="form-group{{ $errors->has('surname') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Nachname') }}</label>
                                    <input type="text" name="surname" id="input-name" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" placeholder="{{ __('Nachname') }}" value="{{ old('surname') }}" required autocomplete="off">

                                    @include('alerts.feedback', ['field' => 'surname'])
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('E-Mail-Adresse') }}</label>
                                    <input type="email" name="email" id="input-email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('E-Mail') }}" value="{{ old('email') }}" required autocomplete="off">

                                    @include('alerts.feedback', ['field' => 'email'])
                                </div>
                                <div class="form-group{{ $errors->has('email_optional') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email_optional">{{ __('Weitere E-Mail-Adresse') }}</label>
                                    <input type="email" name="email_optional" id="input-email_optional" class="form-control{{ $errors->has('email_optional') ? ' is-invalid' : '' }}" placeholder="{{ __('Weitere E-Mail-Adresse') }}" value="{{ old('email_optional') }}" autocomplete="off">

                                    @include('alerts.feedback', ['field' => 'email'])
                                </div>
                                <div class="form-group{{ $errors->has('role_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-role">{{ __('Role') }}</label>
                                    <select name="role_name" id="input-role" class="form-control{{ $errors->has('role_name') ? ' is-invalid' : '' }}" placeholder="{{ __('Role') }}" required>
                                        <option value="">-</option>
                                        @foreach (\App\Buisness\Enum\RoleEnum::getInstances() as $role)
                                            <option value="{{ $role->key }}" {{ $role->key == old('role_name') ? 'selected' : ''}}>{{ $role->getFormattedName() }} </option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'role_name'])
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">{{ __('Passwort') }}</label>
                                    <input type="password" name="password" id="input-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Passwort') }}" value="" required autocomplete="off">

                                    @include('alerts.feedback', ['field' => 'password'])
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">{{ __('Passwort wiederholen') }}</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control" placeholder="{{ __('Passwort wiederholen') }}" value="" required autocomplete="off">
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
