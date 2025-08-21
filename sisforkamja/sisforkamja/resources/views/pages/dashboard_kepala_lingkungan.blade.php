@extends('layout.app')

@section('content')
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

    {{-- Welcome Banner --}}
    <div class="card shadow-sm mb-4 border-left-primary">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="text-primary font-weight-bold mb-2">
                        <i class="fas {{ $greeting_icon }} mr-2"></i> {{ $greeting }}, {{ auth()->user()->name }}!
                    </h4>
                    <p class="text-gray-700 mb-0">
                        Selamat datang, {{ auth()->user()->name }}. Ini adalah ringkasan data untuk {{ auth()->user()->lingkungan }}.
                    </p>
                </div>
                <div class="col-md-4 d-none d-md-block text-right">
                    <img src="{{ asset('template/img/undraw_posting_photo.svg') }}" alt="Welcome" style="height: 120px;">
                </div>
            </div>
        </div>
    </div>

    {{-- Baris Kartu Statistik Penduduk --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Penduduk Aktif</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahPenduduk }}</div></div><div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div></div></div></div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Pria</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahPria }}</div></div><div class="col-auto"><i class="fas fa-male fa-2x text-gray-300"></i></div></div></div></div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah Wanita</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahWanita }}</div></div><div class="col-auto"><i class="fas fa-female fa-2x text-gray-300"></i></div></div></div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-warning py-3"><h6 class="m-0 font-weight-bold text-white"><i class="fas fa-bullhorn mr-2"></i>Pengaduan Terbaru di Wilayah Anda</h6></div>
                <div class="list-group list-group-flush">
                    @forelse($pengaduanLingkungan as $pengaduan)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 font-weight-bold">{{ $pengaduan->penduduk->nama ?? 'Nama...' }}</h6>
                                <small>{{ $pengaduan->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 text-truncate">{{ $pengaduan->isi_pengaduan ?? 'Isi...' }}</p>
                            <small>Status: <span class="badge badge-warning">{{ $pengaduan->status }}</span></small>
                        </div>
                    @empty
                        <div class="card-body text-center text-muted py-5"><i class="fas fa-check-circle fa-2x mb-2"></i><p>Tidak ada pengaduan aktif.</p></div>
                    @endforelse
                </div>
                {{-- <div class="card-footer text-center"><a href="/pengaduan-lingkungan">Lihat Semua Pengaduan &rarr;</a></div> --}}
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary py-3"><h6 class="m-0 font-weight-bold text-white"><i class="fas fa-chart-bar mr-2"></i>Komposisi Usia Penduduk</h6></div>
                <div class="card-body"><div class="chart-bar" style="height: 320px;"><canvas id="usiaBarChart"></canvas></div></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ctxBar = document.getElementById("usiaBarChart");
    if(ctxBar) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: @json($usiaLabels),
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: @json($usiaChartData),
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                    maxBarThickness: 40,
                }]
            },
            options: { maintainAspectRatio: false, responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false, drawBorder: false } }, y: { ticks: { beginAtZero: true, callback: function(value) { if (Number.isInteger(value)) return value; }}}}}
        });
    }
});
</script>
@endpush