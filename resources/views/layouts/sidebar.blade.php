<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="
                    @if(auth()->user()->level == 0)
                        {{ route('dashboard') }}
                    @elseif(auth()->user()->level == 1)
                        {{ route('kepala.dashboard') }}
                    @elseif(auth()->user()->level == 2)
                        {{ route('kasir.dashboard') }}
                    @else
                        #
                    @endif
                ">
                    <i class="fa fa-dashboard"></i> <span> Dashboard</span>
                </a>
            </li>

            <li class="header">MASTER</li>

            <!-- Level 0,1: Kategori and Produk -->
            @if (auth()->user()->level == 0)
                <li>
                    <a href="{{ route('kategori.index') }}">
                        <i class="fa fa-list" aria-hidden="true"></i> <span> Kategori</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-shopping-bag" aria-hidden="true"></i> <span> Produk</span>
                </a>
            </li>

            <!-- Level 0: Pegawai -->
            @if (auth()->user()->level == 0)
                <li>
                    <a href="{{ route('pegawai.index') }}">
                        <i class="fa fa-user-circle" aria-hidden="true"></i> <span> Pegawai</span>
                    </a>
                </li>
            @endif

            <li class="header">TRANSAKSI</li>

            <!-- Level 0,1: Barang Masuk -->
            @if (auth()->user()->level == 0 || auth()->user()->level == 1)
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-plus" aria-hidden="true"></i> <span> Barang Masuk</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        @if (auth()->user()->level == 1)
                        <li><a href="{{ route('barangmasuk.create') }}"><i class="fa fa-plus"></i> Tambah Barang Masuk</a></li>
                        @endif
                        <li><a href="{{ route('barangmasuk.index') }}"><i class="fa fa-list"></i> Data Barang Masuk</a></li>
                    </ul>
                </li>
            @endif

            <!-- Level 0,1: Barang Keluar -->
            @if (auth()->user()->level == 0 || auth()->user()->level == 1)
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-minus" aria-hidden="true"></i> <span> Barang Keluar</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        @if (auth()->user()->level == 1)
                        <li><a href="{{ route('barangkeluar.create') }}"><i class="fa fa-plus"></i> Tambah Barang Keluar</a></li>
                        @endif
                        <li><a href="{{ route('barangkeluar.index') }}"><i class="fa fa-list"></i> Data Barang Keluar</a></li>
                    </ul>
                </li>
            @endif

            <!-- Level 0,1: Permintaan Barang -->
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-basket" aria-hidden="true"></i> <span>Permintaan Barang</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                    @if (auth()->user()->level == 2)
                        <li><a href="{{ route('permintaan.index') }}"><i class="fa fa-plus"></i> Tambah Permintaan</a></li>
                    @endif
                        <li><a href="{{ route('permintaan.list') }}"><i class="fa fa-eye"></i> Lihat Permintaan</a></li>
                    </ul>
                </li>

            <!-- Level 0,1: Cetak Laporan -->
            @if (auth()->user()->level == 0 || auth()->user()->level == 1)
            <li class="header">LAPORAN</li>
                <li>
                    <a href="{{ route('laporan.index') }}">
                        <i class="fa fa-print" aria-hidden="true"></i> <span> Cetak Laporan</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->level == 0)
            <li class="header">SETTING</li>
                <li>
                    <a href="{{ route('toko.index') }}">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i> <span> Toko</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.index') }}">
                        <i class="fa fa-user" aria-hidden="true"></i> <span> Pengguna</span>
                    </a>
                </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
