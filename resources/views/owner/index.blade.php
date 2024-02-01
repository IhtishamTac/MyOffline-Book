<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Home - {{ auth()->user()->name }}</title>
</head>

<body>
    @include('layout.nav')



    <div class="container mt-5">
        
        <div class="row">
            <div class="col-8">
                {!! $chart->container() !!}

            </div>
            <div class="col-4">
                <form action="{{ route('filteredhome.owner') }}" method="get" class="form-group">
                        <div class="d-flex justify-content-between gap-2">
                            <label for="from">From</label>
                            <input type="date" class="form-control w-75"  name="dateFrom" required>
                        </div>
                        <div class="d-flex justify-content-between gap-2 mt-3">
                            <label for="to">To</label>
                            <input type="date" name="dateTo" class="form-control w-75" required>
                        </div>
                        <button class="btn btn-primary w-100 mt-3" style="border-radius: 2px;">Fitler</button>
                        @if ((Session::has('err')))
                        <p class="alert alert-warning">{{ Session::get('err') }}</p>
                        @endif
                </form>
            </div>
        </div>
    </div>

    <script src="{{ $chart->cdn() }}"></script>

    {{ $chart->script() }}
</body>

</html>
