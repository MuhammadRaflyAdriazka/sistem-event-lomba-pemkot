@extends('layouts.kepala.app')

@section('title', 'Dashboard Kepala ' . (auth()->user()->dinas->name ?? 'Dinas'))

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Selamat Datang</h6>
            </div>
            <div class="card-body">
                <p>Lihat ringkasan statistik dan perkembangan kegiatan melalui tampilan dashboard ini.</p>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-calendar-alt"></i> Total Acara</h5>
                                <h2>{{ $totalAcara ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-users"></i> Peserta Aktif</h5>
                                <h2>{{ $pesertaAktif ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-trophy"></i> Acara Selesai</h5>
                                <h2>{{ $eventSelesai ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Donut Chart -->
<div class="row">
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Status Pendaftaran Peserta</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                @if(($pendaftarMenunggu + $pendaftarDiterima + $pendaftarDitolak) > 0)
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Menunggu ({{ $pendaftarMenunggu }})
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Diterima ({{ $pendaftarDiterima }})
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Ditolak ({{ $pendaftarDitolak }})
                    </span>
                </div>
                @else
                <div class="text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-chart-pie fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Belum Ada Data Pendaftaran</h5>
                    <p class="text-muted mb-0">Chart akan muncul setelah ada peserta yang mendaftar acara</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Acara {{ Auth::user()->dinas->name ?? 'Dinas Ini' }}</h6>
            </div>
            <div class="card-body">
                @php
                    // Ambil daftar acara dari dinas ini
                    $acaraDinas = App\Models\Acara::where('id_dinas', Auth::user()->id_dinas)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($acaraDinas->count() > 0)
                    @foreach($acaraDinas as $acara)
                    @php
                        // Hanya hitung peserta yang DISETUJUI untuk kuota
                        $pesertaDiterima = App\Models\Pendaftaran::where('id_acara', $acara->id)->where('status', 'disetujui')->count();
                        $persentaseTerisi = $acara->kuota > 0 ? round(($pesertaDiterima / $acara->kuota) * 100) : 0;
                    @endphp
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <div class="flex-grow-1">
                            <div class="font-weight-bold text-gray-800 mb-1">{{ $acara->judul }}</div>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ \Carbon\Carbon::parse($acara->tanggal_acara)->format('d M Y') }}
                            </small>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-users mr-1"></i>
                                Kuota: {{ number_format($acara->kuota) }} | 
                                <span class="{{ $persentaseTerisi >= 80 ? 'text-success' : ($persentaseTerisi >= 50 ? 'text-warning' : 'text-info') }} font-weight-bold">
                                    {{ number_format($pesertaDiterima) }} diterima
                                </span>
                                ({{ $persentaseTerisi }}%)
                            </small>
                        </div>
                        <div class="ml-3 text-right">
                            @if($acara->status == 'active')
                                <span class="badge badge-success px-3 py-2 mb-1">Aktif</span>
                            @elseif($acara->status == 'draft')
                                <span class="badge badge-warning px-3 py-2 mb-1">Draft</span>
                            @else
                                <span class="badge badge-secondary px-3 py-2 mb-1">Selesai</span>
                            @endif
                            <br>
                            <small class="text-muted">{{ $persentaseTerisi }}% terisi</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Belum ada acara yang dibuat</h6>
                        <p class="text-muted small">Acara yang dibuat admin dinas akan muncul di sini</p>
                    </div>
                @endif
                
                @if($acaraDinas->count() > 0)
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $acaraDinas->count() }} acara terbaru dari total {{ $totalAcara }} acara
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Destroy existing chart if exists to prevent conflicts
@if(($pendaftarMenunggu + $pendaftarDiterima + $pendaftarDitolak) > 0)
if (window.myPieChart) {
    window.myPieChart.destroy();
}

// Clean Chart.js configuration
var ctx = document.getElementById("myPieChart");
window.myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Menunggu", "Diterima", "Ditolak"],
    datasets: [{
      data: [{{ $pendaftarMenunggu ?? 0 }}, {{ $pendaftarDiterima ?? 0 }}, {{ $pendaftarDitolak ?? 0 }}],
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
      borderWidth: 2,
      borderColor: '#ffffff'
    }]
  },
  options: {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    tooltips: {
      enabled: true,
      backgroundColor: "#ffffff",
      titleFontColor: "#333333",
      bodyFontColor: "#666666",
      borderColor: "#dddddd",
      borderWidth: 1,
      cornerRadius: 8,
      displayColors: false,
      callbacks: {
        title: function(tooltipItem, data) {
          return data.labels[tooltipItem[0].index];
        },
        label: function(tooltipItem, data) {
          var total = data.datasets[0].data.reduce(function(a, b) { return a + b; }, 0);
          var currentValue = data.datasets[0].data[tooltipItem.index];
          var percentage = total > 0 ? Math.round((currentValue / total) * 100) : 0;
          return currentValue + ' orang (' + percentage + '%)';
        }
      }
    },
    cutoutPercentage: 70,
    plugins: {
      legend: false,
      datalabels: false
    }
  }
});
@endif
</script>
@endpush
