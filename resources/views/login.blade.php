<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Login</title>
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
    <div class="container mt-5">
        <div class="col-4 mx-auto">
            <img src="other_image/aranged-book.jpg" alt="top"
                style="width: 100%; height: 150px; object-fit: cover; object-position: bottom;">
            <div class="card shadow p-2" style="border-radius: 2px;">
                <div class="card-body">
                    <h2>
                        <span style="font-weight: 600;" class="text-primary">Flybook</span> Indonesia
                    </h2>
                    <form action="{{ route('postlogin') }}" class="form-group" method="POST">
                        @csrf
                        <div class="mt-4">
                            <p>Fill Your Credentials...</p>
                            <input type="text" placeholder="Username..." name="username" class="form-control" required
                                style="border-radius: 2px;">
                        </div>
                        <div class="mt-3">
                            <input type="password" name="password" placeholder="Password..." class="form-control" required
                                style="border-radius: 2px;">
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="form-check mt-2">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Remember me
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn w-100 text-white"
                                style="background-color: #03a9f4; border-radius: 2px; font-weight: 500;">Login</button>
                        </div>
                        <hr class="mt-4">
                        <div class="mt-4">
                            <a href="{{ route('member.index') }}" class="btn w-100 text-white"
                                style="background-color: #8c00ff; border-radius: 2px; font-weight: 500;">Cek Profil Member</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
