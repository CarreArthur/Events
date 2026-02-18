@extends('layouts.app')

@section('title', 'Merci pour votre avis')

@section('content')
<div class="max-w-2xl mx-auto py-24 text-center">
    <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Merci</p>
    <h1 class="text-5xl font-bold text-black mb-6">Avis enregistre</h1>
    <p class="text-base text-gray-600 mb-12">Merci d'avoir partage votre experience pour {{ $event->title }}.</p>

    <a href="{{ url('/') }}" class="inline-block bg-black text-white px-8 py-4 font-medium hover:bg-gray-800 transition">
        Retour a l'accueil
    </a>
</div>
@endsection
