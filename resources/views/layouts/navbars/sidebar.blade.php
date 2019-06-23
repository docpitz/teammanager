<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('hofolding/tsvlogo.png')}}" class="navbar-brand-img" alt="...">
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


                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">{{ __('Eigenes') }}</h6>
                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-address-card text-primary"></i>
                            <span class="nav-link-text">{{ __('Mein Profil') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('myevent.index') }}">
                            <i class="fas fa-table-tennis text-primary"></i>
                            <span class="nav-link-text">{{ __('Meine Veranstaltungen') }}</span>
                        </a>
                    </li>
                </ul>
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
                            <a class="nav-link" href="{{ route('setting.edit') }}">
                                <i class="fas fa-cogs text-primary"></i>
                                <span class="nav-link-text">{{ __('Einstellungen') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
                @endcan

                @role(\App\Buisness\Enum\RoleEnum::getInstance(\App\Buisness\Enum\RoleEnum::SuperAdmin)->key)
                <!-- Divider -->
                <hr class="my-3">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                            <i class="fab fa-laravel" style="color: #f4645f;"></i>
                            <span class="nav-link-text" style="color: #f4645f;">{{ __('Laravel Examples') }}</span>
                        </a>
                        <div class="collapse show" id="navbar-examples">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('profile.edit') }}" class="nav-link">{{ __('Profile') }}</a>
                                </li>
                                    <li class="nav-item">
                                        <a href="{{ route('role.index') }}" class="nav-link">{{ __('Role Management') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.index') }}" class="nav-link">{{ __('User Management') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('category.index') }}" class="nav-link">{{ __('Category Management') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('tag.index') }}" class="nav-link">{{ __('Tag Management') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('item.index') }}" class="nav-link">{{ __('Item Management') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('item.index') }}" class="nav-link">{{ __('Items') }}</a>
                                    </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-pages" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-pages">
                            <i class="ni ni-collection text-yellow"></i>
                            <span class="nav-link-text">{{ __('Pages') }}</span>
                        </a>
                        <div class="collapse" id="navbar-pages">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('page.index', 'timeline') }}" class="nav-link">{{ __('Timeline') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-components" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-components">
                            <i class="ni ni-ui-04 text-info"></i>
                            <span class="nav-link-text">{{ __('Components') }}</span>
                        </a>
                        <div class="collapse" id="navbar-components">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('page.index','buttons') }}" class="nav-link">{{ __('Buttons') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','cards') }}" class="nav-link">{{ __('Cards') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','grid') }}" class="nav-link">{{ __('Grid') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','notifications') }}" class="nav-link">{{ __('Notifications') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','icons') }}" class="nav-link">{{ __('Icons') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','typography') }}" class="nav-link">{{ __('Typography') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#navbar-multilevel" class="nav-link" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-multilevel">{{ __('Multi') }} level</a>
                                    <div class="collapse show" id="navbar-multilevel" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="#!" class="nav-link ">{{ __('Thirdlevelmenu') }}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#!" class="nav-link ">{{ __('Justanotherlink') }}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#!" class="nav-link ">{{ __('Onelastlink') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-forms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
                            <i class="ni ni-single-copy-04 text-pink"></i>
                            <span class="nav-link-text">{{ __('Forms') }}</span>
                        </a>
                        <div class="collapse" id="navbar-forms">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('page.index','elements') }}" class="nav-link">{{ __('Elements') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','components') }}" class="nav-link">{{ __('Components') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','validation') }}" class="nav-link">{{ __('Validations') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                            <i class="ni ni-align-left-2 text-default"></i>
                            <span class="nav-link-text">{{ __('Tables') }}</span>
                        </a>
                        <div class="collapse" id="navbar-tables">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('page.index','tables') }}" class="nav-link">{{ __('Tables') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','sortable') }}" class="nav-link">{{ __('Sortable') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','datatables') }}" class="nav-link">{{ __('Datatables') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-maps" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-maps">
                            <i class="ni ni-map-big text-primary"></i>
                            <span class="nav-link-text">{{ __('Maps') }}</span>
                        </a>
                        <div class="collapse" id="navbar-maps">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('page.index','googlemaps') }}" class="nav-link">{{ __('Google') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('page.index','vectormaps') }}" class="nav-link">{{ __('Vector') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('page.index','widgets') }}">
                            <i class="ni ni-archive-2 text-green"></i>
                            <span class="nav-link-text">{{ __('Widgets') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('page.index','charts') }}">
                            <i class="ni ni-chart-pie-35 text-info"></i>
                            <span class="nav-link-text">{{ __('Charts') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('page.index','calendar') }}">
                            <i class="ni ni-calendar-grid-58 text-red"></i>
                            <span class="nav-link-text">{{ __('Calendar') }}</span>
                        </a>
                    </li>
                </ul>
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">{{ __('Documentation') }}</h6>
                <!-- Navigation -->
                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/getting-started/overview.html" target="_blank">
                            <i class="ni ni-spaceship"></i>
                            <span class="nav-link-text">Getting started</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/foundation/colors.html" target="_blank">
                            <i class="ni ni-palette"></i>
                            <span class="nav-link-text">Foundation</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/components/alerts.html" target="_blank">
                            <i class="ni ni-ui-04"></i>
                            <span class="nav-link-text">Components</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/plugins/charts.html" target="_blank">
                            <i class="ni ni-chart-pie-35"></i>
                            <span class="nav-link-text">Plugins</span>
                        </a>
                    </li>
                </ul>
                @endrole
            </div>
        </div>
    </div>
</nav>