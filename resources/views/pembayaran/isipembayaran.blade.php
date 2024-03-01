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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <form action="{{ route('sukses-bayar') }}">
                            <div class="text-center">
                                <img src="{{ asset('icon_assets/qrislogo.png') }}" alt="qrislogo">
                            </div>
                            <div class="mt-3">
                                <input type="number" min="1" class="form-control" name="nominal"
                                    placeholder="Isi Nominal..." id="nominalInput">
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-success w-100" onclick="inputNominal()"
                                    style="border-radius: 2px;" id="btnBayar" disabled>Bayar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById('nominalInput');
            var btnBayar = document.getElementById('btnBayar');

            input.addEventListener('input', (() => {
                updateTotal();
            }));

            function updateTotal() {
                var input = document.getElementById('nominalInput');
                var uangDibayarkan = parseInt(input.value) || 0;

                if (uangDibayarkan < 1) {
                    btnBayar.disabled = true;
                } else {
                    btnBayar.disabled = false;
                }
            }

        });
    </script>
</body>

</html>
