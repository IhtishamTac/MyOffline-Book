<div class="d-flex" id="wrapper" style="position: absolute; ">
    <!-- Sidebar -->

    <div class="bg-light" id="sidebar-wrapper" style="border-right: 1px solid lightgray; position: fixed;">
        <div class="d-flex flex-column justify-content-between" style="height: 100vh;">
            <div>
                <div class="sidebar-heading">
                    <h2 style="padding: 20px; padding-left: 30px; margin-top: 20px;">
                        <span style="color: rgb(0, 76, 255); font-weight: 600;">Flybook</span> Indonesia
                    </h2>
                </div>
            </div>

            <div>
                <div class="list-group list-group-flush">
                    @if (auth()->user()->role == 'owner')
                        <a href="{{ route('home.owner') }}" class="list-group-item list-group-item-action text-dark p-4"
                            style="font-size: larger; padding-left: 30px;">Data
                            Penjualan</a>
                    @endif
                    @if (auth()->user()->role == 'admin')
                        <a href="{{ route('homes.owner') }}" class="list-group-item list-group-item-action text-dark p-4"
                            style="font-size: larger; padding-left: 30px;">Dashboard</a>
                        <a href="{{ route('home.admin') }}" class="list-group-item list-group-item-action text-dark p-4"
                            style="font-size: larger; padding-left: 30px;">Kelola
                            Buku</a>
                    @endif
                    <a href="{{ route('log') }}" class="list-group-item list-group-item-action text-dark p-4"
                        style="font-size: larger; padding-left: 30px;">Aktivitas
                        Terakhir</a>
                    <a href="{{ route('logout') }}"
                        class="list-group-item list-group-item-action text-light p-4 bg-danger"
                        style="border-radius: 2px; font-size: larger; font-weight: 500"
                        onclick="return confirm('Yakin akan logout?')">Keluar</a>
                </div>
            </div>
        </div>

    </div>

</div>
