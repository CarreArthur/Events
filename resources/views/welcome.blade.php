@extends('layouts.app')

@section('title', 'Accueil - MyEvents')

@section('content')
{{-- Hero Section --}}
<section class="mb-20">
    <div class="max-w-4xl">
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-6">Découvrez l'expérience</p>
        
        <h1 class="text-6xl font-bold text-black leading-tight mb-8">
            Inscrivez-vous à vos événements préférés
        </h1>
        
        <p class="text-lg text-gray-600 leading-relaxed mb-12 max-w-3xl">
            Explorez nos événements, inscrivez-vous sans compte, et gérez votre présence. 
            Invitez vos amis, mentionnez vos contraintes alimentaires — tout simplement.
        </p>

        <a href="{{ route('events.index') }}"
           class="inline-block bg-black text-white px-8 py-4 font-medium hover:bg-gray-800 transition">
            Parcourir les événements
        </a>
    </div>
</section>

{{-- Stats Section --}}
<section class="mb-20 grid grid-cols-3 gap-12 border-t border-gray-200 pt-16">
    <div>
        <p class="text-5xl font-bold text-black">{{ $totalEvents }}</p>
        <p class="mt-3 text-gray-600">Événement{{ $totalEvents != 1 ? 's' : '' }} à découvrir</p>
    </div>
    <div>
        <p class="text-5xl font-bold text-black">{{ $totalRegistrations }}</p>
        <p class="mt-3 text-gray-600">Participant{{ $totalRegistrations != 1 ? 's' : '' }}</p>
    </div>
    <div>
        <p class="text-5xl font-bold text-black">100%</p>
        <p class="mt-3 text-gray-600">Sans frais</p>
    </div>
</section>

{{-- Latest Events Section --}}
@if($latestEvents->isNotEmpty())
    <section class="mb-20">
        <div class="mb-12">
            <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Nos événements</p>
            <h2 class="text-4xl font-bold text-black">À ne pas rater</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($latestEvents as $event)
                @php
                    $imageUrl = !empty($event->cover_image)
                        ? asset('storage/' . $event->cover_image)
                        : 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=800&q=60';
                @endphp
                <article class="border border-gray-200 hover:border-gray-400 transition group cursor-pointer">
                    {{-- Image --}}
                    <div class="overflow-hidden h-56 bg-gray-100">
                        <img 
                            src="{{ $imageUrl }}" 
                            alt="{{ $event->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                        >
                    </div>

                    {{-- Content --}}
                    <div class="p-6">
                        {{-- Date & Type --}}
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-xs font-semibold tracking-widest text-gray-500 uppercase">
                                {{ $event->date_start?->translatedFormat('d M Y') }}
                            </span>
                            {{-- Separator --}}
                            <span class="text-gray-300">•</span>
                            <span class="text-xs font-semibold tracking-widest 
                                {{ $event->is_public ? 'text-black' : 'text-gray-500' }} uppercase">
                                {{ $event->is_public ? 'Public' : 'Privé' }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h3 class="text-lg font-bold text-black mb-2 line-clamp-2 group-hover:underline">
                            {{ $event->title }}
                        </h3>

                        {{-- Location --}}
                        @if(!empty($event->location))
                            <p class="text-sm text-gray-600 mb-4 line-clamp-1">📍 {{ $event->location }}</p>
                        @endif

                        {{-- Description --}}
                        <p class="text-sm text-gray-600 mb-6 line-clamp-2">
                            {{ $event->description }}
                        </p>

                        {{-- Footer with link --}}
                        <div class="pt-4 border-t border-gray-200">
                            <a href="{{ route('events.show', $event->slug) }}" 
                               class="inline-flex items-center text-sm font-medium text-black hover:gap-2 transition gap-1">
                                M'inscrire <span>→</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- View all button --}}
        <div class="mt-12 text-center">
            <a href="{{ route('events.index') }}"
               class="inline-block border border-black text-black px-8 py-4 font-medium hover:bg-black hover:text-white transition">
                Voir tous les événements
            </a>
        </div>
    </section>
@else
    <section class="mb-20 text-center py-12">
        <p class="text-lg text-gray-600">Aucun événement pour le moment.</p>
        <p class="mt-2 text-sm text-gray-500">Revenez bientôt pour découvrir nos premiers événements!</p>
    </section>
@endif

{{-- Features Section --}}
<section class="border-t border-gray-200 pt-16 mb-20">
    <div class="mb-12">
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Pourquoi nous?</p>
        <h2 class="text-4xl font-bold text-black">Inscription simple & rapide</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="border border-gray-200 p-8">
            <p class="text-3xl font-bold text-black mb-4">✓</p>
            <h3 class="text-lg font-bold text-black mb-3">Pas de compte</h3>
            <p class="text-gray-600">Inscrivez-vous directement avec votre email. Aucune création de compte nécessaire.</p>
        </div>
        
        <div class="border border-gray-200 p-8">
            <p class="text-3xl font-bold text-black mb-4">+</p>
            <h3 class="text-lg font-bold text-black mb-3">Invitez vos amis</h3>
            <p class="text-gray-600">Amenez jusqu'à 2 accompagnants à vos événements. Mentionnez leurs noms et emails.</p>
        </div>
        
        <div class="border border-gray-200 p-8">
            <p class="text-3xl font-bold text-black mb-4">☰</p>
            <h3 class="text-lg font-bold text-black mb-3">Contraintes alimentaires</h3>
            <p class="text-gray-600">Signalez vos régimes spéciaux ou allergies directement à l'organisateur.</p>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="text-center py-12">
    <h2 class="text-4xl font-bold text-black mb-6 max-w-2xl mx-auto">
        Trouvez votre prochain événement
    </h2>
    <p class="text-lg text-gray-600 mb-12 max-w-2xl mx-auto">
        Explorez notre catalogue d'événements et rejoignez la communauté.
    </p>
    <a href="{{ route('events.index') }}"
       class="inline-block bg-black text-white px-8 py-4 font-medium hover:bg-gray-800 transition">
        Découvrir les événements
    </a>
</section>

@endsection
