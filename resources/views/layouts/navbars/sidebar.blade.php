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
                @canany(\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::UserOwn)->key,
                \App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventBookingImmediate)->key)
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
                    @can(\App\Buisness\Enum\PermissionEnum::getInstance(\App\Buisness\Enum\PermissionEnum::EventBookingImmediate)->key)
                    <li class="nav-item">
                        @if(auth()->user()->countQuiet() > 0)
                        <span id="badge_quiet" class="badge badge-pill badge-warning" style="float:right;margin-bottom:-23px;">{{auth()->user()->countQuiet()}}</span>
                        @endif
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
            </div>
        </div>
    </div>
</nav>
