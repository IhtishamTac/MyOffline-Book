<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Book;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\Member;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherInventory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Pustakawan',
            'email' => 'pustakawan@gmail.com',
            'username' => 'pusta',
            'password' => bcrypt('pusta'),
            'role' => 'pustakawan'
        ]);
        User::create([
            'name' => 'Pustakawan2',
            'email' => 'pustakawan2@gmail.com',
            'username' => 'pusta2',
            'password' => bcrypt('pusta2'),
            'role' => 'pustakawan'
        ]);

        User::create([
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'username' => 'own',
            'password' => bcrypt('own'),
            'role' => 'owner'
        ]);

        Kategori::create([
            'nama_kategori' => 'Manga',
        ]);
        Kategori::create([
            'nama_kategori' => 'Novel',
        ]);

        Book::create([
            'kategori_id' => 2,
            'sampul_buku' => 'book_image/Bumi_(sampul).jpg',
            'judul_buku' => 'Bumi',
            'deskripsi' => 'Seri pertama dari novel karya Tere Liye',
            'harga_buku' => 95000,
            'stok' => 100,
            'status' => 'Dijual'
        ]);
        Book::create([
            'kategori_id' => 2,
            'sampul_buku' => 'book_image/bulan_(sampul).jpg',
            'judul_buku' => 'Bulan',
            'deskripsi' => 'Seri kedua dari novel karya Tere Liye',
            'harga_buku' => 95000,
            'stok' => 100,
            'status' => 'Dijual'

        ]);
        Book::create([
            'kategori_id' => 2,
            'sampul_buku' => 'book_image/bintang_sampul.jpg',
            'judul_buku' => 'Bintang',
            'deskripsi' => 'Seri ke 4 dari novel karya Tere Liye',
            'harga_buku' => 95000,
            'stok' => 100,
            'status' => 'Dijual'

        ]);

        Book::create([
            'kategori_id' => 2,
            'sampul_buku' => 'book_image/y4T0MYTuP3y8Et8KdsUNRVmxirkw7UpyTbUzpbVy.jpg',
            'judul_buku' => 'Perpustakaan Tengah Malam',
            'deskripsi' => 'Perpustakaan Tengah Malam atau The Midnight Library adalah sebuah novel fantasi karya Matt Haig, diterbitkan pada 13 Agustus 2020 oleh Canongate Books.',
            'harga_buku' => 86000,
            'stok' => 20,
            'status' => 'Tidak Dijual'

        ]);
        Book::create([
            'kategori_id' => 1,
            'sampul_buku' => 'book_image/Fh8bwTY5ZKtZmvl88afui8diLsrtSz2gtG4B1GfR.jpg',
            'judul_buku' => 'Jojo : Phantom Blood',
            'deskripsi' => 'Phantom Blood merupakan bagian pertama dari seri JoJo no Kimyou na Bouken.',
            'harga_buku' => 75000,
            'stok' => 22,
            'status' => 'Tidak Dijual'
        ]);
        Book::create([
            'kategori_id' => 1,
            'sampul_buku' => 'book_image/ZsRFDERSS9hJk1hQ7e2fd2YCd1oQo90CPoLaK1Dn.jpg',
            'judul_buku' => 'Yakusoku no Neverland Vol. 1',
            'deskripsi' => 'Volume pertama dari series Manga The Promised Neverland',
            'harga_buku' => 150000,
            'stok' => 130,
            'status' => 'Dijual'
        ]);
        Book::create([
            'kategori_id' => 1,
            'sampul_buku' => 'book_image/ZdvUFS92iF64wSRwGN6OwrBz7upqOBVl3w5pPwCt.jpg',
            'judul_buku' => 'Yakusoku no Neverland Vol. 2',
            'deskripsi' => 'Volume kedua dari series Manga The Promised Neverland',
            'harga_buku' => 150000,
            'stok' => 90,
            'status' => 'Dijual'
        ]);
        Book::create([
            'kategori_id' => 1,
            'sampul_buku' => 'book_image/o2EC8PWpVVAZ3AeFsStX2Z73muzQt73u16aYaz9p.jpg',
            'judul_buku' => 'Yakusoku no Neverland Vol. 3',
            'deskripsi' => 'Volume ketiga dari series Manga The Promised Neverland',
            'harga_buku' => 150000,
            'stok' => 90,
            'status' => 'Dijual'
        ]);
        

        Transaksi::create([
            'invoice' => 'INV' . Str::random(10),
            'user_id' => 2,
            'nama_pembeli' => 'Guntur Wijaya',
            'status' => 'Dibayar',
            'total_semua' => '665000',
            'uang_bayar' => '700000',
            'uang_kembali' => '35000',
            'updated_at' => Carbon::now()->subMonth()
        ]);
        Transaksi::create([
            'invoice' => 'INV' . Str::random(10),
            'user_id' => 3,
            'nama_pembeli' => 'Guntur Wijaya',
            'status' => 'Dibayar',
            'total_semua' => '760000',
            'uang_bayar' => '800000',
            'uang_kembali' => '40000',
            'updated_at' => Carbon::now()
        ]);
        Transaksi::create([
            'invoice' => 'INV' . Str::random(10),
            'user_id' => 2,
            'nama_pembeli' => 'Ahmad Sutisna',
            'status' => 'Dibayar',
            'total_semua' => '300000',
            'uang_bayar' => '300000',
            'uang_kembali' => '0',
            'updated_at' => Carbon::now()
        ]);
        DetailTransaksi::create([
            'transaksi_id' => 1,
            'book_id' => 3,
            'qty' => 4
        ]);
        DetailTransaksi::create([
            'transaksi_id' => 1,
            'book_id' => 2,
            'qty' => 3
        ]);

        DetailTransaksi::create([
            'transaksi_id' => 2,
            'book_id' => 1,
            'qty' => 4
        ]);
        DetailTransaksi::create([
            'transaksi_id' => 2,
            'book_id' => 2,
            'qty' => 4
        ]);
        DetailTransaksi::create([
            'transaksi_id' => 3,
            'book_id' => 6,
            'qty' => 1
        ]);
        DetailTransaksi::create([
            'transaksi_id' => 3,
            'book_id' => 7,
            'qty' => 1
        ]);
        DetailTransaksi::create([
            'transaksi_id' => 3,
            'book_id' => 8,
            'qty' => 1
        ]);

        Member::create([
            'kode_unik' => 'MEiSam4',
            'nama_member' => 'Isam',
            'no_telp' => '08572993086',
            'alamat' => 'Jl. Ahmad Dahlan No.17'
        ]);
        Member::create([
            'kode_unik' => 'MEiFeni5',
            'nama_member' => 'Feni',
            'no_telp' => '08572230386',
            'alamat' => 'Jl. Ahmad Yani No.12'
        ]);
        Member::create([
            'kode_unik' => 'MEiRushia6',
            'nama_member' => 'Rushia',
            'no_telp' => '08542653086',
            'alamat' => 'Jl. Ki Hajar Dewantara No.7'
        ]);

        Voucher::create([
            'nama_voucher' => 'Diskon Febuari!',
            'deskripsi' => 'Diskon febuari, Untukmmu!',
            'syarat' => 'Belanja Minimal',
            'belanja_minimal' => 100000,
            'potongan_harga' => 5,
            'tanggal_kadaluarsa' => Carbon::tomorrow()->addWeek(),
            'status' => 'Aktif'
        ]);
        Voucher::create([
            'nama_voucher' => 'Diskon Awal Tahun!',
            'deskripsi' => 'Diskon awal tahun, Untukmmu!',
            'syarat' => 'Tidak Bersyarat',
            'potongan_harga' => 10,
            'tanggal_kadaluarsa' => Carbon::tomorrow()->addWeek(),
            'status' => 'Aktif'
        ]);

        VoucherInventory::create([
            'member_id' => 1,
            'voucher_id' => 1
        ]);
        VoucherInventory::create([
            'member_id' => 1,
            'voucher_id' => 2
        ]);
    }
}
