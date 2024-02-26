<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Add Book - {{ auth()->user()->name }}</title>
</head>

<body>
    @include('layout.sidebar')
    <div class="container">
        <div class="col-6 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('post-add-book.admin') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <h2>Tambahkan Buku</h2>
                        <div class="mt-4">
                            <select name="kategori_id" id="kategori" class="form-select">
                                <option selected disabled>Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="sampul">Sampul Buku : </label>
                            <input type="file" name="sampul" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" name="judul" placeholder="Judul..." required>
                        </div>
                        <div class="mt-3">
                            <textarea name="deskripsi" style="resize: none;" class="form-control" cols="30" rows="3"
                                placeholder="Deskripsi singkat..." required></textarea>
                        </div>
                        <div class="mt-3">
                            <input type="number" name="harga" class="form-control" placeholder="Harga..." required>
                        </div>
                        <div class="mt-3">
                            <label for="stok">Stok Buku : </label>
                            <input type="number" name="stok" min="1" class="form-control" value="1"
                                required>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-success w-100" style="border-radius: 2px;">Tambahkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
