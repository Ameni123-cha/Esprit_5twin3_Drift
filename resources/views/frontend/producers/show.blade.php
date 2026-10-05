@extends('layouts.frontend')

@section('title', $producer->company_name)

@section('content')
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="d-flex flex-wrap gap-3 align-items-center mb-4">
                <a href="{{ route('producers.index') }}" class="btn btn-outline-secondary">Retour</a>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="bg-gradient-to-r from-success to-emerald-600 p-4 p-lg-5 text-white">
                    <span class="badge rounded-pill bg-white text-success px-3 py-2 mb-3">Producteur</span>
                    <h1 class="display-6 fw-bold mb-0">{{ $producer->company_name }}</h1>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="list-group list-group-flush rounded-4 border">
                                <div class="list-group-item px-3 py-3">
                                    <small class="text-uppercase text-muted">Localisation</small>
                                    <div class="fw-semibold mt-1 text-dark">{{ $producer->location ?? '—' }}</div>
                                </div>
                                <div class="list-group-item px-3 py-3">
                                    <small class="text-uppercase text-muted">Méthode</small>
                                    <div class="fw-semibold mt-1 text-dark">{{ $producer->farming_method ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="list-group list-group-flush rounded-4 border">
                                <div class="list-group-item px-3 py-3">
                                    <small class="text-uppercase text-muted">Capacité</small>
                                    <div class="fw-semibold mt-1 text-dark">{{ $producer->production_capacity ?? '—' }}</div>
                                </div>
                                <div class="list-group-item px-3 py-3">
                                    <small class="text-uppercase text-muted">Produits publiés</small>
                                    <div class="fw-semibold mt-1 text-dark">{{ $producer->products()->where('status', 'published')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container pb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold text-dark mb-0">Produits associés</h2>
        </div>

        @if($producer->products->isEmpty())
            <x-empty-state title="Aucun produit" message="Ce producteur n’a pas encore de produits publiés." />
        @else
            <div class="row g-4">
                @foreach($producer->products as $product)
                    <div class="col-md-6 col-xl-4">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
