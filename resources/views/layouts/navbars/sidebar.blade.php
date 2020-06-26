<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('hofolding/teammanager-logo.png')}}" class="navbar-brand-img" alt="...">
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home text-primary"></i>
                            <span class="nav-link-text">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                </ul>
                @canany([\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::UserOwn)->key,
                \App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventBookingDelayed)->key,
                \App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventBookingImmediate)->key])
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">{{ __('Eigenes') }}</h6>
                <ul class="navbar-nav mb-md-3">
                    @can(\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::UserOwn)->key)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-address-card text-primary"></i>
                            <span class="nav-link-text">{{ __('Mein Profil') }}</span>
                        </a>
                    </li>
                    @endcan
                    @canany([\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventBookingImmediate)->key,
                            \App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventBookingDelayed)->key])
                    <li class="nav-item">
                        @can(\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventBookingImmediate)->key)
                            @if(auth()->user()->countFutureQuietEvents() > 0)
                                <span id="badge_quiet" class="badge badge-pill badge-warning" style="float:right;margin-bottom:-23px;">{{auth()->user()->countFutureQuietEvents()}}</span>
                            @endif
                        @elsecan((\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventBookingDelayed)->key))
                            @if(auth()->user()->countFutureQuietEventsDelayed() > 0)
                                <span id="badge_quiet" class="badge badge-pill badge-warning" style="float:right;margin-bottom:-23px;">{{auth()->user()->countFutureQuietEventsDelayed()}}</span>
                            @endif
                        @endcan
                        <a class="nav-link" href="{{ route('myEvent') }}">
                            <i class="fas fa-table-tennis text-primary"></i>
                            <span class="nav-link-text">{{ __('Meine Veranstaltungen') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
                @endcan
                @canany(\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::UserManagement)->key,
                \App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::GroupManagement)->key,
                \App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventManagement)->key,
                \App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::Settings)->key)
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">{{ __('Verwaltung') }}</h6>

                <ul class="navbar-nav mb-md-3">
                    @can(\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::UserManagement)->key)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.index') }}">
                            <i class="fas fa-user text-primary"></i>
                            <span class="nav-link-text">{{ __('Teammitglieder') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can(\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::GroupManagement)->key)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('group.index') }}">
                            <i class="fas fa-users text-primary"></i>
                            <span class="nav-link-text">{{ __('Gruppen') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can(\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventManagement)->key)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('event.index') }}">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            <span class="nav-link-text">{{ __('Veranstaltungen') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can(\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::Settings)->key)
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="fas fa-cogs text-primary"></i>
                            <span class="nav-link-text">{{ __('Einstellungen') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
                @endcan
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">{{ __('Links') }}</h6>

                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="http://www.tsvhofolding.de/index.php?abt=Tischtennis">
                            <i class="fas fa-globe text-primary"></i>
                            <span class="nav-link-text">{{ __('Homepage') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="http://tt-foto.tsvhofolding.de">
                            <i class="fas fa-images text-primary"></i>
                            <span class="nav-link-text">{{ __('Interne Fotogalerie') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://www.facebook.com/TSVHofoldingTischtennis/">
                            <i class="fab fa-facebook text-primary"></i>
                            <span class="nav-link-text">{{ __('Facebook') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://www.instagram.com/tsvhofoldingtischtennis/?hl=de">
                            <i class="fab fa-instagram text-primary"></i>
                            <span class="nav-link-text">{{ __('Instagram') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
