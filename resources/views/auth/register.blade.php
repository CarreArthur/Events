@extends('layouts.app')

@section('title', 'Inscription - MyEvents')

@section('content')
<div class="max-w-md mx-auto py-24">
    <div class="mb-12 text-center">
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Créez votre compte</p>
        <h1 class="text-4xl font-bold text-black">Inscription</h1>
    </div>

    @if($errors->any())
        <div class="mb-8 p-4 bg-red-50 border border-red-200">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-black mb-3">Nom complet</label>
            <input 
                type="text" 
                name="name" 
                id="name"
                value="{{ old('name') }}"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition"
                placeholder="Marie Dupont"
                required
            />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-black mb-3">Email</label>
            <input 
                type="email" 
                name="email" 
                id="email"
                value="{{ old('email') }}"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition"
                placeholder="marie.dupont@eventpro-solutions.fr"
                required
            />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-semibold text-black mb-3">Mot de passe</label>
            <input 
                type="password" 
                name="password" 
                id="password"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition"
                placeholder="Minimum 8 caractères"
                required
            />
            <p class="text-xs text-gray-500 mt-2">Minimum 8 caractères</p>
        </div>

        {{-- Password confirmation --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-black mb-3">Confirmer le mot de passe</label>
            <input 
                type="password" 
                name="password_confirmation" 
                id="password_confirmation"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition"
                placeholder="••••••••"
                required
            />
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full bg-black text-white py-3 font-semibold hover:bg-gray-800 transition">
            Créer mon compte
        </button>
    </form>

    {{-- Login link --}}
    <p class="mt-8 text-center text-gray-600 text-sm">
        Vous avez déjà un compte ?
        <a href="{{ route('login') }}" class="text-black font-semibold hover:underline">
            Se connecter
        </a>
    </p>
</div>
@endsection
