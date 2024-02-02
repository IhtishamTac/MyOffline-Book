<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Book;
use App\Models\DetailTransaksi;
use App\Models\Log;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::where('status', 'Dijual')->get();
        return view('welcome', compact('books'));
    }

    public function caribuku(Request $request)
    {
        $searchTerm = $request->input('cariBuku');
        if ($searchTerm) {
            $books = Book::where('judul_buku', 'LIKE', "%{$searchTerm}%")->get();
        } else {
            $books = Book::where('status', 'Dijual')->get();
        }

        return view('welcome', compact('books'));
    }

    public function log()
    {
        if(auth()->user()->role == 'owner'){
            $log = Log::all();
        }else if(auth()->user()->role == 'admin'){
            $adminUser = User::where(['role' => 'admin', 'id' => auth()->id()])->get();
            $adminId = $adminUser->pluck('id')->toArray();

            $pustaUser = User::where('role', 'pustakawan')->get();
            $pustaId = $pustaUser->pluck('id')->toArray();

            $log = Log::whereIn('user_id', array_merge($adminId, $pustaId))->get();
        }else{
            $log = Log::where('user_id', auth()->id())->with('user')->get();
        }
        return view('log', compact('log'));
    }

    public function postkeranjang(Request $request, $id)
    {
        $cekTransaksi = Transaksi::where(['user_id' => auth()->id(), 'status' => 'Pending'])->with('detailtransaksi')->first();
        if ($cekTransaksi) {
            if ($detailTransaksi = $cekTransaksi->detailtransaksi->where('book_id', $id)->first()) {
                $detailTransaksi->qty = $detailTransaksi->qty + $request->qty;
                $detailTransaksi->save();
                return redirect()->back()->with('message', 'Dimasukan ke keranjang');
            }else{
                DetailTransaksi::create([
                    'transaksi_id' => $cekTransaksi->id,
                    'book_id' => $id,
                    'qty' => $request->qty
                ]);
                return redirect()->back()->with('message', 'Dimasukan ke keranjang');
            }
        }
        if (!$cekTransaksi) {
            $createTran = Transaksi::create([
                'user_id' => auth()->id(),
                'status' => 'Pending'
            ]);
            DetailTransaksi::create([
                'transaksi_id' => $createTran->id,
                'book_id' => $id,
                'qty' => $request->qty
            ]);
            return redirect()->back()->with('message', 'Dimasukan ke keranjang');
        }

       
    }
    public function keranjang()
    {
        $transaksi = Transaksi::where(['user_id' => auth()->id(), 'status' => 'Pending'])->with('detailtransaksi.book')->get();
        return view('checkout', compact('transaksi'));
    }

    // public function checkout($tranID){
    //     $transacId = json_decode($tranID);
    //     $transaksi = Transaksi::whereIn('id', $transacId)->where('status', 'Pending')->with('book')->get();
    //     return view('checkout', compact('transaksi'));
    // }

    public function postcheckout(Request $request, $tranID)
    {
        $request->validate([
            'uang_dibayarkan' => 'required',
            'nama_pembeli' => 'required',
        ]);
        $transacId = json_decode($tranID);
        $transaksi = Transaksi::whereIn('id', $transacId)->with('detailtransaksi.book')->get();

        $totalSemua = 0;

        foreach ($transaksi as $item) {
            foreach ($item->detailtransaksi as $dtl) {
                $hargaAwal = $dtl->book->harga_buku * $dtl->qty;
                $totalSemua += $hargaAwal;

                $inv = 'INV' . Str::random(10);
                $item->invoice = $inv;
                $item->nama_pembeli = $request->nama_pembeli;
                $item->status = 'Dibayar';
                $item->total_semua = $totalSemua;
                $item->uang_bayar = (int)$request->uang_dibayarkan;
                $item->uang_kembali = (int)$request->uang_dibayarkan - $totalSemua;
                $item->created_at = Carbon::now();
                $item->save();

                $book = Book::where('id', $dtl->book->id)->first();
                $book->stok -= $dtl->qty;
                $book->save();
            }
        }

        Log::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Melakukan checkout untuk Pelanggan Bernama : ' . $request->nama_pembeli
        ]);

        return redirect()->route('home');
    }

    public function hapuskeranjang($id)
    {
        $keranjang = DetailTransaksi::where('id', $id)->first();
        if (!$keranjang) {
            return redirect()->back()->with('error', 'Failed to delete');
        }
        if ($keranjang->delete()) {
            return redirect()->back()->with('message', 'Berhasil dihapus');
        }
    }

    public function history()
    {
        $transaksi = Transaksi::where([
            'user_id' => auth()->id(),
            'status' => 'Dibayar'
        ])->with('detailtransaksi.book')->get();

        return view('history-pembelian', compact('transaksi'));
    }

    public function detailHistory($id)
    {
       $transaksi = Transaksi::where('id', $id)->with('detailtransaksi.book')->first();
        return view('detail-history-pembelian', compact('transaksi'));
    }
}
