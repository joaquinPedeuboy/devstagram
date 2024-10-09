<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PostController extends Controller implements HasMiddleware
{
    //Otra forma de usar los middleware desde el controlador
    // public static function middleware(): array
    // {
    //     return [
    //         'auth',
    //         //new Middleware('auth', only: ['create']),
    //     ];
    // }
// Middleware desde el controlador
public function __construct()
    {
        $this->middleware('auth');
    }
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['show', 'index']),
        ];
    }
// // o también:

// new Middleware('auth', only: ['create']),

    public function index(User $user) {
        // Obtener los posts
        $posts = Post::where('user_id', $user->id)->latest()->paginate(10); // Paginacion de posts

        return view('dashboard', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        //Validacion
        $validate = $request->validate([
            'titulo' => ['required','max:255'],
            'descripcion' => ['required'],
            'imagen' => ['required']
        ]);

        // Primer forma
        Post::create([
            'titulo' => $validate["titulo"],
            'descripcion' => $validate["descripcion"],
            'imagen' => $validate["imagen"],
            'user_id' => auth()->user()->id
        ]);

        //Otra forma de crear registros
        // $post = new Post;
        // $post->titulo = $request->titulo;
        // $post->descripcion = $request->descripcion;
        // $post->imagen = $request->imagen;
        // $post->user_id = auth()->user()->id;

        // Tercer forma de crear
        // $request->user()->posts()->create([
        //     'titulo' => $validate["titulo"],
        //     'descripcion' => $validate["descripcion"],
        //     'imagen' => $validate["imagen"],
        //     'user_id' => auth()->user()->id
        // ]);

        return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user, Post $post) {


        return view('posts.show', [
            'post' => $post,
            'user' => $user
        ]);
    }

    // Eliminar publicacion
    public function destroy( Post $post) {
        $this->authorize('delete', $post);

        $post->delete();

        // Eliminar la imagen
        $imagen_path = public_path('uploads/' . $post->imagen);

        if(File::exists($imagen_path)) {
            unlink($imagen_path);
        };
        
        return redirect()->route('posts.index', auth()->user()->username);
    }
}
