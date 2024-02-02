<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class PdfController extends Controller
{
    public function printDetailPembelian($id){
        $transaksiData = Transaksi::where('id', $id)->first();
        $data = [
            'data' => $transaksiData
        ];
        $pdf = Pdf::loadView('template-pdf.print-semua-pembelian', $data);
        return $pdf->download($transaksiData->invoice . '.pdf');
    }
}
