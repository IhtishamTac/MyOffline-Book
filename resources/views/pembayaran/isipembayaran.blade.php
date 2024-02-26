<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Isi Pembayaran</title>
</head>
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
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-center">
            <div class="col-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('icon_assets/qrislogo.png') }}" alt="qrislogo">
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" placeholder="Isi Nominal..." id="nominalInput">
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-success w-100" style="border-radius: 2px;">Bayar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function inputNominal() {
            
        }
    </script>
</body>

</html>
