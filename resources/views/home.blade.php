@extends('layouts.app')

@section('titulo')
    Welcome
@endsection

@section('contenido')
    {{-- Otra forma de ver los posts --}}
    {{-- @forelse ($posts as $post)
        <h1>{{ $post->titulo }}</h1>
    @empty
        <p>No hay posts</p>
    @endforelse --}}

    {{-- Componente de Laravel --}}
    {{-- Pasar variable --}}
    <x-listar-post :posts="$posts" :emptyMessage="'No hay posts, sigue a alguien para poder mostrar sus posts'" /> 
        {{-- Slot --}}
        {{-- <x-slot:titulo>
            <header>Esto es un header</header>
        </x-slot:titulo>
        <h1>Mostrando post desde slot</h1> --}}


@endsection
