<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Book;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\Log;
use App\Models\Member;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherInventory;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::where('status', 'Dijual')->where('stok', '>', 0)->latest()->get();
        $kategoris = Kategori::paginate(5);
        return view('welcome', compact('books', 'kategoris'));
    }

    public function caribuku(Request $request)
    {
        $searchTerm = $request->input('query');
        if ($searchTerm) {
            $books = Book::where('judul_buku', 'LIKE', "%{$searchTerm}%")->where('status', 'Dijual')->where('stok', '>', 0)->get();
        } else {
            $books = Book::where('status', 'Dijual')->where('stok', '>', 0)->get();
        }

        return view('ajax-template.book-card-home', compact('books'));
    }

    public function cariBukuKategori(Request $request)
    {
        $searchTerm = $request->input('query');
        $lowercaseQuery = strtolower($searchTerm);
        if ($searchTerm) {
            $kategori = Kategori::where('nama_kategori', 'LIKE', "%{$lowercaseQuery}%")
                ->with([
                    'books' => function ($bookQuery) {
                        $bookQuery->where('status', 'Dijual');
                    }
                ])->get();

            foreach ($kategori as $kat) {
                $books = $kat->books;
            }
        } else {
            $books = Book::where('status', 'Dijual')->where('stok', '>', 0)->get();
        }

        return view('ajax-template.book-card-home', compact('books'));
    }

    public function log()
    {
        if (auth()->user()->role == 'owner') {
            $log = Log::latest()->get();
        } else if (auth()->user()->role == 'admin') {
            $adminUser = User::where(['role' => 'admin', 'id' => auth()->id()])->get();
            $adminId = $adminUser->pluck('id')->toArray();

            $pustaUser = User::where('role', 'pustakawan')->get();
            $pustaId = $pustaUser->pluck('id')->toArray();

            $log = Log::whereIn('user_id', array_merge($adminId, $pustaId))->latest()->get();
        } else {
            $log = Log::where('user_id', auth()->id())->with('user')->latest()->get();
        }
        foreach ($log as $lo) {
            $lo->createdAt = Carbon::parse($lo->created_at)->format('h:i:s d-M-Y');
        }
        return view('log', compact('log'));
    }

    public function filteredLog(Request $request)
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

        if (auth()->user()->role == 'owner') {
            $log = Log::whereDate('created_at', '>=', Carbon::parse($request->dateFrom)->startOfDay())
                ->whereDate('created_at', '<=', Carbon::parse($request->dateTo)->endOfDay())
                ->latest()
                ->get();
        } else if (auth()->user()->role == 'admin') {
            $adminUser = User::where(['role' => 'admin', 'id' => auth()->id()])->get();
            $adminId = $adminUser->pluck('id')->toArray();

            $pustaUser = User::where('role', 'pustakawan')->get();
            $pustaId = $pustaUser->pluck('id')->toArray();

            $log = Log::whereIn('user_id', array_merge($adminId, $pustaId))
                ->whereDate('created_at', '>=', Carbon::parse($request->dateFrom)->startOfDay())
                ->whereDate('created_at', '<=', Carbon::parse($request->dateTo)->endOfDay())
                ->latest()
                ->get();
        } else {
            $log = Log::where('user_id', auth()->id())->with('user')
                ->whereDate('created_at', '>=', Carbon::parse($request->dateFrom)->startOfDay())
                ->whereDate('created_at', '<=', Carbon::parse($request->dateTo)->endOfDay())
                ->latest()
                ->get();
        }
        foreach ($log as $lo) {
            $lo->createdAt = Carbon::parse($lo->created_at)->format('h:i:s d-M-Y');
        }
        return view('log', compact('log'));
    }

    public function postkeranjang(Request $request, $id)
    {
        $book = Book::where('id', $id)->first();
        if($book->stok < $request->qty){
            Alert::error('Warning', 'Stok buku hanya tersisa ' . $book->stok.'!');
            return redirect()->back();
        }
        $cekTransaksi = Transaksi::where(['user_id' => auth()->id(), 'status' => 'Pending'])->with('detailtransaksi')->first();
        if ($cekTransaksi) {
            if ($detailTransaksi = $cekTransaksi->detailtransaksi->where('book_id', $id)->first()) {
                $detailTransaksi->qty = $detailTransaksi->qty + $request->qty;
                $detailTransaksi->save();
                toast('Dimasukan ke keranjang', 'success');
                return redirect()->back()->with('message', 'Dimasukan ke keranjang');
            } else {
                DetailTransaksi::create([
                    'transaksi_id' => $cekTransaksi->id,
                    'book_id' => $id,
                    'qty' => $request->qty
                ]);
                toast('Dimasukan ke keranjang', 'success');
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
            toast('Dimasukan ke keranjang', 'success');
            return redirect()->back()->with('message', 'Dimasukan ke keranjang');
        }
    }
    public function keranjang()
    {
        $inventory = [];
        $transaksi = Transaksi::where(['user_id' => auth()->id(), 'status' => 'Pending'])->with('detailtransaksi.book')->get();
        $transaksi = $transaksi->map(function ($item) {
            $item->detailtransaksi = $item->detailtransaksi->reverse();
            return $item;
        });
        return view('checkout', compact(['transaksi', 'inventory']));
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
        $diskon = 0;

        foreach ($transaksi as $item) {
            foreach ($item->detailtransaksi as $dtl) {
                $hargaAwal = $dtl->book->harga_buku * $dtl->qty;
                $totalSemua += $hargaAwal;

                if ($request->kode_member && $request->id_voucher) {
                    $kodeMember = $request->kode_member;
                    $idVoucher = $request->id_voucher;

                    $thisMember = Member::where('kode_unik', $kodeMember)->first();
                    $thisVoucher = Voucher::where('id', $idVoucher)->first();

                    $diskon = $thisVoucher->potongan_harga / 100 * $totalSemua;
                }

                $inv = 'INV' . Str::random(10);
                $item->invoice = $inv;
                $item->nama_pembeli = $request->nama_pembeli;
                $item->status = 'Dibayar';
                $item->total_semua = $totalSemua - $diskon;
                if ($request->kode_member && $request->id_voucher) {
                    $item->voucher_digunakan = $thisVoucher->nama_voucher . ' / ' . $thisVoucher->potongan_harga . '%';
                } else {
                    $item->voucher_digunakan = null;
                }
                $item->uang_bayar = (int)$request->uang_dibayarkan;
                $item->uang_kembali = (int)$request->uang_dibayarkan - ($totalSemua - $diskon);
                $item->created_at = Carbon::now();
                $item->save();

                $book = Book::where('id', $dtl->book->id)->first();
                $book->stok -= $dtl->qty;
                $book->save();
            }
        }


        if ($request->kode_member && $request->id_voucher) {
            VoucherInventory::where(['member_id' => $thisMember->id, 'voucher_id' => $thisVoucher->id])->delete();
        }

        Log::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Melakukan checkout untuk Pelanggan Bernama : ' . $request->nama_pembeli
        ]);
        Cache::forget('nominal');

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
        ])->with('detailtransaksi.book')->latest()->paginate(10);

        return view('history-pembelian', compact('transaksi'));
    }

    public function search_history(Request $request)
    {
        $inputSearch = $request->input('query');
        $transaksi = Transaksi::where(['user_id' => auth()->id(), 'status' => 'Dibayar'])
            ->where('invoice', 'LIKE', "%{$inputSearch}%")
            ->orWhere('nama_pembeli', 'LIKE', "%{$inputSearch}%")
            // ->where('updated_at','LIKE', "%{$inputSearch}%")
            ->with('detailtransaksi.book')
            ->latest()
            ->get();
        return view('ajax-template.card-history', compact(['transaksi']));
    }

    public function detailHistory($id)
    {
        $transaksi = Transaksi::where('id', $id)->with('detailtransaksi.book')->first();
        return view('detail-history-pembelian', compact('transaksi'));
    }

    public function vouchers(Request $request)
    {
        $member = Member::where('kode_unik', $request->kode_member)->first();
        if (!$member) {
            Alert::error('Member tidak ada!');
            return redirect()->back();
        }
        $memberName = $member->nama_member;
        $inventory = VoucherInventory::where('member_id', $member->id)->get();
        $transaksi = Transaksi::where(['user_id' => auth()->id(), 'status' => 'Pending'])->with('detailtransaksi.book')->get();
        return view('checkout', compact(['transaksi', 'inventory', 'memberName']));
    }
}
