<nav class="navbar navbar-light bg-light" style="background-size: 100%; height: 100px;">
    <div class="container">
        @if (auth()->user()->role == 'pustakawan')
            <a class="navbar-brand p-2" href="{{ route('home') }}">
                <h4>
                    <span style="color: rgb(255, 215, 0); font-weight: 600;">Flybook</span> Indonesia
                </h4>
            </a>
        @elseif (auth()->user()->role == 'admin')
            <a class="navbar-brand p-2" href="{{ route('home.admin') }}">
                <span style="color: rgb(255, 215, 0); font-weight: 600;">Flybook</span> Indonesia
            </a>
        @elseif (auth()->user()->role == 'owner')
            <a class="navbar-brand p-2" href="{{ route('home.owner') }}">
                <span style="color: rgb(255, 215, 0); font-weight: 600;">Flybook</span> Indonesia
            </a>
        @endif

        <div class="d-flex">
            @if (auth()->user()->role == 'pustakawan')
                <a href="{{ route('keranjang') }}" class="nav-link text-dark">Keranjang</a>
                <a href="{{ route('history') }}" class="nav-link text-dark">History Pembelian</a>
            @endif
            @if (auth()->user()->role == 'owner')
                <a href="{{ route('home.owner') }}" class="nav-link text-dark">Data Penjualan</a>
            @endif
            @if (auth()->user()->role == 'admin')
                <a href="{{ route('home.admin') }}" class="nav-link text-dark">Kelola Buku</a>
            @endif
            <a href="{{ route('log') }}" class="nav-link text-dark">Aktivitas Terakhir</a>
            <a href="{{ route('logout') }}" class="nav-link text-light"
                style="background-color: rgb(255, 215, 0); border-radius: 2px;"
                onclick="return confirm('Yakin akan logout?')">Logout</a>
        </div>
    </div>
</nav>
