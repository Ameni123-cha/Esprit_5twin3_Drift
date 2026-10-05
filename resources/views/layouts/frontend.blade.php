<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trace Verte') — Traçabilité alimentaire</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|fraunces:500,600,700" rel="stylesheet" />
    <!-- Bootstrap CSS (optional for a professional, opinionated UI) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[var(--tv-surface)] font-sans antialiased text-slate-800">
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Trace Verte Logo" class="rounded-circle border border-success-subtle shadow-sm" style="width:44px;height:44px;object-fit:cover;">
                <span class="ms-2 fw-semibold text-dark">Trace Verte</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Produits</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('producers.index') }}">Producteurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('transformers.index') }}">Transformateurs</a></li>
                </ul>

                <form class="d-flex me-3" role="search" action="{{ route('products.index') }}" method="GET">
                    <input class="form-control me-2" type="search" placeholder="Rechercher produit, producteur, code-barre" aria-label="Search" name="q">
                    <button class="btn btn-success" type="submit">Rechercher</button>
                </form>

                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ route('consumer.profile') }}" class="btn btn-outline-secondary d-none d-sm-inline">{{ auth()->user()->name }}</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-danger" type="submit">Déconnexion</button></form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Connexion</a>
                        <a href="{{ route('register') }}" class="btn btn-success">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        <x-flash-messages />
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-brand-100 bg-brand-800 text-brand-50">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <p class="font-display text-2xl font-semibold">Trace Verte</p>
                <p class="mt-2 text-sm text-brand-100/80">De la ferme à l’assiette : une traçabilité alimentaire claire, vérifiable et responsable.</p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-brand-200">Explorer</p>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a class="hover:underline" href="{{ route('products.index') }}">Produits</a></li>
                    <li><a class="hover:underline" href="{{ route('producers.index') }}">Producteurs</a></li>
                    <li><a class="hover:underline" href="{{ route('transformers.index') }}">Transformateurs</a></li>
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-brand-200">Confidentialité</p>
                <p class="mt-3 text-sm text-brand-100/80">Les scores et analyses sont informatifs. Ils ne constituent ni un diagnostic de santé ni une preuve juridique de fraude.</p>
            </div>
        </div>
        <div class="border-t border-brand-700/60 px-4 py-4 text-center text-xs text-brand-200">© {{ date('Y') }} Trace Verte</div>
    </footer>
    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
