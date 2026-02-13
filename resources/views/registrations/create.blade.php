@extends('layouts.app')

@section('title', 'Inscription - ' . $event->title)

@section('content')
<div class="max-w-2xl">
    <div class="mb-12">
        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase">Formulaire</p>
        <h1 class="mt-2 text-4xl font-bold text-black">Inscription</h1>
        <p class="mt-2 text-gray-600">{{ $event->title }}</p>
    </div>

    @if($errors->has('capacity'))
        <div class="mb-8 p-6 bg-black text-white border border-gray-800">
            <p class="text-sm">{{ $errors->first('capacity') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('registrations.store', $event->slug) }}" class="space-y-8">
        @csrf

        @if($invitation)
            <input type="hidden" name="invite_token" value="{{ $invitation->invite_token }}">
        @endif

        <div>
            <label class="block text-sm font-semibold text-black mb-3">Nom *</label>
            <input 
                name="guest_name" 
                value="{{ old('guest_name', $invitation?->guest_name) }}"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition" 
                required>
            @error('guest_name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-black mb-3">Email *</label>
            <input 
                type="email" 
                name="guest_email" 
                value="{{ old('guest_email', $invitation?->guest_email) }}"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition" 
                required>
            @error('guest_email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3">
            <input 
                id="is_attending" 
                type="checkbox" 
                name="is_attending" 
                value="1"
                class="w-5 h-5 border border-gray-200 focus:outline-none" 
                {{ old('is_attending', true) ? 'checked' : '' }}>
            <label for="is_attending" class="text-sm text-gray-700">Je participe à cet événement</label>
        </div>

        <div>
            <label class="block text-sm font-semibold text-black mb-3">Contraintes alimentaires</label>
            <textarea 
                name="dietary_info" 
                rows="4"
                class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition resize-none">{{ old('dietary_info') }}</textarea>
            <p class="mt-2 text-xs text-gray-500">Végétarien, allergie, etc.</p>
        </div>

        @if($isPrivate)
            <div class="p-6 bg-gray-50 border border-gray-200">
                <p class="text-sm text-gray-700">Événement privé : pas d'accompagnants.</p>
            </div>
            <input type="hidden" name="guests_count" value="0">
        @else
            <div>
                <label class="block text-sm font-semibold text-black mb-3">Accompagnants</label>
                <select name="guests_count" id="guests_count" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition">
                    @for($i=0;$i<=2;$i++)
                        <option value="{{ $i }}" {{ (int)old('guests_count', 0) === $i ? 'selected' : '' }}>
                            {{ $i }} accompagnant{{ $i !== 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>
            </div>

            <div id="guests_fields" class="space-y-8"></div>
        @endif

        <button class="w-full bg-black text-white py-4 font-semibold hover:bg-gray-800 transition">
            Valider mon inscription
        </button>
    </form>
</div>

@if(! $isPrivate)
    <script>
    (function () {
        const select = document.getElementById('guests_count');
        const container = document.getElementById('guests_fields');

        function render(count) {
            container.innerHTML = '';
            for (let i = 0; i < count; i++) {
                const block = document.createElement('div');
                block.className = "border border-gray-200 p-6";
                block.innerHTML = `
                    <p class="text-sm font-semibold text-black mb-6">Accompagnant ${ i + 1 }</p>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-black mb-3">Nom</label>
                        <input name="guests[${ i }][name]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black mb-3">Email</label>
                        <input type="email" name="guests[${ i }][email]" class="w-full px-4 py-3 border border-gray-200 focus:border-black focus:outline-none transition" />
                    </div>
                `;
                container.appendChild(block);
            }
        }

        render(parseInt(select.value || '0', 10));
        select.addEventListener('change', (e) => render(parseInt(e.target.value || '0', 10)));
    })();
    </script>
@endif
@endsection
