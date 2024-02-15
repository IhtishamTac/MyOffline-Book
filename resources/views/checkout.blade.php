<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
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
            background-image: url('{{ asset('other_image/bg-perpis.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            opacity: 0.2;
            z-index: -1;
        }
    </style>
</head>

<body>
    @php
        $totalSemua = 0;

        foreach ($transaksi as $item) {
            foreach ($item->detailtransaksi as $dtl) {
                $hargaAwal = $dtl->book->harga_buku * $dtl->qty;
                $totalSemua += $hargaAwal;
            }
        }

    @endphp
    <div class="container mt-5">
        <a href="{{ route('home') }}" class="btn btn-warning text-light"
            style="position: absolute; border-radius: 2px; left: 200px;">Kembali</a>
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
                                    {{ number_format($totalSemua, 2, ',', '.') }}</span></p>
                            <button id="btnCheckout" onclick="updateTotal()"
                                style="background-color: rgb(255, 221, 0); border-radius: 2px; width: 100px;font-size: large;"
                                class="btn text-black" disabled>Bayar</button>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <input type="text" class="form-control w-50" name="uang_dibayarkan"
                            placeholder="Uang dibayarkan..." id="uangDibayarkan">
                        <p>Uang Kembalian : <span style="font-weight: 500" id="uangKembali">Rp. 0</span></p>
                    </div>
                    <div class="mt-3">
                        <input type="text" class="form-control" placeholder="Nama Pembeli" name="nama_pembeli" onchange="updateTotal()"
                            required>
                    </div>

                    <input type="hidden"name="kode_member" id="hiddenKodeMember">
                    <input type="hidden"name="id_voucher" id="hiddenIdVoucher">
                </form>
                <form action="{{ route('vouchers') }}">
                    <div class="mt-3">
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control w-75" placeholder="Kode Unik Member"
                                name="kode_member" id="kodeMember" onchange="setCookieMember(this.value)" id="inputKodeMember">
                            <button class="btn btn-warning w-25" id="btnCariMember">Cari Member</button>
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
                        @if (Session::has('err'))
                            <p class="alert alert-danger mt-2">{{ Session::get('err') }}</p>
                        @endif
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table">
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
                                        Rp. {{ number_format($detail->book->harga_buku, 2, ',', '.') }};
                                    </td>
                                    <td>
                                        {{ $detail->qty }}
                                    </td>
                                    <td>
                                        Rp.
                                        {{ number_format($detail->book->harga_buku * $detail->qty, 2, ',', '.') }};
                                    </td>
                                    <td>
                                        <a href="{{ route('hapuskeranjang', $detail->id) }}" class="btn btn-danger"
                                            style="border-radius: 2px;">Hapus</a>

                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
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

            if (uangDibayarkan < (totalSemua - potongan_harga)) {
                lblKembali.textContent = "Uang tidak cukup";
                lblTotal.textContent = 'Rp. ' + newTotal.toFixed(2);
                btnCheckout.disabled = true;
            } else {
                lblKembali.textContent = 'Rp. ' + kembali.toFixed(2);
                lblTotal.textContent = 'Rp. ' + newTotal.toFixed(2);
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

            inputKodeMember.value = kodeMembe;
        });
    </script>
</body>

</html>
