@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body text-center">
                <h2>Selamat Datang, <strong>{{ $user->name }}</strong></h2>
                <p>Anda login sebagai <strong>KEPALA GUDANG</strong></p>
                <p>Jangan Lupa Memeriksa <strong>NOTIFIKASI!</strong></p>
            </div>
        </div>
    </div>
</div>
<div class="info-boxes-container">
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
    <div class="info-box bg-yellow">
        <div class="info-box-icon">
            <i class="fa fa-archive"></i>
        </div>
        <div class="info-box-content">
            <div class="info-box-number">{{ $total_permintaan }}</div>
            <div class="info-box-text">Total Permintaan Barang</div>
            <a href="{{ route('permintaan.list') }}" class="info-box-more">
                Lihat <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

@if($pendingRequests > 0 || $produk_menipis->count() > 0)
    <!-- Notifikasi Floating Button -->
        <button class="floating-notif-btn" data-toggle="modal" data-target="#notificationModal">
            <i class="fa fa-bell"></i>
        </button>
@endif

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-bell"></i> Notifikasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($pendingRequests > 0)
                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Ada permintaan barang yang masih <strong>Pending</strong>.
                        <a href="{{ route('permintaan.list') }}" class="btn btn-warning btn-sm mt-2">Lihat Permintaan</a>
                    </div>
                @endif

                @if($produk_menipis->count() > 0)
                    <div class="alert alert-warning">
                        <strong>Stok Menipis!</strong>
                        <ul>
                            @foreach($produk_menipis as $produk)
                                <li>{{ $produk->nama_produk }} - Stok: {{ $produk->stok }}</li>
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
@endsection
