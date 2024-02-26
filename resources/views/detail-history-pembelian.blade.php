<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Detail History - {{ auth()->user()->name }}</title>

    <style>
         body {
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('other_image/bg-white.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            opacity: 0.5;
            z-index: -1;
        }
    </style>
</head>

<body>
    @include('layout.nav')
    @php
        $totalSemuas = 0;

        foreach ($transaksi->detailtransaksi as $ditel) {
            $totalSemuas += $ditel->book->harga_buku * $ditel->qty;
        }
    @endphp
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h2>Detail Pembelian</h2>
                    <a href="{{ route('print-detail-pembelian', $transaksi->id) }}">
                        <img src="{{ asset('icon_assets/icons8-print-40.png') }}" width="45px" alt="print">
                    </a>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <h3 style="font-weight: 350;">Pembeli : <span
                            style="font-weight: 500;">{{ $transaksi->nama_pembeli }}</span></h3>
                    <p style="padding: 7px; border-radius: 2px; font-size: large; height: 40px"
                        class="text-white bg-success"> {{ $transaksi->invoice }} </p>
                </div>
                <hr>
            </div>
            <div class="card-body">
                <hr>
                <div class="mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Gambar Barang
                                </th>
                                <th>
                                    Nama Barang
                                </th>
                                <th>
                                    Harga Barang
                                </th>
                                <th>
                                    Jumlah Barang
                                </th>
                                <th>
                                    Total Harga
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi->detailtransaksi as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset($item->book->sampul_buku) }}"
                                            style="width: 70px; height: 110px; object-fit: cover;" alt="">
                                    </td>
                                    <td>
                                        {{ $item->book->judul_buku }}
                                    </td>
                                    <td>
                                        Rp. {{ number_format($item->book->harga_buku, 0, ',', '.') }};
                                    </td>
                                    <td>
                                        {{ $item->qty }}
                                    </td>
                                    <td>
                                        Rp.
                                        {{ number_format($item->book->harga_buku * $item->qty, 0, ',', '.') }};
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    @if ($transaksi->voucher_digunakan)
                        <div class="d-flex justify-content-between">
                            <p style="font-size: large">Total Semula : </p>
                            <h5>Rp. {{ number_format($totalSemuas, 0, ',', '.') }}</h5>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p style="font-size: large">Voucher : </p>
                            @if ($transaksi->voucher_digunakan)
                                <h5>{{ $transaksi->voucher_digunakan }}</h5>
                            @else
                                <h5>Tidak ada Voucher yang digunakan</h5>
                            @endif
                        </div>
                    @endif
                    <div class="d-flex justify-content-between">
                        <p style="font-size: large">Total Semua : </p>
                        <h5>Rp. {{ number_format($transaksi->total_semua, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
