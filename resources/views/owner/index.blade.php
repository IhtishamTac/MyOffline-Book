<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Home - {{ auth()->user()->name }}</title>

    <style>
        body{
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
                <div class="row">
                    <div class="col-8">
                        {!! $chart->container() !!}

                    </div>
                    <div class="col-4">
                        <form action="{{ route('filteredhome.owner') }}" method="get" class="form-group">
                            <div class="d-flex justify-content-between gap-2">
                                <label for="from">From</label>
                                <input type="date" class="form-control w-75" name="dateFrom" required>
                            </div>
                            <div class="d-flex justify-content-between gap-2 mt-3">
                                <label for="to">To</label>
                                <input type="date" name="dateTo" class="form-control w-75" required>
                            </div>
                            <button class="btn btn-warning w-100 mt-3" style="border-radius: 2px;">Fitler</button>
                            @if (Session::has('err'))
                                <p class="alert alert-warning">{{ Session::get('err') }}</p>
                            @endif
                        </form>
                        <div class="mt-3 d-flex justify-content-between">
                            <p>Total Pendapatan : </p>
                            <h3>Rp. {{ number_format($total_pendapatan, 2, ',', '.') }}</h3>
                        </div>
                        {!! $pieChart->container() !!}
                    </div>
                </div>


            </div>
        </div>
    </div>


    <script src="{{ $chart->cdn() }}"></script>
    <script src="{{ $pieChart->cdn() }}"></script>

    {{ $chart->script() }}
    {{ $pieChart->script() }}

</body>

</html>
