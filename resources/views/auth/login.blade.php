@extends('layouts.app')

@section('title', 'Connexion - MyEvents')

@section('content')
<div class="max-w-md mx-auto py-24">
    <div class="mb-12 text-center">
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Identifiez-vous</p>
        <h1 class="text-4xl font-bold text-black">Connexion</h1>
    </div>

    @if($errors->any())
        <div class="mb-8 p-4 bg-red-50 border border-red-200">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

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
                placeholder="••••••••"
                required
            />
        </div>

        {{-- Remember me --}}
        <div>
            <label class="flex items-center gap-3">
                <input type="checkbox" name="remember" class="w-4 h-4 border border-gray-300">
                <span class="text-sm text-gray-600">Rester connecté(e)</span>
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full bg-black text-white py-3 font-semibold hover:bg-gray-800 transition">
            Se connecter
        </button>
    </form>

    {{-- Register link --}}
    <p class="mt-8 text-center text-gray-600 text-sm">
        Vous n'avez pas de compte ?
        <a href="{{ route('register') }}" class="text-black font-semibold hover:underline">
            S'inscrire
        </a>
    </p>
</div>
@endsection
