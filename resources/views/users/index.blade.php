@extends('layouts.app', ['title' => __('Teammitglieder')])

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
                                <h3 class="mb-0">{{ __('Teammitglieder') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="right" title="neues Teammitglied hinzufügen">
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

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('1. E-Mail') }}</th>
                                <th scope="col">{{ __('2. E-Mail') }}</th>
                                <th scope="col">{{ __('Berechtigung') }}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->surname }} {{ $user->firstname }}</td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}">{{ \App\Helper\Email::replaceUmlauts($user->email) }}</a>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $user->email_optional }}">{{ \App\Helper\Email::replaceUmlauts($user->email_optional) }}</a>
                                    </td>
                                    <td>{{ $user->getRole()->getFormattedName() }}</td>
                                    <td class="text-right">
                                        <form action="{{ route('user.destroy', $user) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <a href="{{ route('user.edit', $user) }}" data-toggle="tooltip" title="bearbeiten">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if ($user->id != auth()->id())
                                                <button type="button" class="btn btn-link" onclick="confirm('{{ __("Wollen Sie dieses Teammitglied wirklich löschen?") }}') ? this.parentElement.submit() : ''" data-toggle="tooltip" title="löschen">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="löschen der eigenen Person ist nicht möglich">
                                                    <button type="button" style="pointer-events: none;" class="btn btn-link" disabled="disabled">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </span>
                                            @endif

                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $users->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
@endpush
