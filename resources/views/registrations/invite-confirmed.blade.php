@extends('layouts.app')

@section('title', 'Confirmation inscription')

@section('content')
<div class="max-w-2xl mx-auto py-24 text-center">
    @if($capacityError)
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Erreur</p>
        <h1 class="text-5xl font-bold text-black mb-6">Impossible de confirmer</h1>
        <p class="text-base text-gray-600 mb-12">{{ $capacityError }}</p>
    @elseif($alreadyConfirmed)
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Information</p>
        <h1 class="text-5xl font-bold text-black mb-6">Déjà confirmée</h1>
        <p class="text-base text-gray-600 mb-12">Votre présence était déjà enregistrée.</p>
    @else
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Succès</p>
        <h1 class="text-5xl font-bold text-black mb-6">Présence confirmée</h1>
        <p class="text-base text-gray-600 mb-12">Merci, votre présence est bien enregistrée.</p>
    @endif

    <a href="{{ url('/') }}" class="inline-block bg-black text-white px-8 py-4 font-medium hover:bg-gray-800 transition">
        Retour à l'accueil
    </a>
</div>
@endsection
