<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">


    <title>Checkout - {{ auth()->user()->name }}</title>

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
</head>

<body>
    @php
        $totalSemua = 0;
        // $nominal = cache()->get('nominal') || 0;
        $NamaMember = null;
        if(!empty($memberName)){
            $NamaMember = $memberName;
        }

        foreach ($transaksi as $item) {
            foreach ($item->detailtransaksi as $dtl) {
                $hargaAwal = $dtl->book->harga_buku * $dtl->qty;
                $totalSemua += $hargaAwal;
            }
        }

    @endphp

    @include('layout.nav');
    @include('sweetalert::alert')

    <div class="container mt-5" style="padding-bottom: 200px;">
        {{-- <a href="{{ route('home') }}" class="btn btn-warning text-light"
            style="position: absolute; border-radius: 2px; left: 200px;">Kembali</a> --}}
        <div class="card">
            <div class="card-header">

                <form action="{{ route('postcheckout', ['tranID' => json_encode($transaksi->pluck('id'))]) }}"
                    method="POST">
                    @csrf
                    <div class="d-flex justify-content-between">
                        <h3>Checkout Barang Anda</h3>
                        <div class="d-flex">
                            <p style="margin-top: 10px; font-size: large;">Total Semua Buku : <span
                                    style="font-weight: 500; margin-right: 10px;" id="totalHarga">Rp.
                                    {{ number_format($totalSemua, 0, ',', '.') }}</span></p>
                            <button id="btnCheckout" type="submit" onclick="return confirmBayar()"
                                style=" border-radius: 2px; width: 100px;font-size: large;"
                                class="btn btn-success text-white" disabled>Bayar</button>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <div class="d-flex">
                            <input type="number" min="1" class="form-control" name="uang_dibayarkan"
                                placeholder="Uang dibayarkan..." id="uangDibayarkan" >
                            {{-- qris button --}}
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalQris"
                                class="btn btn-secondary w-50" style="border-radius: 2px;">Show QRis</button>
                            {{-- qris qrcode --}}
                            <div class="modal fade" id="modalQris">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <img src="{{ asset('icon_assets/qrislogo.png') }}" alt="qrislogo">
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex justify-content-center">
                                                {!! QrCode::size(400)->generate('http://192.168.1.12:8000/pembayaran-qris') !!}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary w-50" style="border-radius: 2px;"
                                                data-bs-dismiss="modal">Batalkan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- qris qrcode --}}
                        </div>
                        <p>Uang Kembalian : <span style="font-weight: 500" id="uangKembali">Rp. 0</span></p>
                    </div>
                    <div class="mt-3">
                        <input type="text" class="form-control" placeholder="Nama Pembeli" name="nama_pembeli"
                        value="{{ $NamaMember }}"
                            onchange="updateTotal()" required>
                    </div>

                    <input type="hidden"name="kode_member" id="hiddenKodeMember">
                    <input type="hidden"name="id_voucher" id="hiddenIdVoucher">
                </form>
                <form action="{{ route('vouchers') }}">
                    <div class="mt-3">
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control w-75" placeholder="Kode Unik Member"
                                name="kode_member" id="kodeMember" onchange="setCookieMember(this.value)"
                                id="inputKodeMember">
                            <button class="btn btn-primary w-25" id="btnCariMember">Cari Member</button>
                        </div>
                        <select name="voucher" class="form-select mt-2" onchange="discount(this.value)">
                            @if ($inventory)
                                <option disabled selected>Pilih Voucher</option>
                                @foreach ($inventory as $item)
                                    <option value="{{ $item->voucher->id }}_{{ $item->voucher->potongan_harga }}">
                                        {{ $item->voucher->nama_voucher }}
                                        <span>({{ $item->voucher->potongan_harga }}%)</span>
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled selected>Tidak Ada Voucher</option>
                            @endif
                        </select>

                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table" id="tabelKeranjang">
                    <thead>
                        <tr>
                            <th>
                                Gambar Barang
                            </th>
                            <th>
                                Nama Barang
                            </th>
                            <th>
                                Harga Barang
                            </th>
                            <th>
                                Jumlah Barang
                            </th>
                            <th>
                                Total Harga
                            </th>
                            <th>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi as $item)
                            @foreach ($item->detailtransaksi as $detail)
                                <tr>
                                    <td>
                                        <img src="{{ asset($detail->book->sampul_buku) }}"
                                            style="width: 70px; height: 110px; object-fit: cover;" alt="">
                                    </td>
                                    <td>
                                        {{ $detail->book->judul_buku }}
                                    </td>
                                    <td>
                                        Rp. {{ number_format($detail->book->harga_buku, 0, ',', '.') }};
                                    </td>
                                    <td>
                                        {{ $detail->qty }}
                                    </td>
                                    <td>
                                        Rp.
                                        {{ number_format($detail->book->harga_buku * $detail->qty, 0, ',', '.') }};
                                    </td>
                                    <td>
                                        <a href="{{ route('hapuskeranjang', $detail->id) }}" class="btn btn-danger"
                                            style="border-radius: 2px;"
                                            onclick="return confirm('Yakin akan menghapus?')">Hapus</a>

                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('layout.footer')

    <script src="{{ asset('js/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
        new DataTable("#tabelKeranjang");
        let potongan_harga = 0;
        let totalSemua = {{ $totalSemua }};

        function discount(selectedValue) {
            var inputKodeMember = document.getElementById('kodeMember');
            var hiddenKodeMember = document.getElementById('hiddenKodeMember');
            var hiddenIdVoucher = document.getElementById('hiddenIdVoucher');
            var newSelectValue = selectedValue.split('_');
            potongan_harga = newSelectValue[1] / 100 * totalSemua;

            hiddenIdVoucher.value = newSelectValue[0];
            hiddenKodeMember.value = inputKodeMember.value;
            updateTotal();
        }

        function updateTotal() {
            var input = document.getElementById('uangDibayarkan');
            var lblKembali = document.getElementById('uangKembali');
            var lblTotal = document.getElementById('totalHarga');
            var btnCheckout = document.getElementById('btnCheckout');

            var uangDibayarkan = parseInt(input.value) || 0;
            var kembali = uangDibayarkan - (totalSemua - potongan_harga);
            var newTotal = totalSemua - potongan_harga;

            if(newTotal == 0){
                lblTotal.textContent = "Mohon masukan minimal 1 barang";
                input.disabled = true;
            }else if (uangDibayarkan < (totalSemua - potongan_harga)) {
                lblKembali.textContent = "Uang tidak cukup";
                lblTotal.textContent = 'Rp. ' + newTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                btnCheckout.disabled = true;
            } else {
                lblKembali.textContent = 'Rp. ' + kembali.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                lblTotal.textContent = 'Rp. ' + newTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                btnCheckout.disabled = false;
            }
        }

        function setCookieMember(inputValue) {
            localStorage.setItem('kodeMember', inputValue);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById('uangDibayarkan');

            input.addEventListener('input', (() => {
                updateTotal();
            }));

            var inputKodeMember = document.getElementById('kodeMember');
            const kodeMembe = localStorage.getItem('kodeMember');
            const inputNominal = {{ cache()->get('nominal') ?? 0 }};
            inputKodeMember.value = kodeMembe;
            input.value = inputNominal;
            updateTotal();
        });

        function confirmBayar() {
            var confirmation = confirm('Yakin akan melanjutkan?');

            if (confirmation) {
                localStorage.removeItem('kodeMember');
                
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>

</html>
