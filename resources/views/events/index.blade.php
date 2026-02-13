@extends('layouts.app')

@section('content')
<div class="">
    <div class="mb-12">
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase">Catalogue</p>
        <h1 class="mt-2 text-5xl font-bold text-black">Événements</h1>
    </div>

    @if($events->count() === 0)
        <div class="py-12 text-center border border-gray-200">
            <p class="text-gray-600">Aucun événement disponible pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @foreach($events as $event)
                @php
                    $imageUrl = !empty($event->cover_image)
                        ? asset('storage/' . $event->cover_image)
                        : 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=60';
                @endphp

                <article class="border border-gray-200 overflow-hidden hover:border-gray-400 transition">
                    <a href="{{ route('events.show', $event) }}" class="block">
                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $event->title }}"
                            class="w-full h-56 object-cover"
                            loading="lazy"
                        >
                    </a>

                    <div class="p-6">
                        <div class="text-xs text-gray-500 font-medium mb-3 uppercase tracking-wide">
                            {{ $event->date_start?->translatedFormat('d F Y') }}
                        </div>

                        <h2 class="text-xl font-semibold mb-3 text-black">
                            <a href="{{ route('events.show', $event) }}" class="hover:underline">
                                {{ $event->title }}
                            </a>
                        </h2>

                        @if(!empty($event->location))
                            <div class="text-sm text-gray-600 mb-4">
                             {{ $event->location }}
                            </div>
                        @endif

                        @if(!empty($event->description))
                            <p class="text-sm text-gray-600 mb-6">
                                {{ \Illuminate\Support\Str::limit(strip_tags($event->description), 100) }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <a href="{{ route('events.show', $event) }}" class="text-sm font-medium text-black hover:underline">
                                Détails →
                            </a>

                            @if($event->is_public)
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Public</span>
                            @else
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Privé</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $events->links() }}
        </div>
    @endif

</div>
@endsection
