{{-- CSS Kustom untuk Tampilan Navbar yang Lebih Baik --}}
<style>
    .topbar .nav-item .nav-link {
        height: 4.375rem;
        display: flex;
        align-items: center;
        padding: 0 0.75rem;
    }
    .topbar .nav-item.dropdown .dropdown-toggle::after {
        width: 1rem;
        text-align: center;
        float: right;
        vertical-align: 0;
        border: 0;
        font-weight: 900;
        content: '\f107'; /* Ikon panah bawah dari Font Awesome */
        font-family: 'Font Awesome 5 Free';
    }
    .topbar .dropdown-menu {
        border: 1px solid #e3e6f0;
        border-top: none;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    .topbar .dropdown-item i {
        color: #d1d3e2;
        transition: color 0.15s ease-in-out;
    }
    .topbar .dropdown-item:hover i {
        color: #858796;
    }
</style>

    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
    
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
    
            <!-- Nav Item - Alerts (Notifications) -->
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    @if(isset($notificationCount) && $notificationCount > 0)
                        <span class="badge badge-danger badge-counter">{{ $notificationCount > 5 ? '5+' : $notificationCount }}</span>
                    @endif
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Pusat Notifikasi
                    </h6>
    
                    @forelse($unreadNotifications as $notification)
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('notifications.markAsRead', $notification->id) }}">
                            <div class="mr-3">
                                <div class="icon-circle {{ $notification->data['bg_color'] }}">
                                    <i class="{{ $notification->data['icon'] }}"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">{{ $notification->created_at->translatedFormat('d F Y, H:i') }}</div>
                                <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                            </div>
                        </a>
                    @empty
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                            </div>
                            <div>
                                <span class="font-weight-normal">Tidak ada notifikasi baru.</span>
                            </div>
                        </a>
                    @endforelse
                </div>
            </li>
    
            <div class="topbar-divider d-none d-sm-block"></div>
    
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                    <img class="img-profile rounded-circle"
                        src="{{ asset('template/img/undraw_profile.svg') }}">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#settingsModal">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Pengaturan Akun
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
    
        </ul>
    </nav>
    


{{-- Panggil modal pengaturan yang baru --}}
@auth
    @include('pages.profile.modal-setting')
@endauth
