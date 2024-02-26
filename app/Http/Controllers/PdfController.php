<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use OpenSpout\Common\Entity\Style\Style;
use Rap2hpoutre\FastExcel\FastExcel;

class PdfController extends Controller
{
    public function printDetailPembelian($id)
    {
        $transaksiData = Transaksi::where('id', $id)->first();
        $data = [
            'data' => $transaksiData
        ];
        $pdf = Pdf::loadView('template-pdf.print-semua-pembelian', $data);
        return $pdf->download($transaksiData->invoice . '.pdf');
    }
    public function printTransactionByDate(Request $request) {
        
    }
    public function printOwnerAllTransaction()
    {
        $transaksi = Transaksi::where('status', 'Dibayar')->get();
        $header_style = (new Style())->setFontBold();

        $rows_style = (new Style())
            // ->setFontSize(15)
            // ->setShouldWrapText()
            ->setBackgroundColor("EDEDED");
        return (new FastExcel($transaksi))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->download('DataTransaksi.xlsx');
    }
}
