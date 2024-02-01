<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>History - {{ auth()->user()->name }}</title>
</head>

<body>
    @php
        $jumlahSemua = 0;
        foreach ($transaksi as $trn) {
            foreach ($trn->detailtransaksi as $dtl) {
                $jumlahSemua += $dtl->qty;
            }
        }
    @endphp
    @include('layout.nav')
    <div class="container mt-5">
        <h2>History Pembelian</h2>
        <div class="row mt-5">
            @foreach ($transaksi as $item)
                <div class="col-6 mt-3">
                    <div class="card">
                        <div class="d-flex">
                            <img src="{{ asset($item->detailtransaksi->first()->book->sampul_buku) }}" style="width: 130px; height: 210px; object-fit: cover;" alt=""
                                class="card-img-top">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <p style="font-size: large">{{ $item->nama_pembeli }} <span style="font-weight: 500">({{ $item->created_at }})</span></p>
                                    <p style="background-color: rgb(43, 67, 226); padding: 7px; border-radius: 2px; position: absolute; right: 0; top: 0;"
                                        class="text-white">{{ $item->invoice }} </p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Jumlah Semua Barang : </p>
                                    <p style="font-weight: 500;">{{ $jumlahSemua }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Total Harga : </p>
                                    <p style="font-weight: 500;">Rp. {{ number_format($item->total_semua, 2, ',', '.') }}
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Uang Dibayarkan : </p>
                                    <p style="font-weight: 500;">Rp. {{ number_format($item->uang_bayar, 2, ',', '.') }}</p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
