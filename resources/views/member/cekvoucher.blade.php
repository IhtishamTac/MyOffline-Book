<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Member - Index</title>

    <style>
        body {
            background-image: url('other_image/bg-white.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
    @include('sweetalert::alert')

    @include('layout.nav')
    <div class="container mt-5">
        <div class="col-4 mx-auto">
            <div class="card shadow" style="border-radius: 2px;">
                <div class="card-body">
                    <h3 class="m-3">
                        Masukan kode member
                    </h3>
                    <form action="{{ route('member.profile') }}" class="form-group">
                        <div class="mt-3">
                            <input type="text" name="kode_unik" class="form-control" required>
                        </div>
                        <div class="mt-3 d-flex gap-1">
                            <button class="btn btn-primary w-75" style="border-radius: 2px;">Cari Member</button>
                            <a href="{{ route('login') }}" class="btn btn-secondary w-25"
                                style="border-radius: 2px;">Batal</a>
                        </div>
                        @if (Session::has('message'))
                            <p class="alert alert-danger mt-3">{{ Session::get('message') }}</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
