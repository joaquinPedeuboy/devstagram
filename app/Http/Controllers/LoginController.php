<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function index() {
        return view('auth.login');
    }

    public function store(Request $request) {

        // $validate = $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required'
        // ]);

        

        //Validacion login
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->with([
                'mensaje' => 'Credenciales Incorrectas',
            ])->onlyInput('mensaje');
        }

        return redirect()->route('posts.index', auth()->user()->username);
    }
}
