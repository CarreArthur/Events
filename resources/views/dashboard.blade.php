@extends('layouts.app')

@section('title', 'Tableau de bord - MyEvents')

@section('content')
<div class="py-24">
    <div class="max-w-6xl">
        <div class="flex justify-between items-center mb-12">
            <div>
                <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Bienvenue</p>
                <h1 class="text-5xl font-bold text-black">{{ auth()->user()->name }}</h1>
                <p class="mt-2 text-gray-600">Accédez à l'administration des événements</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-6 py-3 bg-gray-100 text-black hover:bg-gray-200 transition font-medium">
                    Déconnexion
                </button>
            </form>
        </div>

        {{-- Admin vs Employee sections --}}
        @if(auth()->user()->isAdmin())
            {{-- Admin dashboard --}}
            <section class="mb-20">
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-black mb-4">Administration complète</h2>
                    <p class="text-gray-600">Accédez au panneau d'administration Filament pour gérer tous les événements et utilisateurs.</p>
                </div>

                <a href="/admin" 
                   class="inline-block bg-black text-white px-8 py-4 font-medium hover:bg-gray-800 transition">
                    Accéder au panneau admin →
                </a>
            </section>
        @else
            {{-- Employee (Chef de projet) dashboard --}}
            <section class="mb-20 border border-gray-200 p-8">
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-black mb-4">Gestion de vos événements</h2>
                    <p class="text-gray-600">Créez et gérez vos événements via le panneau Filament.</p>
                </div>

                <a href="/admin/events" 
                   class="inline-block bg-black text-white px-8 py-4 font-medium hover:bg-gray-800 transition">
                    Accéder à mes événements →
                </a>
            </section>

            <section>
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-black mb-4">Actions rapides</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="/admin/events/create" class="border border-gray-200 p-6 hover:border-black transition">
                        <p class="text-3xl font-bold text-black mb-2">+</p>
                        <h3 class="font-bold text-black">Créer un événement</h3>
                        <p class="text-sm text-gray-600 mt-2">Commencez à organiser un nouvel événement</p>
                    </a>

                    <a href="/admin/registrations" class="border border-gray-200 p-6 hover:border-black transition">
                        <p class="text-3xl font-bold text-black mb-2">📋</p>
                        <h3 class="font-bold text-black">Voir les inscriptions</h3>
                        <p class="text-sm text-gray-600 mt-2">Consultez la liste des participants</p>
                    </a>

                    <a href="/" class="border border-gray-200 p-6 hover:border-black transition">
                        <p class="text-3xl font-bold text-black mb-2">👀</p>
                        <h3 class="font-bold text-black">Voir le site public</h3>
                        <p class="text-sm text-gray-600 mt-2">Consultez la page d'accueil</p>
                    </a>
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
