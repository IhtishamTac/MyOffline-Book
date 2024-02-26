<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice</title>
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
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background-color: rgb(243, 243, 243);
            padding: 0 20px;
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
            border: 1px solid #000000;
            padding: 12px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f2f2f2;
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
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div>
                <h1><span style="color: rgb(35, 46, 255)">Flybook</span> Indonesia</h1>
                <p style="margin-top: -20px; color: rgb(5, 69, 90);">flybook@comp.id</p>
            </div>
        </div>
        <hr>
        <div class="invoice-info">
            <div>
                <p>Kode : <strong>{{ $data->invoice }}</strong></p>
                <p>Tanggal : <strong>{{ \Carbon\Carbon::parse($data->updated_at)->format('d F Y') }}</strong></p>
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
                        <td>{{ $item->book->judul_buku }}</td>
                        <td>Rp. {{ number_format($item->book->harga_buku, 2, ',', '.') }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>Rp. {{ number_format($item->book->harga_buku * $item->qty, 2, ',', '.') }}</td>
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
            <p><strong>Total: Rp. {{ number_format($totalSemua, 2, ',', '.') }}</strong></p>
        </div>

        <div class="footer">
            <p>Terimakasih untuk pembelian Anda!</p>
            <p>Hubungi kami di <strong><span style="color: rgb(18, 64, 216);">support@flybook.id</span></strong> atau <strong>+62 230-2342-4678</strong></p>
        </div>
    </div>
</body>
</html>
