<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preload" as="image" href="{{ asset('other_image/bg-perpis.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>History - {{ auth()->user()->name }}</title>
</head>
<style>
    .card-history {
        text-decoration: none;
        color: black;
        transition: transform .2s;
    }

    .card-history:hover {
        text-decoration: underline;
        transform: scale(1.2);
    }

    .nav-kasir {
        background-color: rgb(0, 76, 255);
        border-radius: 2px;
    }

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

    .wraps {
        padding-bottom: 200px;
        background-color: rgb(251, 251, 251);
        padding: 40px;
        border-radius: 5px;
    }
</style>

<body>

    @include('layout.nav')
    <div class="container mt-5 wraps">
        <div class="d-flex justify-content-between">
            <h2>History Pembelian</h2>
            <input type="search" class="form-control p-3 w-25" style="height: 40px;" name="searchHistory"
                id="searchHistory" placeholder="Cari Transaksi...">
        </div>
        <div class="row mt-5" id="searchHistoryResult">
            @foreach ($transaksi as $item)
                @php
                    $jumlahSemua = 0;
                    foreach ($item->detailtransaksi as $dtl) {
                        $jumlahSemua += $dtl->qty;
                    }
                @endphp
                <div class="col-6 mt-3">
                    <a href="{{ route('detail-history', $item->id) }}" class="card-history">
                        <div class="card">
                            <div class="d-flex">
                                <img src="{{ asset($item->detailtransaksi->first()->book->sampul_buku) }}"
                                    style="width: 130px; height: 210px; object-fit: cover;" alt=""
                                    class="card-img-top">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p style="font-size: large">{{ $item->nama_pembeli }} <span
                                                style="font-weight: 500">({{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }})</span></p>
                                        <p style="padding: 7px; border-radius: 2px; position: absolute; right: 0; top: 0;"
                                            class="text-white bg-success">{{ $item->invoice }} </p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p>Jumlah Semua Barang : </p>
                                        <p style="font-weight: 500;">{{ $jumlahSemua }}</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p>Total Harga : </p>
                                        <p style="font-weight: 500;">Rp.
                                            {{ number_format($item->total_semua, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p>Uang Dibayarkan : </p>
                                        <p style="font-weight: 500;">Rp.
                                            {{ number_format($item->uang_bayar, 0, ',', '.') }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>

    <div style="margin-top: 400px;">
        @include('layout.footer')

    </div>
    <script src="{{ asset('js/jquery-3.7.0.js') }}"></script>
    <script>
        $(document).ready(() => {
            $('#searchHistory').on('input', () => {
                var query = $('#searchHistory').val();

                $.ajax({
                    url: '/pustakawan/search-history',
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: ((data) => {
                        $('#searchHistoryResult').html(data);
                        // data = JSON.parse(data); 
                        // let transaksi = data.transaksi;
                        // console.log(transaksi);
                    }),
                    error: ((error) => {
                        console.error('Error:', error);
                    }),
                });
            });
        });
    </script>
</body>

</html>
