@extends('layouts.app')

@section('title', 'Laisser un avis')

@section('content')
<div class="max-w-2xl mx-auto py-16">
    <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase mb-4">Avis</p>
    <h1 class="text-4xl font-bold text-black mb-4">{{ $event->title }}</h1>
    <p class="text-base text-gray-600 mb-8">Partagez votre ressenti sur cet evenement.</p>

    @if($error)
        <div class="border border-red-200 bg-red-50 text-red-700 px-6 py-4 mb-8">
            {{ $error }}
        </div>
    @elseif($alreadyReviewed)
        <div class="border border-gray-200 bg-gray-50 text-gray-700 px-6 py-4 mb-8">
            Un avis a deja ete depose pour cette participation.
        </div>
    @endif

    @if(! $error && ! $alreadyReviewed)
        <form method="POST" action="{{ route('reviews.store', $registration->invite_token) }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="rating">Note</label>
                <select id="rating" name="rating" class="w-full border border-gray-300 p-3">
                    <option value="">Choisir une note</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}/5</option>
                    @endfor
                </select>
                @error('rating')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="comment">Commentaire (optionnel)</label>
                <textarea id="comment" name="comment" rows="5" class="w-full border border-gray-300 p-3" placeholder="Votre avis...">{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            @if($errors->has('review'))
                <p class="text-sm text-red-600">{{ $errors->first('review') }}</p>
            @endif

            <button type="submit" class="inline-flex items-center bg-black text-white px-6 py-3 font-medium hover:bg-gray-800 transition">
                Envoyer mon avis
            </button>
        </form>
    @endif
</div>
@endsection
