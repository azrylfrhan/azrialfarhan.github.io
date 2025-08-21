@extends('layout.app')

@section('content')

{{-- ======================================================= --}}
{{-- === BAGIAN 1: WELCOME BANNER & KARTU STATISTIK === --}}
{{-- ======================================================= --}}

@php
    // Logika untuk ucapan selamat berdasarkan waktu (tetap sama)
    $hour = now()->hour;
    if ($hour < 11) {
        $greeting = 'Selamat Pagi';
        $greeting_icon = 'fa-cloud-sun';
    } elseif ($hour < 15) {
        $greeting = 'Selamat Siang';
        $greeting_icon = 'fa-sun';
    } elseif ($hour < 19) {
        $greeting = 'Selamat Sore';
        $greeting_icon = 'fa-cloud-sun';
    } else {
        $greeting = 'Selamat Malam';
        $greeting_icon = 'fa-moon';
    }
@endphp

<!-- Welcome Banner -->
<div class="card shadow-sm mb-4 border-left-primary">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="text-primary font-weight-bold mb-2">
                    <i class="fas {{ $greeting_icon }} mr-2"></i> {{ $greeting }}, {{ auth()->user()->name }}!
                </h4>
                <p class="text-gray-700 mb-0">
                    Selamat datang kembali. Mari kita lihat ringkasan aktivitas dan prioritas kerja untuk hari ini.
                </p>
            </div>
            <div class="col-md-4 d-none d-md-block text-right">
                <img src="{{ asset('template/img/undraw_posting_photo.svg') }}" alt="Welcome" style="height: 120px;">
            </div>
        </div>
    </div>
</div>

<!-- Baris Kartu Statistik -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Permohonan Baru</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahPermohonanBaru }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-inbox fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Penduduk Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahPendudukAktif }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Surat Selesai (Bulan Ini)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $suratSelesaiBulanIni }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Jenis Surat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahJenisSurat }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-file-alt fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ======================================================= --}}
{{-- === BAGIAN 2: PRIORITAS KERJA (SIMULASI TRIAGE) === --}}
{{-- ======================================================= --}}
<div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0 text-gray-800">Prioritas Kerja Hari Ini</h1>
</div>

<div class="row">

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-danger py-3">
                <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-rocket mr-2"></i>Prioritas Tinggi</h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($tugasTinggi as $permohonan)
                    <a href="{{ url('/surat') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 font-weight-bold">{{ $permohonan->penduduk->nama ?? 'Nama...' }}</h6>
                            <small>{{ $permohonan->created_at->diffForHumans() }}</small>
                        </div>
                        <small class="mb-1 text-danger">{{ $permohonan->jenisSurat->nama_surat ?? 'Jenis...' }}</small>
                    </a>
                @empty
                    <div class="card-body text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-2x mb-2"></i><p>Tidak ada tugas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-warning py-3">
                <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-tasks mr-2"></i>Prioritas Sedang</h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($tugasSedang as $permohonan)
                     <a href="{{ url('/surat') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 font-weight-bold">{{ $permohonan->penduduk->nama ?? 'Nama...' }}</h6>
                            <small>{{ $permohonan->created_at->diffForHumans() }}</small>
                        </div>
                        <small class="mb-1 text-warning">{{ $permohonan->jenisSurat->nama_surat ?? 'Jenis...' }}</small>
                    </a>
                @empty
                    <div class="card-body text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-2x mb-2"></i><p>Tidak ada tugas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-success py-3">
                <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-coffee mr-2"></i>Prioritas Rendah</h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($tugasRendah as $permohonan)
                    <a href="{{ url('/surat') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 font-weight-bold">{{ $permohonan->penduduk->nama ?? 'Nama...' }}</h6>
                            <small>{{ $permohonan->created_at->diffForHumans() }}</small>
                        </div>
                        <small class="mb-1 text-success">{{ $permohonan->jenisSurat->nama_surat ?? 'Jenis...' }}</small>
                    </a>
                @empty
                    <div class="card-body text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-2x mb-2"></i><p>Tidak ada tugas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tampilkan link ini hanya jika ada tugas di salah satu kolom --}}
    @if(!$tugasTinggi->isEmpty() || !$tugasSedang->isEmpty() || !$tugasRendah->isEmpty())
        <div class="col-12 text-center mb-4"><a href="/surat">Lihat Semua Permohonan &rarr;</a></div>
    @endif
    
</div>


{{-- ======================================================= --}}
{{-- === BAGIAN 3: GRAFIK & VISUALISASI DATA === --}}
{{-- ======================================================= --}}
<div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0 text-gray-800">Visualisasi Data</h1>
</div>

<div class="row">
    <!-- Grafik Tren Permohonan -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tren Permohonan Surat (Bulan Ini)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 300px;">
                    <canvas id="trenPermohonanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Komposisi Agama -->
    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Komposisi Penduduk Berdasarkan Agama</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar" style="height: 250px;">
                    <canvas id="agamaBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Komposisi Gender -->
    <div class="col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Komposisi Penduduk Berdasarkan Gender</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4" style="height: 210px;">
                    <canvas id="genderDoughnutChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2"><i class="fas fa-circle text-primary"></i> Laki-laki</span>
                    <span class="mr-2"><i class="fas fa-circle text-success"></i> Perempuan</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Memuat library Chart.js hanya di halaman ini --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Kode JavaScript untuk semua grafik tidak berubah sama sekali --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    // GRAFIK 1: TREN PERMOHONAN (LINE CHART)
    var ctxLine = document.getElementById("trenPermohonanChart");
    if (ctxLine) {
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: "Jumlah Permohonan",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    data: @json($chartData),
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { ticks: { beginAtZero: true, callback: function(value) { if (Number.isInteger(value)) return value; }}}}
            }
        });
    }

    // GRAFIK 2: KOMPOSISI GENDER (DOUGHNUT CHART)
    var ctxDoughnut = document.getElementById("genderDoughnutChart");
    if (ctxDoughnut) {
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [{{ $jumlahPria }}, {{ $jumlahWanita }}],
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: false,
                    }
                },
                cutout: '80%',
            },
        });
    }

    // GRAFIK 3: KOMPOSISI AGAMA (BAR CHART)
    var ctxBar = document.getElementById("agamaBarChart");
    if(ctxBar) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: @json($agamaLabels),
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: @json($agamaData),
                    backgroundColor: '#36b9cc',
                    hoverBackgroundColor: '#2c9faf',
                    borderColor: '#36b9cc',
                    maxBarThickness: 25,
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { 
                    x: { grid: { display: false, drawBorder: false } },
                    y: { ticks: { beginAtZero: true, callback: function(value) { if (Number.isInteger(value)) return value; }}}
                }
            }
        });
    }
});
</script>
@endpush
