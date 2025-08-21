@php
    // Tentukan URL dashboard yang benar berdasarkan peran pengguna
    $dashboardUrl = '/dashboard'; // Default untuk Admin
    if (auth()->user() && auth()->user()->role->name == 'Kepala Lingkungan') {
        $dashboardUrl = '/dashboard-kepala-lingkungan';
    }

    // Struktur menu Anda
    $menus = [
        // Role ID 1 (Admin)
        1 => [
            (object) ['type' => 'item', 'title' => 'Dashboard', 'path' => 'dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt'],
            (object) ['type' => 'divider'],
            (object) ['type' => 'heading', 'title' => 'MENU UTAMA'],
            (object) [
                'type' => 'dropdown',
                'title' => 'Layanan Surat',
                'icon' => 'fas fa-fw fa-envelope-open-text',
                'id' => 'layananSuratCollapse',
                'children' => [
                    (object) ['title' => 'Daftar Permohonan', 'path' => 'surat'],
                    (object) ['title' => 'Arsip Digital', 'path' => 'arsip-surat'],
                ]
            ],
            (object) ['type' => 'item', 'title' => 'Manajemen Pengaduan', 'path' => 'pengaduan', 'icon' => 'fas fa-fw fa-bullhorn'],
            (object) ['type' => 'divider'],
            (object) ['type' => 'heading', 'title' => 'PENGATURAN & MASTER'],
            (object) [
                'type' => 'dropdown',
                'title' => 'Manajemen Data',
                'icon' => 'fas fa-fw fa-cogs',
                'id' => 'manajemenDataCollapse',
                'children' => [
                    (object) ['title' => 'Data Penduduk', 'path' => 'penduduk'],
                    (object) ['title' => 'Data Admin', 'path' => 'admin'],
                    (object) ['title' => 'Persetujuan Warga', 'path' => 'persetujuan-pengguna'],
                ]
            ],
        ],
        // Role ID 2 (Kepala Lingkungan)
        2 => [
            // PERUBAHAN DI SINI: Path diubah ke dashboard yang benar
            (object) ['type' => 'item', 'title' => 'Dashboard', 'path' => 'dashboard-kepala-lingkungan', 'icon' => 'fas fa-fw fa-tachometer-alt'],
            (object) ['type' => 'item', 'title' => 'Data Penduduk', 'path' => 'penduduk', 'icon' => 'fas fa-fw fa-table'],
            (object) ['type' => 'item', 'title' => 'Laporan Pengaduan', 'path' => 'pengaduan', 'icon' => 'fas fa-fw fa-bullhorn'],
        ],
    ];
@endphp

{{-- CSS KUSTOM (Tidak ada perubahan) --}}
<style>
    /* ... (semua style kustom Anda tetap di sini) ... */
</style>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    {{-- PERUBAHAN DI SINI: href sekarang dinamis menggunakan $dashboardUrl --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url($dashboardUrl) }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logotomohon.png') }}" alt="Logo Tomohon" style="width: 50px;">
        </div>
        <div class="sidebar-brand-text text-sm mx-3">SI KAMJA</div>
    </a>

    {{-- Loop melalui struktur menu (Tidak ada perubahan pada logika loop) --}}
    @foreach ($menus[auth()->user()->role_id] as $menu)
        
        @if ($menu->type == 'divider')
            <hr class="sidebar-divider my-0">
        @elseif ($menu->type == 'heading')
            <div class="sidebar-heading px-3 mt-4">{{ $menu->title }}</div>
        @elseif ($menu->type == 'item')
            <li class="nav-item {{ request()->is($menu->path . '*') ? 'active' : '' }}">
                <a class="nav-link" href="/{{ $menu->path }}">
                    <i class="{{ $menu->icon }} fa-fw"></i>
                    <span>{{ $menu->title }}</span>
                </a>
            </li>
        @elseif ($menu->type == 'dropdown')
            @php
                $isActive = false;
                foreach ($menu->children as $child) {
                    if (request()->is($child->path . '*')) {
                        $isActive = true;
                        break;
                    }
                }
            @endphp
            <li class="nav-item {{ $isActive ? 'active' : '' }}">
                <a class="nav-link {{ $isActive ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#{{ $menu->id }}"
                    aria-expanded="{{ $isActive ? 'true' : 'false' }}" aria-controls="{{ $menu->id }}">
                    <i class="{{ $menu->icon }} fa-fw"></i>
                    <span>{{ $menu->title }}</span>
                </a>
                <div id="{{ $menu->id }}" class="collapse {{ $isActive ? 'show' : '' }}" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">{{ $menu->title }}:</h6>
                        @foreach ($menu->children as $child)
                            <a class="collapse-item {{ request()->is($child->path . '*') ? 'active' : '' }}" href="/{{ $child->path }}">{{ $child->title }}</a>
                        @endforeach
                    </div>
                </div>
            </li>
        @endif

    @endforeach

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>