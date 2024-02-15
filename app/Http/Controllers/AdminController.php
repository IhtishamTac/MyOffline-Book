<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return view('admin.add-book');
    }

    public function postAddBook(Request $request)
    {
        if ($request) {
            $book = Book::create([
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
        return redirect()->route('home.admin');
    }


    public function editBook(Book $book)
    {
        return view('admin.edit-book', compact('book'));
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
        $book->update($data);

        Log::create([
            'aktivitas' => auth()->user()->name . ' Melakukan update pada buku ' . $book->judul_buku,
            'user_id' => auth()->id()
        ]);
        return redirect()->route('home.admin');
    }

    public function aktifkanBuku(Book $book){
        $book->update([
            'status' => 'Dijual'
        ]);
        return redirect()->route('home.admin');
    }

    public function nonaktifkanBuku(Book $book){
        $book->update([
            'status' => 'Tidak Dijual'
        ]);
        return redirect()->route('home.admin');
    }
}
