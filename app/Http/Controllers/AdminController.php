<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Kategori;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    public function index()
    {
        $books = Book::where('status', 'Dijual')->get();
        return view('admin.index', compact('books'));
    }

    public function bookTidakDijual(Request $request)
    {
        $books = Book::where('status', $request->statusBuku)->get();
        return view('admin.index', compact('books'));
    }

    public function addBook()
    {
        $kategori = Kategori::all();
        return view('admin.add-book', compact('kategori'));
    }

    public function postAddBook(Request $request)
    {
        if ($request) {
            $book = Book::create([
                'kategori_id' => $request->kategori_id,
                'sampul_buku' => $request->sampul->store('book_image'),
                'judul_buku' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'harga_buku' => $request->harga,
                'stok' => $request->stok,
            ]);

            if ($book) {
                Log::create([
                    'aktivitas' => auth()->user()->name . ' Menambahkan buku ' . $book->judul_buku,
                    'user_id' => auth()->id()
                ]);
            }
        }
        toast('Buku berhasil ditambahkan', 'success');
        return redirect()->route('home.admin');
    }


    public function editBook(Book $book)
    {
        $kategori = Kategori::all();
        return view('admin.edit-book', compact('book','kategori'));
    }

    public function postEditBook(Request $request, Book $book)
    {
        $data = $request->validate([
            'judul_buku' => 'required',
            'deskripsi' => 'required',
            'harga_buku' => 'required',
            'stok' => 'required',
        ]);

        if($request->hasFile('sampul_buku')){
            $data['sampul_buku'] = $request->sampul_buku->store('img');
        }else{
            unset($data['foto']);
        }
        if($request->kategori_id){
            $data['kategori_id'] = $request->kategori_id;
        }

        $book->update($data);

        Log::create([
            'aktivitas' => auth()->user()->name . ' Melakukan update pada buku ' . $book->judul_buku,
            'user_id' => auth()->id()
        ]);
        toast('Buku berhasil diubah', 'success');
        return redirect()->route('home.admin');
    }

    public function aktifkanBuku(Book $book){
        $book->update([
            'status' => 'Dijual'
        ]);
        toast('Buku berhasil diaktifkan', 'success');
        return redirect()->route('home.admin');
    }

    public function nonaktifkanBuku(Book $book){
        $book->update([
            'status' => 'Tidak Dijual'
        ]);
        toast('Buku berhasil dinonaktifkan', 'success');
        return redirect()->route('home.admin');
    }
}
