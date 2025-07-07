@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="info-boxes-container">
    <div class="info-box bg-aqua">
        <div class="info-box-icon">
            <i class="fa fa-list"></i>
        </div>
        <div class="info-box-content">
            <div class="info-box-number">{{ $kategori }}</div>
            <div class="info-box-text">Total Kategori</div>
            <a href="{{ route('kategori.index') }}" class="info-box-more">
                Lihat <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Info Box 2 - Total Produk -->
    <div class="info-box bg-blue">
        <div class="info-box-icon">
            <i class="fa fa-cube"></i>
        </div>
        <div class="info-box-content">
            <div class="info-box-number">{{ $produk }}</div>
            <div class="info-box-text">Total Produk</div>
            <a href="{{ route('produk.index') }}" class="info-box-more">
                Lihat <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Info Box 3 - Total Pegawai -->
    <div class="info-box bg-purple">
        <div class="info-box-icon">
            <i class="fa fa-users"></i>
        </div>
        <div class="info-box-content">
            <div class="info-box-number">{{ $pegawai }}</div>
            <div class="info-box-text">Total Pegawai</div>
            <a href="{{ route('pegawai.index') }}" class="info-box-more">
                Lihat <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

        <!-- Info Box 4 - Total Transaksi Barang Masuk -->
    <div class="info-box bg-red">
        <div class="info-box-icon">
            <i class="fa fa-shopping-cart"></i>
        </div>
        <div class="info-box-content">
            <div class="info-box-number">{{ $barang_masuk }}</div>
            <div class="info-box-text">Total Transaksi Barang Masuk</div>
            <a href="{{ route('barangmasuk.index') }}" class="info-box-more">
                Lihat <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Info Box 5 - Total Transaksi Barang Keluar -->
    <div class="info-box bg-green">
        <div class="info-box-icon">
            <i class="fa fa-truck"></i>
        </div>
        <div class="info-box-content">
            <div class="info-box-number">{{ $barang_keluar }}</div>
            <div class="info-box-text">Total Transaksi Barang Keluar</div>
            <a href="{{ route('barangmasuk.index') }}" class="info-box-more">
                Lihat <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
<!-- /.row -->
<!-- Floating Notification Button -->
@if (auth()->user()->level == 0 || auth()->user()->level == 1)
    @if($pendingRequests > 0 || $produk_menipis->count() > 0)
        <!-- Floating Notification Button -->
        <button class="floating-notif-btn" data-toggle="modal" data-target="#notificationModal">
            <i class="fa fa-bell"></i>
        </button>

    @endif
@endif

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="notificationModalLabel"><i class="fa fa-bell"></i> Notifikasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($pendingRequests > 0)
                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Ada permintaan barang yang masih <strong>Pending</strong>, silakan cek permintaan barang.
                        <a href="{{ route('permintaan.list') }}" class="btn btn-warning btn-sm mt-2">Lihat Permintaan</a>
                    </div>
                @endif

                @if($produk_menipis->count() > 0)
                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Stok produk menipis, silahkan produksi.
                        <ul>
                            @foreach($produk_menipis as $produk)
                                <li>{{ $produk->nama_produk }} ({{ $produk->kategori->nama_kategori }}) - Stok: {{ $produk->stok }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($pendingRequests == 0 && $produk_menipis->count() == 0)
                    <div class="text-center text-success">
                        <i class="fa fa-check-circle fa-2x"></i>
                        <p>Tidak ada notifikasi saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Filter untuk Permintaan Barang -->
@if (auth()->user()->level == 0 || auth()->user()->level == 1)
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Jumlah Permintaan Barang per Toko</h3>
                </div>
                <div class="box-body">
                    <form id="filterFormPermintaan" class="form-inline mb-3">
                        <label for="start_date">Dari Tanggal:</label>
                        <input type="date" id="start_date_permintaan" name="start_date" value="{{ date('Y-m-01') }}" class="form-control mx-2">

                        <label for="end_date">Sampai Tanggal:</label>
                        <input type="date" id="end_date_permintaan" name="end_date" value="{{ date('Y-m-t') }}" class="form-control mx-2">

                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>

                    <canvas id="permintaanChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter untuk Barang Masuk dan Barang Keluar -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Jumlah Barang Masuk dan Keluar per Produk</h3>
                </div>
                <div class="box-body">
                    <form id="filterFormBarangMasukKeluar" class="form-inline mb-3">
                        <label for="start_date">Dari Tanggal:</label>
                        <input type="date" id="start_date_barang" name="start_date" value="{{ date('Y-m-01') }}" class="form-control mx-2">

                        <label for="end_date">Sampai Tanggal:</label>
                        <input type="date" id="end_date_barang" name="end_date" value="{{ date('Y-m-t') }}" class="form-control mx-2">

                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>

                    <canvas id="barangMasukChart" height="100"></canvas>
                    <canvas id="barangKeluarChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function() {
        const ctxMasuk = document.getElementById('barangMasukChart').getContext('2d');
        const ctxKeluar = document.getElementById('barangKeluarChart').getContext('2d');
        const ctxPermintaan = document.getElementById('permintaanChart').getContext('2d');
        

        let chartMasuk, chartKeluar, chartPermintaan;

        function fetchDataAndRenderChartPermintaan(startDate, endDate) {
            $.get('{{ route('dashboard.permintaan-per-toko-per-tanggal') }}', { start_date: startDate, end_date: endDate })
                .done(function(response) {
                    if(chartPermintaan) {
                        chartPermintaan.destroy();
                    }

                    // Tambahkan warna jika belum ada
                    const colors = [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ];

                    response.datasets.forEach((dataset, index) => {
                        if (!dataset.backgroundColor) {
                            dataset.backgroundColor = colors[index % colors.length];
                        }
                    });

                    chartPermintaan = new Chart(ctxPermintaan, {
                        type: 'bar',
                        data: {
                            labels: response.labels,
                            datasets: response.datasets
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah Permintaan Barang per Toko per Tanggal'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    precision: 0,
                                    title: {
                                        display: true,
                                        text: 'Jumlah Permintaan'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Tanggal'
                                    }
                                }
                            }
                        }
                    });
                })
                .fail(function() {
                    alert('Gagal mengambil data grafik');
                });
        }


        function fetchDataAndRenderChartBarangMasukKeluar(startDate, endDate) {
            $.get('{{ route('dashboard.barang-masuk-keluar-per-produk') }}', { start_date: startDate, end_date: endDate })
                .done(function(response) {
                    if(chartMasuk) {
                        chartMasuk.destroy();
                    }
                    if(chartKeluar) {
                        chartKeluar.destroy();
                    }

                    chartMasuk = new Chart(ctxMasuk, {
                        type: 'line',
                        data: {
                            labels: response.labels,
                            datasets: response.datasetsMasuk
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah Barang Masuk per Produk per Tanggal'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    precision: 0
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Tanggal'
                                    }
                                }
                            }
                        }
                    });

                    chartKeluar = new Chart(ctxKeluar, {
                        type: 'line',
                        data: {
                            labels: response.labels,
                            datasets: response.datasetsKeluar
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah Barang Keluar per Produk per Tanggal'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    precision: 0
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Tanggal'
                                    }
                                }
                            }
                        }
                    });
                })
                .fail(function() {
                    alert('Gagal mengambil data grafik');
                });
        }

        // Load chart dengan tanggal default
        const startDatePermintaan = $('#start_date_permintaan').val();
        const endDatePermintaan = $('#end_date_permintaan').val();
        fetchDataAndRenderChartPermintaan(startDatePermintaan, endDatePermintaan);

        const startDateBarang = $('#start_date_barang').val();
        const endDateBarang = $('#end_date_barang').val();
        fetchDataAndRenderChartBarangMasukKeluar(startDateBarang, endDateBarang);

        // Ketika form filter disubmit untuk permintaan barang
        $('#filterFormPermintaan').submit(function(e) {
            e.preventDefault();
            const start = $('#start_date_permintaan').val();
            const end = $('#end_date_permintaan').val();

            if (start > end) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal Tidak Valid',
                    text: 'Tanggal awal tidak boleh melebihi tanggal akhir',
                });
                return;
            }

            fetchDataAndRenderChartPermintaan(start, end);
        });


        // Ketika form filter disubmit untuk barang masuk dan keluar
        $('#filterFormBarangMasukKeluar').submit(function(e) {
            e.preventDefault();
            const start = $('#start_date_barang').val();
            const end = $('#end_date_barang').val();

            if (start > end) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal Tidak Valid',
                    text: 'Tanggal awal tidak boleh melebihi tanggal akhir',
                });
                return;
            }

            fetchDataAndRenderChartBarangMasukKeluar(start, end);
        });

    });
</script>

@endpush

