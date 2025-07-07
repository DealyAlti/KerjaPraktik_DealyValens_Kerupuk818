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
                <p>Anda login sebagai <strong>KASIR</strong></p>
                <p>Berada di Toko: <strong>{{ $toko->nama_toko ?? '-' }}</strong></p>

                <a href="{{ route('permintaan.index') }}" class="btn btn-success mt-3">Buat Permintaan Baru</a>
            </div>
        </div>
    </div>
</div>
@endsection
