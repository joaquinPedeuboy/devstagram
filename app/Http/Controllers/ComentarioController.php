<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    //
    public function store(Request $request, User $user, Post $post) {
        // Validar
        $validate = $request->validate([
            'comentario' => ['required','max:255']
        ]);
        // Almacenar
        Comentario::create([
            'user_id' => auth()->user()->id,  // Obtenemos el ID del usuario autenticado
            'post_id' => $post->id,            // Asociamos el comentario con el post
            'comentario' => $validate["comentario"] // El comentario validado
        ]);
        
        // Retornar una respuesta o imprimir un mensaje
        return back()->with('mensaje', 'Comentario creado exitosamente');
    }
}
