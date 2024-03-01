<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preload" as="image" href="{{ asset('other_image/bg-perpis.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Home - {{ auth()->user()->name }}</title>

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


        .wraps {
            padding-bottom: 100px;
            background-color: rgb(251, 251, 251);
            padding: 40px;
            border-radius: 5px;
        }

        .nav-kasir {
            background-color: rgb(255, 215, 0);
            border-radius: 2px;
        }
    </style>
</head>

<body>
    @include('sweetalert::alert')
    @include('layout.nav')
    <div class="container mt-5 wraps shadow" style="">

        <div class="d-flex justify-content-between">
            <h2>Pilih buku yang akan dipesan</h2>

            <div class="d-flex gap-2">
                <input type="text" id="searchKategori" name="searchKategori" list="optionsList" class="form-control"
                    placeholder="Cari Kategori">

                <datalist id="optionsList">
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->nama_kategori }}"/>
                    @endforeach
                </datalist>

                <input type="search" name="cariBuku" class="form-control" placeholder="Cari Buku" id="cariBuku">
            </div>
        </div>
        <div class="mt-4">
            <div class="row" id="cariBukuResult">
                @foreach ($books as $book)
                    {{-- Ini Awal Modal --}}
                    <div class="modal fade" id="modalKeranjang_{{ $book->id }}" style="margin-top: 300px;">
                        <div class="modal-dialog">
                            <div class="modal-content p-3">
                                <div class="modal-body">
                                    <div class="d-flex justify-content-between">
                                        <h3>{{ $book->judul_buku }}</h3>
                                        <p style="margin-top: 5px;">Stok : <span
                                                style="color: red; font-weight: 500;">{{ $book->stok }}</span></p>
                                    </div>
                                    <p
                                        style="white-space: nowrap; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $book->deskripsi }}</p>
                                    <form action="{{ route('post-keranjang', $book->id) }}" method="POST">
                                        @csrf
                                        <div class="d-flex justify-content-between mt-2 mb-2">
                                            <p style="margin-top: 4px;">Jumlah</p>
                                            <input type="number" value="1" min="1" name="qty"
                                                style="height: 35px;" class="form-control w-50">
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-success" style="border-radius: 2px;">
                                                Masukan keranjang
                                            </button>
                                            <h5 style="margin-top: 4px;">Rp.
                                                {{ number_format($book->harga_buku, 0, ',', '.') }}
                                            </h5>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary w-50" style="border-radius: 2px;"
                                        data-bs-dismiss="modal">Batalkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Ini Akhir modal  --}}
                    <div class="col-2 mt-3">
                        <a href="" data-bs-toggle="modal" data-bs-target="#modalKeranjang_{{ $book->id }}">
                            <img src="{{ asset($book->sampul_buku) }}" width="100px" height="290px"
                                style="object-fit: cover;" alt="sampul" class="card-img-top">
                            <p class="btn btn-success w-100" style="border-radius: 2px;">Pilih</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div style="margin-top: 400px;">
        @include('layout.footer')
    </div>

    <script src="{{ asset('js/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(() => {
            $('#cariBuku').on('input', () => {
                var query = $('#cariBuku').val();

                $.ajax({
                    url: '/pustakawan/caribuku',
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: ((data) => {
                        $('#cariBukuResult').html(data);
                    }),
                    error: ((error) => {
                        console.error('Error:', error);
                    }),
                });
            });

            $('#searchKategori').on('input', () => {
                var query = $('#searchKategori').val();

                $.ajax({
                    url: '/pustakawan/cari-buku-kategori',
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: ((data) => {
                        $('#cariBukuResult').html(data);
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
