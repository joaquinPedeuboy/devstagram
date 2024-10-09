<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerfilController extends Controller
{
    //

    public function index() {
        return view('perfil.index');
    }

    public function store(Request $request) {

        //Modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);
        
        // Validar username y email
        $credentials = $request->validate([
            'username' => ['required','unique:users,username,'.auth()->user()->id,'min:3','max:20','regex:/\w*$/', 'not_in:twitter,editar-perfil,login,register'],
            // 'in:CLIENTE' Obliga al usuario a elegir cliente
            'email' => ['required','unique:users,email,'.auth()->user()->id, 'email', 'max:60']
        ]);

        // Guardar cambios usuario autenticado
        $usuario = User::find(auth()->user()->id);

        // Requiere que se proporcione la contraseña actual
        $request->validate([
            'oldpassword' => ['required']
        ]);

        // Verificar si la contraseña actual es correcta
        if(!Hash::check($request->oldpassword, auth()->user()->password)) {
            return back()->withErrors(['oldpassword' => 'La contraseña actual no coincide'])->withInput();
        }

        // Si hay una contraseña nueva, validar y actualizarla
        if($request->filled('password')){
            $request->validate([
                'password' => 'required|confirmed|min:6'
            ]);
            $usuario->password = Hash::make($request->password);
        }

        if($request->imagen) {
            $imagen = $request->file('imagen');

            $nombreImagen = Str::uuid() . "." . $imagen->extension();
    
            $manager = new ImageManager(new Driver());
    
            $imagenServidor = $manager::imagick()->read($imagen);
            $imagenServidor->cover(1000,1000);
    
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
    
            $imagenServidor->save($imagenPath);
        }

        // Actualizar el username y email del usuario
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->imagen = $nombreImagen ?? auth()->user()-> imagen ?? null;
        // Guardar los cambios en el usuario
        $usuario->save();

        // Redireccionar
        return redirect()->route('posts.index', $usuario->username);
    }
}
