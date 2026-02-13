@extends('layouts.app')

@section('title', 'Annulation inscription')

@section('content')
<div class="max-w-2xl mx-auto py-24 text-center">
    @if($error)
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Erreur</p>
        <h1 class="text-5xl font-bold text-black mb-6">Impossible d'annuler</h1>
        <p class="text-base text-gray-600 mb-12">{{ $error }}</p>
    @elseif($alreadyCancelled)
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Information</p>
        <h1 class="text-5xl font-bold text-black mb-6">Déjà annulée</h1>
        <p class="text-base text-gray-600 mb-12">Votre inscription a déjà été annulée.</p>
    @else
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Succès</p>
        <h1 class="text-5xl font-bold text-black mb-6">Inscription annulée</h1>
        <p class="text-base text-gray-600 mb-12">Votre inscription à l'événement "{{ $event->title }}" a bien été annulée.</p>
    @endif

    <a href="{{ url('/') }}" class="inline-block bg-black text-white px-8 py-4 font-medium hover:bg-gray-800 transition">
        Retour à l'accueil
    </a>
</div>
@endsection
