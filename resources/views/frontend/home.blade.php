@extends('layouts.frontend')

@section('title', 'Accueil')

@section('content')
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 text-uppercase fw-semibold">Traçabilité alimentaire</span>
                    <h1 class="mt-4 display-4 fw-bold lh-sm text-dark">De la ferme à l’assiette, chaque produit raconte son histoire.</h1>
                    <p class="lead text-secondary mt-3 mb-4">Trace Verte aide les consommateurs, producteurs et distributeurs à suivre l’origine, la transformation, la qualité et l’impact environnemental d’un aliment.</p>

                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4">Explorer les produits</a>
                        <a href="{{ route('producers.index') }}" class="btn btn-outline-secondary btn-lg px-4">Découvrir les acteurs</a>
                    </div>

                    <div class="row mt-5 g-3">
                        <div class="col-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <small class="text-muted text-uppercase">Produits</small>
                                    <div class="h2 fw-bold mt-2 mb-0">{{ $stats['products'] ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <small class="text-muted text-uppercase">Alertes</small>
                                    <div class="h2 fw-bold mt-2 mb-0">{{ $stats['alerts'] ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <small class="text-muted text-uppercase">Traçabilité</small>
                                    <div class="h2 fw-bold mt-2 mb-0">100%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow border-0 rounded-4">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3">Focus du moment</h5>
                            <p class="text-muted small mb-4">Carrefour des pratiques durables</p>
                            <div class="list-group list-group-flush">
                                @foreach($featuredProducts->take(3) as $product)
                                    <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $product->name }}</div>
                                                <small class="text-muted">{{ $product->producer?->company_name ?? 'Producteur partenaire' }}</small>
                                            </div>
                                            @if($product->environmentalFootprint)
                                                <x-footprint-score :score="(int) $product->environmentalFootprint->ai_score" />
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="tv-card h-100">
                    <p class="text-sm font-semibold uppercase tracking-wide text-brand-700">1. Origine</p>
                    <h3 class="mt-3 font-display text-2xl font-semibold text-brand-800">Suivi du producteur</h3>
                    <p class="mt-2 text-sm text-slate-600">Identifier la ferme, ses méthodes agricoles et les certifications associées.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tv-card h-100">
                    <p class="text-sm font-semibold uppercase tracking-wide text-brand-700">2. Transformation</p>
                    <h3 class="mt-3 font-display text-2xl font-semibold text-brand-800">Contrôle de la chaîne</h3>
                    <p class="mt-2 text-sm text-slate-600">Visualiser les étapes de fabrication, stockage et distribution dans un parcours clair.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tv-card h-100">
                    <p class="text-sm font-semibold uppercase tracking-wide text-brand-700">3. Impact</p>
                    <h3 class="mt-3 font-display text-2xl font-semibold text-brand-800">Mémoire écologique</h3>
                    <p class="mt-2 text-sm text-slate-600">Comparer émissions, eau et surface utilisée, avec une analyse démonstrative à destination de l’information.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-brand-700">Produits à découvrir</p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-brand-800">Des produits transparents et mieux identifiés</h2>
            </div>
            <a href="{{ route('products.index') }}" class="tv-link">Voir tous les produits</a>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-md-6 col-xl-4">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </section>

    @if($publicAlerts->isNotEmpty())
        <section class="border-top border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <p class="text-sm font-semibold uppercase tracking-wide text-brand-700">Alertes publiques</p>
                    <h2 class="mt-2 font-display text-3xl font-semibold text-brand-800">Mises en garde et vigilance</h2>
                </div>
                <div class="row g-4">
                    @foreach($publicAlerts as $alert)
                        <div class="col-lg-4">
                            <div class="tv-card h-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <x-alert-badge :alert="$alert" />
                                    <span class="text-xs text-slate-500">{{ $alert->detected_at?->format('d/m/Y') }}</span>
                                </div>
                                <h3 class="mt-4 font-semibold text-slate-800">{{ $alert->title }}</h3>
                                <p class="mt-2 text-sm text-slate-600">{{ Str::limit($alert->description ?? 'Aucune description détaillée déposée.', 140) }}</p>
                                <p class="mt-4 text-xs text-slate-500">Produit : {{ $alert->product?->name ?? 'Produit concerné' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
