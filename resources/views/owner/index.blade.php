<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">

    <script src="{{ asset('js/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

    <title>Home - {{ auth()->user()->name }}</title>

    <style>
        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-1">
            @include('layout.sidebar')
        </div>
        <div class="col-11">
            <div class="container mt-5">
                <div class="row gap-2">
                    <div class="col-7 shadow border p-3">
                        {!! $chart->container() !!}

                    </div>
                    <div class="col-4 shadow border p-3">
                        <form action="{{ route('filteredhome.owner') }}" method="get" class="form-group">
                            <div class="d-flex justify-content-between gap-2">
                                <label for="from">From</label>
                                <input type="date" class="form-control w-75" name="dateFrom" required>
                            </div>
                            <div class="d-flex justify-content-between gap-2 mt-3">
                                <label for="to">To</label>
                                <input type="date" name="dateTo" class="form-control w-75" required>
                            </div>
                            <div class="d-flex">
                                <button class="btn w-75 mt-3 text-white"
                                    style="border-radius: 2px; background-color: #7752FE;">Fitler</button>
                                <button class="btn w-25 mt-3 text-white"
                                    style="border-radius: 2px; background-color: #36c911;">Print</button>
                            </div>
                            @if (Session::has('err'))
                                <p class="alert alert-warning">{{ Session::get('err') }}</p>
                            @endif
                        </form>
                        <div class="mt-3 d-flex justify-content-between">
                            <p>Total Pendapatan : </p>
                            <h3>Rp. {{ number_format($total_pendapatan, 0, ',', '.') }}</h3>
                        </div>
                        {!! $pieChart->container() !!}
                    </div>
                </div>

                <div class="col-11 mt-4">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('print-semua-transaksi') }}" class="btn btn-warning w-25" style="border-radius: 2px;">Print semua Transaksi</a>
                    </div>
                    <table class="table table-hover table-responsive shadow border" id="ownerTable">
                        <thead class="bg-dark text-white p-3">
                            <tr>
                                <th>No</th>
                                <th>Nama Pembeli</th>
                                <th>Buku yang dibeli</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_pembeli }}</td>
                                    @php
                                        $nama_buku = [];
                                        $qty_buku = 0;
                                        $total_harga = 0;
                                        foreach ($item->detailtransaksi as $detail) {
                                            $nama_buku[] = $detail->book->judul_buku;
                                            $qty_buku += $detail->qty;
                                            $total_harga += $detail->book->harga_buku;
                                        }
                                        $new_nama_buku = implode(', ', $nama_buku);
                                    @endphp
                                    <td>{{ $new_nama_buku }}</td>
                                    <td>{{ $qty_buku }}</td>
                                    <td>Rp.{{ number_format($total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('print-detail-pembelian', $item->id) }}">
                                            <img src="{{ asset('icon_assets/icons8-print-40.png') }}" width="40px" alt="print">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 400px;">
        @include('layout.footer')
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    <script src="{{ $pieChart->cdn() }}"></script>

    {{ $chart->script() }}
    {{ $pieChart->script() }}
    <script>
        new DataTable('#ownerTable');
    </script>
</body>

</html>
