@extends('layouts.app', ['title' => __('Gruppe anlegen')])

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
                                <h3 class="mb-0">{{ __('Gruppe anlegen') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('group.index') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="right" title="zurück zur Übersicht">
                                    <i class="fas fa-level-up-alt fa-2x"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('group.store') }}" autocomplete="off"
                              enctype="multipart/form-data">
                            @csrf
                            <h6 class="heading-small text-muted mb-4">{{ __('Neue Gruppe') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                                <select multiple="multiple" size="10" name="group_users[]" title="users">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}" {{ in_array($user->id, ! is_null(old("group_users")) ? old("group_users") : array() ) ? "selected":"" }}>
                                            {{$user->surname}} {{$user->firstname}}
                                        </option>
                                    @endforeach
                                </select>
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

@include('groups.duallistbox', ['name_of_select_multiple' => 'group_users'])