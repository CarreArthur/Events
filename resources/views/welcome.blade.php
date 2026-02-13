@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<section class="">
    <div class="mb-16">
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase">Plateforme d'événements</p>

        <h1 class="mt-4 text-6xl font-bold tracking-tight text-black leading-tight max-w-4xl">
            Organisez vos événements simplement
        </h1>

        <p class="mt-6 text-lg text-gray-600 max-w-2xl leading-relaxed">
            Une plateforme complète pour créer, gérer et suivre vos événements. Inscriptions sans compte, gestion des accompagnants, contraintes alimentaires — tout en un seul endroit.
        </p>

        <div class="mt-10 flex gap-4">
            <a href="{{ route('events.index') }}"
               class="inline-flex items-center px-8 py-4 bg-black text-white font-medium hover:bg-gray-800 transition">
                Découvrir les événements
            </a>

            <a href="{{ url('/admin') }}"
               class="inline-flex items-center px-8 py-4 border-2 border-black text-black font-medium hover:bg-gray-50 transition">
                Espace administrateur
            </a>
        </div>
    </div>

    {{-- Section stats/highlights --}}
    <div class="grid grid-cols-3 gap-12 border-t border-gray-200 pt-16">
        <div>
            <p class="text-4xl font-bold text-black">50+</p>
            <p class="mt-2 text-gray-600">Événements par an</p>
        </div>
        <div>
            <p class="text-4xl font-bold text-black">0</p>
            <p class="mt-2 text-gray-600">Compte invité requis</p>
        </div>
        <div>
            <p class="text-4xl font-bold text-black">100%</p>
            <p class="mt-2 text-gray-600">Gratuit et simple</p>
        </div>
    </div>
</section>
@endsection
