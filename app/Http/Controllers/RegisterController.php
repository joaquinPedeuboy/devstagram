<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    //
    public function index() {
        return(view('auth.register'));
    }

    public function store(Request $request) {

        //Modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);
        // validacion
        $validate = $request->validate([
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ]);

        User::create([
            'name' => $validate["name"],
            'username' => $request->username, //Str::slug($validate["username"]) //Convierte a URL
            'email' => $validate["email"],
            'password' => $validate["password"],//laravel hashea el password por si solo
            //Hash::make($request["password"])
        ]);

        //Autenticar
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            return redirect()->route('posts.index', auth()->user()->username);
        }

        return back()->withErrors([
            'email' => 'Estas credenciales no existen.',
        ])->onlyInput('email');

        // Redireccionar
        return redirect()->route('posts.index');
    }
}
