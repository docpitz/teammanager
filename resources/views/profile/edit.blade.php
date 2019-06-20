@extends('layouts.app', ['title' => __('Spielerprofil'), 'navClass' => 'bg-default'])

@section('content')
    @include('forms.header', [
        'title' => __('Hallo') . ' '. auth()->user()->name,
        'description' => __('Das ist deine Profilseite. Wir werden sie in der nächsten Zeit weiter ausbauen.'),
        'class' => 'col-lg-9'
    ])

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-lg-6">
                <div class="card bg-gradient-info border-0">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-2 text-white">Trainingseifer (COMMING SOON)</h5>
                                <i class="fas fa-star fa-2x text-white"></i><i class="fas fa-star fa-2x text-white"></i><i class="fas fa-star fa-2x text-white"></i><i class="fas fa-star fa-2x text-white"></i><i class="far fa-star fa-2x text-white"></i>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                    <i class="ni ni-active-40"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-white mr-2"><i class="fa fa-arrow-up"></i> +18 %</span>
                            <span class="text-nowrap text-light">Im Vergleich zum letzten Jahr</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card bg-gradient-danger border-0">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-2 text-white">TTR-Punkte (COMMING SOON)</h5>
                                <span class="text-xl font-weight-bold mb-0 text-white">1150</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                    <i class="ni ni-spaceship"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <span class="text-white mr-2"><i class="fa fa-arrow-up"></i> +120 TTR-Punkte</span>
                            <span class="text-nowrap text-light">Im Vergleich zu letzten Jahr</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Spielerprofil editieren') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Spielerprofil') }}</h6>

                            @include('alerts.success')
                            @include('alerts.error_self_update', ['key' => 'not_allow_profile'])

                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-username">{{ __('Benutzername') }}</label>
                                    <input type="text" name="username" id="input-username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="{{ __('Benutzername') }}" value="{{ old('username', auth()->user()->username) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'username'])
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-fistname">{{ __('Vorname') }}</label>
                                    <input disabled="disabled" type="text" id="input-firstname" class="form-control" value="{{ auth()->user()->firstname }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-surname">{{ __('Nachname') }}</label>
                                    <input disabled="disabled" type="text" id="input-surname" class="form-control"  value="{{ auth()->user()->surname }}" >
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('E-Mail-Adresse') }}</label>
                                    <input type="email" name="email" id="input-email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('E-Mail-Adresse') }}" value="{{ old('email', auth()->user()->email) }}" required>

                                    @include('alerts.feedback', ['field' => 'email'])
                                </div>
                                <div class="form-group{{ $errors->has('email_optional') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email_optional">{{ __('Weitere E-Mail') }}</label>
                                    <input type="email" name="email_optional" id="input-email_optional" class="form-control{{ $errors->has('email_optional') ? ' is-invalid' : '' }}" placeholder="{{ __('Weitere E-Mail-Adresse') }}" value="{{ old('email_optional', auth()->user()->email_optional) }}">

                                    @include('alerts.feedback', ['field' => 'email_optional'])
                                </div>

                                <!--
                                <div class="form-group{{ $errors->has('photo') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Profile photo') }}</label>
                                    <div class="custom-file">
                                        <input type="file" name="photo" class="custom-file-input{{ $errors->has('photo') ? ' is-invalid' : '' }}" id="input-picture" accept="image/*">
                                        <label class="custom-file-label" for="input-picture">{{ __('Select profile photo') }}</label>
                                    </div>

                                    @include('alerts.feedback', ['field' => 'photo'])
                                </div>
                                -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Speichern') }}</button>
                                </div>
                            </div>
                        </form>
                        <hr class="my-4" />
                        <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Passwort') }}</h6>

                            @include('alerts.success', ['key' => 'password_status'])
                            @include('alerts.error_self_update', ['key' => 'not_allow_password'])

                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-current-password">{{ __('Aktuelles Passwort') }}</label>
                                    <input type="password" name="old_password" id="input-current-password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Aktuelles Passwort') }}" value="" required>

                                    @include('alerts.feedback', ['field' => 'old_password'])
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">{{ __('Neues Passwort') }}</label>
                                    <input type="password" name="password" id="input-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Neues Passwort') }}" value="" required>

                                    @include('alerts.feedback', ['field' => 'password'])
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">{{ __('Neues Passwort wiederholen') }}</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control" placeholder="{{ __('Neues Passwort wiederholen') }}" value="" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Passwort ändern') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection