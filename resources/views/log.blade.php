<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log - {{ auth()->user()->name }}</title>
    
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">

    <script src="{{ asset('js/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>


   @if (auth()->user()->role == 'pustakawan')
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
        background-image: url('{{ asset('other_image/bg-perpis.jpg') }}');
        background-size: cover;
        background-repeat: no-repeat;
        opacity: 0.2;
        z-index: -1;
        
    }
</style>
   @endif
</head>

<body>
    @if (auth()->user()->role == 'pustakawan')
        @include('layout.nav')
    @else
        @include('layout.sidebar')
    @endif
    <div class="container">
        @if (Session::has('err'))
            <p class="alert alert-warning">{{ Session::get('err') }}</p>
        @endif
    </div>
    <div class="container d-flex mt-5"
        style="background-color:  rgb(246, 246, 246); padding: 30px; border-radius: 2px;">
        <table class="table w-75 mx-auto">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama User</th>
                    <th>Aktivitas Yang Dilakukan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($log as $item)
                    <tr>
                        <td>{{ $item->createdAt }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->aktivitas }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <form action="{{ route('filtered-log') }}" method="get" class="form-group w-20 mt-5">
            <div class="d-flex justify-content-between gap-2">
                <label for="from">From</label>
                <input type="date" class="form-control w-75" name="dateFrom" required>
            </div>
            <div class="d-flex justify-content-between gap-2 mt-3">
                <label for="to">To</label>
                <input type="date" name="dateTo" class="form-control w-75" required>
            </div>
            <button class="btn btn-warning w-100 mt-3" style="border-radius: 2px;">Fitler</button>

        </form>
    </div>

    <script>
        new DataTable('#logTable');
    </script>
</body>

</html>
