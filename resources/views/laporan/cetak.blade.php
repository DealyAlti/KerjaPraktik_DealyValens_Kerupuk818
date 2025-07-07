<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan {{ ucwords(str_replace('_', ' ', $jenisLaporan)) }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 20px;
        }

        .header {
            text-align: center;
        }

        .header img {
            width: 200; /* Set width and let height adjust automatically */
            height: auto;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .date-range {
            font-size: 14px;
            margin-top: 5px;
        }

        .date-line {
            border-top: 2px solid #000;
            margin: 10px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table, .table th, .table td {
            border: 1px solid #000;
        }

        .table th, .table td {
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .date-printed {
            font-size: 14px;
            margin-top: 5px;
            font-style: italic;
        }

    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo.jpg') }}" alt="Logo"> <!-- Adjusted to load logo from the public folder -->
        <div class="title">Laporan {{ ucwords(str_replace('_', ' ', $jenisLaporan)) }} Kerupuk 818 Palembang</div>
        
        <!-- Format the date using tanggal_indonesia helper -->
        <div class="date-range">
            Tanggal: {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
        </div>

        <div class="date-printed">
            Dicetak pada: {{ tanggal_indonesia(now(), false) }}
        </div>


        <!-- Add a black line after the date -->
        <div class="date-line"></div>
    </div>

    @if ($jenisLaporan == 'barang_masuk')
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Masuk</th>
                    <th>Tanggal Masuk</th>
                    <th>Penangungg Jawab</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td>{{ $item->jumlahMasuk }}</td>
                        <td>{{ tanggal_indonesia($item->tanggal, false) }}</td> <!-- Format date for table -->
                        <td>{{ $item->nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif ($jenisLaporan == 'barang_keluar')
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Tanggal</th>
                    <th>Jumlah Keluar</th>
                    <th>Penerima Barang</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td>{{ tanggal_indonesia($item->tanggal, false) }}</td> <!-- Format date for table -->
                        <td>{{ $item->jumlahKeluar }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif ($jenisLaporan == 'permintaan_disetujui')
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Permintaan</th>
                    <th>Tanggal Permintaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td>{{ $item->jumlahPermintaan }}</td>
                        <td>{{ tanggal_indonesia($item->tanggal_permintaan, false) }}</td> <!-- Format date for table -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif


</body>
</html>
