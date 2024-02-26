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


    <title>Home - Admin</title>

    <style>
        body{
            overflow-x: hidden;
        }
    </style>
</head>

<body>
    @include('sweetalert::alert')
    <div class="row">
        <div class="col-1">
            @include('layout.sidebar')
        </div>
        <div class="col-11">
            <div class="container mt-5">
                <div class="d-flex justify-content-between mb-4">
                    <a href="{{ route('add-book.admin') }}" class="btn btn-success" style="border-radius: 2px;">Tambah
                        buku</a>
                    <form action="{{ route('filteredhome.admin') }}">
                        <select name="statusBuku" class="form-select" onchange="this.form.submit()"
                            style="border-radius: 2px;">
                            <option selected>Status Buku</option>
                            <option value="Dijual">Dijual</option>
                            <option value="Tidak Dijual">Tidak Dijual</option>
                        </select>
                    </form>
                </div>
                <table class="table" id="adminTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Sampul</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @if ($item->status == 'Dijual')
                                    <td><img src="{{ asset($item->sampul_buku) }}" width="80px" height="120px"
                                            style="object-fit: cover;" alt="sampul"></td>
                                @elseif($item->status == 'Tidak Dijual')
                                    <td><img src="{{ asset($item->sampul_buku) }}"
                                            style="filter: grayscale(100%); height: 120px; object-fit: cover;"
                                            width="80px" alt="sampul"></td>
                                @endif
                                <td>{{ $item->judul_buku }}</td>
                                <td>{{ $item->deskripsi }}</td>
                                <td>Rp. {{ number_format($item->harga_buku, 2, ',', '.') }}</td>
                                <td>{{ $item->stok }}</td>
                                <td style="display: flex; gap: 5px;" class="">
                                    
                                    <a href="{{ route('edit-book.admin', $item->id) }}" class="btn btn-warning"
                                        style="border-radius: 2px;">Edit</a>
                                    @if ($item->status == 'Dijual')
                                        <a href="{{ route('nonaktifkan-buku.admin', $item->id) }}"
                                            class="btn btn-danger" style="border-radius: 2px;" onclick="return confirm('Yakin akan menontaktifkan buku?')">Hapus</a>
                                    @elseif($item->status == 'Tidak Dijual')
                                        <a href="{{ route('aktifkan-buku.admin', $item->id) }}" class="btn btn-success" onclick="return confirm('Yakin akan mengaktifkan buku?')"
                                            style="border-radius: 2px;">Kembalikan</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 400px;">
                @include('layout.footer')
            </div>
            <script>
                new DataTable('#adminTable');
            </script>
        </div>
    </div>


</body>

</html>
