<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Voucher;
use App\Models\VoucherInventory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MemberController extends Controller
{
    public function index()
    {
        return view('member.cekvoucher');
    }

    public function profile(Request $request)
    {
        $member = Member::where('kode_unik',$request->kode_unik)->with('inventory.voucher')->first();
        if(!$member){
            Alert::warning('Member tidak ada!');
            return redirect()->back();
        }
        $vinven = VoucherInventory::where('member_id', $member->id)->get();
        $voucherId = $vinven->pluck('voucher_id');
        $voucher = Voucher::whereIn('id', $voucherId)->get();
        return view('member.profile', compact(['voucher','member']));
    }

    public function pembayaranQris()
    {
        return view('pembayaran.isipembayaran');
    }
}
