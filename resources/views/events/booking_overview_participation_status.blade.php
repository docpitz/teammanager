<div class="min-vh-100">
    <h3>{{$header}}</h3>
    <ul id="{{$participationDescription}}" class="droptrue sortableItems bg-{{$color}}-pastel">
        @foreach($users as $user)
            <li class="ui-state-default" id='{{$user->id}}'>
                <input type='hidden' name='{{$participationDescription}}[]' value='{{$user->id}}' id="hiddenInput{{$user->id}}"/>
                <div class="container">
                <div class="row">
                    <div class="col-2-small-padding justify-content-center align-self-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img src="{{$user->profilePicture()}}">
                        </span>
                    </div>
                    <div class="col-9">
                        <h4 class="mb--1">{{$user->firstname}} {{$user->surname}}</h4>
                        <h6 class="mb--1">{{$user->date_user_changed_participation_status->format('d.m.Y H:i').__(' Uhr')}}</h6>
                        @if(is_null($user->changed_by_user_surname))
                            <h6>({{__("User unbekannt (gelöscht)")}})</h6>
                        @else
                            <h6>({{$user->changed_by_user_surname}}, {{$user->changed_by_user_firstname}})</h6>
                        @endif
                    </div>
                    <div class="col-2-small-padding align-self-center text-left">
                        <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#history{{$user->id}}">
                            i
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="history{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Versionshistorie ({{$user->firstname}} {{$user->surname}})</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <div>
                                                <table class="table align-items-center">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col">Änderungszeit</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">geändert durch</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="list">
                                                    @foreach($user->changes as $change)
                                                        <tr>
                                                            <td>{{$change['changed_date_formatted']}}</td>
                                                            <td>{{$change['participation_status_name']}}</td>
                                                            @if (empty($change['user']))
                                                                <td>{{__("User unbekannt (gelöscht)")}}</td>
                                                            @else
                                                            <td>{{$change['user']->surname}}, {{$change['user']->firstname}}</td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
