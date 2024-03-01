<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Member - Profile</title>

    <style>
         body {
            background-image: url({{ asset('other_image/bg-white.jpg') }});
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
    @include('layout.nav');
    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-4">
                <div class="card shadow" style="border-radius: 2px;">
                    <div class="card-body">
                        <h3>{{ $member->nama_member }}</h3>
                        <p>{{ $member->no_telp }}</p>
                        <p style="font-size: large;">{{ $member->alamat }}</p>
                    </div>
                </div>
                <a href="{{ route('member.index') }}" class="btn btn-secondary w-100" style="border-radius: 2px;">Kembali</a>
            </div>
            <div class="col-5">
                <div class="card shadow" style="border-radius: 2px">
                    <div class="card-header">
                        <h3>Voucher yang dimiliki</h3>
                    </div>
                    @if ($voucher->count() <= 0)
                        <p class="p-3">Anda tidak memiliki voucher</p>
                    @endif
                    @foreach ($voucher as $item)
                        <div class="m-3 p-3" style="border-radius: 2px; background-color: rgb(246, 246, 246); border: 1px solid #591FCE;">
                            <div class="d-flex justify-content-between">
                                <h4>{{ $item->nama_voucher }}</h4>
                              
                                <h5 style="font-size: large; color: red; margin-top: 4px;">{{ $item->potongan_harga }}%</h5>
                            </div>
                            
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>

</html>
