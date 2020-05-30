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

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Teilnehmer(ange./max./mögl.)') }}</th>
                                <th scope="col">{{ __('Veranstaltungsbeginn') }}</th>
                                <th scope="col">{{ __('Veröffentlichung') }}</th>
                                <th scope="col">{{ __('Anmeldeschluss') }}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($events as $event)
                                <tr>
                                    <td>{{ $event->name }}</td>
                                    <td>{{ $event->countPromise() }} / {{ $event->max_participant }} / {{ count($event->users) }}</td>
                                    <td>{{ $event->date_event_start->format('d.m.Y H:i') }}</td>
                                    <td>{{ $event->date_publication->format('d.m.Y') }}</td>
                                    <td>{{ $event->date_sign_up_end->format('d.m.Y') }}</td>
                                    <td class="text-right">
                                        <form action="{{ route('event.destroy', $event) }}" method="post">
                                            @csrf
                                            <a href="{{ route('checkEvent.edit', $event) }}" data-toggle="tooltip" title="Teilnehmer einteilen">
                                                <i style="padding: 5px" class="fas fa-user-check"></i>
                                            </a>
                                            <a href="{{ route('event.edit', $event) }}" data-toggle="tooltip" title="bearbeiten">
                                                <i style="padding: 5px" class="fas fa-edit"></i>
                                            </a>
                                            @method('delete')
                                            <button type="button" style="padding: 5px" class="btn btn-link" onclick="confirm('{{ __("Wollen Sie diese Veranstaltung wirklich löschen?") }}') ? this.parentElement.submit() : ''" data-toggle="tooltip" title="löschen">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6">{{__("Derzeit sind keine Veranstaltungen vorhanden.")}}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $events->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
