<!DOCTYPE html>

<head>
    <title>Document</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .invoice {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .invoice-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .invoice-info div {
        width: 48%;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .invoice-table th,
    .invoice-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    .invoice-total {
        margin-top: 20px;
        text-align: right;
    }

    .footer {
        margin-top: 20px;
        text-align: center;
    }
</style>

<body>
    <div class="invoice">
        <div class="header">
            <h1>Invoice</h1>
        </div>

        <div class="invoice-info">
            <div>
                <p>Invoice : <strong>{{ $data->invoice }}</strong></p>
                <p>Tanggal : <strong>{{ $data->updated_at }}</strong></p>
                <p>Pembeli : <strong>{{ $data->nama_pembeli }}</strong></p>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga Barang</th>
                    <th>Jumlah Barang</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->detailtransaksi as $item)
                    <tr>
                        <td>
                            {{ $item->book->judul_buku }}
                        </td>
                        <td>
                            Rp. {{ number_format($item->book->harga_buku, 2, ',', '.') }}
                        </td>
                        <td>
                            {{ $item->qty }}
                        </td>
                        <td>
                            Rp. {{ number_format($item->book->harga_buku * $item->qty, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $totalSemua = 0;

            foreach ($data->detailtransaksi as $dtl) {
                $hargaAwal = $dtl->book->harga_buku * $dtl->qty;
                $totalSemua += $hargaAwal;
            }
        @endphp
        <div class="invoice-total">
            <p><strong>Total: Rp. {{ number_format( $totalSemua, 2, ',', '.') }}</strong></p>
        </div>

        <div class="footer">
            <p>Terimakasih untk pembelian Anda!</p>
        </div>
    </div>
</body>

</html>
