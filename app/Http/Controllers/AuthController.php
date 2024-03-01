<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function login(Request $req){
        $cred = $req->validate([
            'username' => 'required',
            'password' => 'required'
        ]); 

        if(!Auth::attempt($cred)){
            Alert::error('Warning', 'Username atau Password salah!');
            return redirect()->back();
        }
        if(auth()->user()->role == 'admin'){
            return redirect()->route('homes.owner');
        }
        if(auth()->user()->role == 'pustakawan'){
            return redirect()->route('home');
        }
        if(auth()->user()->role == 'owner'){
            return redirect()->route('home.owner');
        }
    }

    public function logout(){
       
        auth()->logout();
        return redirect()->route('login');
    }
}
