<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OwnerController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::where('status', 'Dibayar')->get();

        $tanggal = $transaksi->pluck('updated_at')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d-m-Y');
        })->toArray();

        $profit = $transaksi->pluck('total_semua')->toArray();

        $chart = (new LarapexChart)->setType('area')
            ->setTitle('Book sales')
            ->setSubtitle('From Transactions this day')
            ->setXAxis($tanggal)
            ->setDataset([
                [
                    'name'  =>  'Income',
                    'data'  =>  $profit
                ]
            ]);

        return view('owner.index', compact('chart'));
    }

    public function filteredChart(Request $request){
        if($request){
            $valid = Validator::make($request->all(), [
                'dateFrom' => 'required|date',
                'dateTo' => 'required|date|after_or_equal:dateFrom',
            ]);
            if($valid->fails()){
                return redirect()->back()->with('err', $valid->errors());
            }
        }
        
        if (!$request->dateFrom && !$request->dateTo) {
            $transaksi = Transaksi::where('status', 'Dibayar')->get();
        }
        if ($request->dateFrom && $request->dateTo) {
            $transaksi = Transaksi::where('status', 'Dibayar')
                ->whereDate('updated_at', '>=', Carbon::parse($request->dateFrom)->startOfDay())
                ->whereDate('updated_at', '<=', Carbon::parse($request->dateTo)->endOfDay())
                ->get();
        }

        $tanggal = $transaksi->pluck('updated_at')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d-m-Y');
        })->toArray();

        $profit = $transaksi->pluck('total_semua')->toArray();

        $chart = (new LarapexChart)->setType('area')
            ->setTitle('Book sales')
            ->setSubtitle('From Transactions this day')
            ->setXAxis($tanggal)
            ->setDataset([
                [
                    'name'  =>  'Income',
                    'data'  =>  $profit
                ]
            ]);

        return view('owner.index', compact('chart'));
    }
}
