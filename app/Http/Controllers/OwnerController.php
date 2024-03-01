<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OwnerController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::where('status', 'Dibayar')->with('detailtransaksi.book')->latest()->get();

        $tanggal = $transaksis->pluck('updated_at')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d-m-Y');
        })->toArray();

        $profit = $transaksis->pluck('total_semua')->toArray();

        $total_pendapatan = array_sum($profit);

        $chart = (new LarapexChart)->setType('bar')
            ->setTitle('Book sales')
            ->setSubtitle('From Transactions this day')
            ->setXAxis($tanggal)
            ->setDataset([ 
                [
                    'name'  =>  'Income Rp. ',
                    'data'  =>  $profit
                ]
            ])
            ->setColors(['#0C9CEE']);

        $semuaKasir = User::where('role', 'pustakawan')->get();
        $idKasir = [];
        $transaksi = [];
        $namaKasir = [];
        $totalPerKasir = [];

        foreach ($semuaKasir as $kasirs) {
            $transaksi[] = Transaksi::where(['user_id' => $kasirs->id, 'status' => 'Dibayar'])->get();
            $idKasir[] = $kasirs->id;
            $namaKasir[] = $kasirs->name;
            $totalPerKasir[] = array_sum($transaksi[count($transaksi) - 1]->pluck('total_semua')->toArray());
        }

        $pieChart = (new LarapexChart)->setType('pie')
            ->setTitle('Active User')
            ->setSubtitle('Show the active Cashier and Admin')
            ->setDataset($totalPerKasir)
            ->setLabels($namaKasir)
            ->setColors([ '#190482', '#8E8FFA'])
            ->setFontColor('#000000');

        return view('owner.index', compact(['chart', 'pieChart', 'total_pendapatan', 'transaksis']));
    }

    public function filteredChart(Request $request)
    {
        if ($request) {
            $valid = Validator::make($request->all(), [
                'dateFrom' => 'required|date',
                'dateTo' => 'required|date|after_or_equal:dateFrom',
            ]);
            if ($valid->fails()) {
                return redirect()->back()->with('err', $valid->errors());
            }
        }
        if ($request->dateFrom && $request->dateTo) {
            $transaksis = Transaksi::where('status', 'Dibayar')
                // ->whereDate('updated_at', '>=', Carbon::parse($request->dateFrom)->startOfDay())
                // ->whereDate('updated_at', '<=', Carbon::parse($request->dateTo)->endOfDay())
                ->whereBetween('updated_at', [
                    Carbon::parse($request->dateFrom)->startOfDay(),
                    Carbon::parse($request->dateTo)->endOfDay()
                ])
                ->with('detailtransaksi.book')
                ->latest()
                ->get();
        }

        $tanggal = $transaksis->pluck('updated_at')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d-m-Y');
        })->toArray();

        $profit = $transaksis->pluck('total_semua')->toArray();

        $total_pendapatan = array_sum($profit);

        $chart = (new LarapexChart)->setType('bar')
            ->setTitle('Book sales')
            ->setSubtitle('From Transactions this day')
            ->setXAxis($tanggal)
            ->setDataset([
                [
                    'name'  =>  'Income Rp. ',
                    'data'  =>  $profit
                ]
            ])
            ->setColors(['#0C9CEE']);

        $semuaKasir = User::where('role', 'pustakawan')->get();
        $idKasir = [];
        $transaksi = [];
        $namaKasir = [];
        $totalPerKasir = [];

        foreach ($semuaKasir as $kasirs) {
            $transaksi[] = Transaksi::where(['user_id' => $kasirs->id, 'status' => 'Dibayar'])
                ->whereBetween('updated_at', [
                    Carbon::parse($request->dateFrom)->startOfDay(),
                    Carbon::parse($request->dateTo)->endOfDay()
                ])
                ->get();
            $idKasir[] = $kasirs->id;
            $namaKasir[] = $kasirs->name;
            $totalPerKasir[] = array_sum($transaksi[count($transaksi) - 1]->pluck('total_semua')->toArray());
        }

        $pieChart = (new LarapexChart)->setType('pie')
            ->setTitle('Active User')
            ->setSubtitle('Show the active Cashier and Admin')
            ->setDataset($totalPerKasir)
            ->setLabels($namaKasir)
            ->setColors([ '#190482', '#8E8FFA'])
            ->setFontColor('#000000');

        return view('owner.index', compact(['chart', 'pieChart', 'total_pendapatan', 'transaksis']));
    }
}
