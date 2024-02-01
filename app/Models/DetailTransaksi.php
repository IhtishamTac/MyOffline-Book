<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function transaksi(){
        return $this->belongsTo(Transaksi::class);
    }
}
