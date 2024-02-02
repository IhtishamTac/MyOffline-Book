<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Detail History - {{ auth()->user()->name }}</title>
</head>

<body>
    @include('layout.nav')
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
                    <h3 style="font-weight: 350;">Pembeli : <span style="font-weight: 500;">{{ $transaksi->nama_pembeli }}</span></h3>
                    <p style="background-color: rgb(43, 67, 226); padding: 7px; border-radius: 2px; font-size: large; height: 40px"
                        class="text-white"> {{ $transaksi->invoice }} </p>
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
                                        Rp. {{ number_format($item->book->harga_buku, 2, ',', '.') }};
                                    </td>
                                    <td>
                                        {{ $item->qty }}
                                    </td>
                                    <td>
                                        Rp.
                                        {{ number_format($item->book->harga_buku * 4, 2, ',', '.') }};
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
