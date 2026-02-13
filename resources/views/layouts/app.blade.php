<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white text-gray-900" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    {{-- HEADER --}}
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-8 py-6">
            <a href="{{ url('/') }}" class="text-2xl font-bold tracking-tight text-black">
                MyEvents
            </a>

            <nav class="flex items-center gap-12 text-sm font-medium">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-black transition">Accueil</a>
                <a href="{{ url('/events') }}" class="text-gray-700 hover:text-black transition">Événements</a>
                <a href="{{ url('/admin') }}" class="text-gray-700 hover:text-black transition">Admin</a>
            </nav>
        </div>
    </header>

    {{-- CONTENU --}}
    <main class="mx-auto max-w-7xl px-8 py-16">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-8 py-12">
            <div class="grid grid-cols-4 gap-12 mb-8">
                <div>
                    <h4 class="font-semibold text-black mb-4">MyEvents</h4>
                    <p class="text-sm text-gray-600">Plateforme de gestion d'événements simple et professionnelle.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-black mb-4">Liens</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="{{ url('/') }}" class="hover:text-black">Accueil</a></li>
                        <li><a href="{{ url('/events') }}" class="hover:text-black">Événements</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-black mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-black">Contact</a></li>
                        <li><a href="#" class="hover:text-black">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-black mb-4">Légal</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-black">Conditions</a></li>
                        <li><a href="#" class="hover:text-black">Confidentialité</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-8 text-sm text-gray-600 text-center">
                © {{ date('Y') }} MyEvents. Tous droits réservés.
            </div>
        </div>
    </footer>

</body>
</html>
