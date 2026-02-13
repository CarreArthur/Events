@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="">
    {{-- Breadcrumb --}}
    <div class="mb-12">
        <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:text-black transition">
            ← Retour aux événements
        </a>
    </div>

    <div class="grid gap-12 lg:grid-cols-3">
        {{-- COLONNE PRINCIPALE --}}
        <div class="lg:col-span-2">
            {{-- Hero image --}}
            @php
                $imageUrl = !empty($event->cover_image)
                    ? asset('storage/' . $event->cover_image)
                    : 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=1600&q=60';
            @endphp
            <div class="mb-12 overflow-hidden border border-gray-200">
                <img src="{{ $imageUrl }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
            </div>

            {{-- Title + metadata --}}
            <div class="mb-12">
                <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">
                    {{ $event->date_start?->translatedFormat('d F Y') }}
                </p>
                <h1 class="text-5xl font-bold text-black leading-tight mb-6">
                    {{ $event->title }}
                </h1>
                @if(!empty($event->location))
                    <p class="text-lg text-gray-600">{{ $event->location }}</p>
                @endif
            </div>

            {{-- Description --}}
            <div class="prose prose-sm max-w-none mb-12 border-y border-gray-200 py-12">
                <div class="text-base leading-relaxed text-gray-700">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <aside class="lg:col-span-1">
            {{-- Info box --}}
            <div class="border border-gray-200 p-8 sticky top-8">
                <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-8">Détails</p>

                <div class="space-y-6 mb-8 pb-8 border-b border-gray-200">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Date</p>
                        <p class="text-base font-semibold text-black">
                            {{ $event->date_start?->translatedFormat('d F Y') }}
                            @if($event->date_end)
                                — {{ $event->date_end->translatedFormat('d F Y') }}
                            @endif
                        </p>
                    </div>

                    @if(!is_null($event->max_participants))
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Participants</p>
                            <p class="text-base font-semibold text-black">{{ $event->max_participants }} max</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Type</p>
                        <p class="text-base font-semibold text-black">{{ $event->is_public ? 'Public' : 'Privé' }}</p>
                    </div>

                    @if(!is_null($event->max_participants))
                        @php
                            $used = $event->registrations()
                                ->where('status', 'REGISTERED')
                                ->where('is_attending', true)
                                ->count();
                            $remaining = max($event->max_participants - $used, 0);
                        @endphp
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Places restantes</p>
                            <p class="text-base font-semibold text-black">{{ $remaining }}/{{ $event->max_participants }}</p>
                        </div>
                    @endif
                </div>

                {{-- CTA --}}
                @if($event->is_public)
                    <a href="{{ route('registrations.create', $event->slug) }}"
                       class="block w-full bg-black text-white text-center py-4 font-medium hover:bg-gray-800 transition">
                        S'inscrire
                    </a>
                @else
                    <div class="text-sm text-gray-600 text-center py-4 border border-gray-200">
                        Événement privé
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection
