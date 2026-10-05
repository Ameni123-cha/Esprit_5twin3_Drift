@extends('layouts.frontend')

@section('title', 'Admin – Tableau de bord')

@section('content')
    <div class="container py-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Tableau de bord</h1>
                <small class="text-muted">Vue d'ensemble administrative</small>
            </div>
            <div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">Gérer les produits</a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <small class="text-muted">Produits</small>
                    <div class="h4 mt-2">{{ $stats['products'] ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <small class="text-muted">Producteurs</small>
                    <div class="h4 mt-2">{{ $stats['producers'] ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <small class="text-muted">Transformateurs</small>
                    <div class="h4 mt-2">{{ $stats['transformers'] ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <small class="text-muted">Consommateurs</small>
                    <div class="h4 mt-2">{{ $stats['consumers'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Produits récents</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($recentProducts as $p)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $p->name }}</strong>
                                        <div class="text-muted small">{{ $p->producer?->company_name ?? '—' }}</div>
                                    </div>
                                    <div class="text-end small">
                                        @if($p->environmentalFootprint)
                                            <x-footprint-score :score="(int) $p->environmentalFootprint->ai_score" />
                                        @endif
                                        <div class="text-muted">{{ $p->created_at?->format('d/m/Y') }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Alertes récentes</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($recentAlerts as $a)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $a->title }}</strong>
                                        <div class="text-muted small">Produit: {{ $a->product?->name ?? '—' }}</div>
                                    </div>
                                    <div class="text-end small text-muted">{{ $a->detected_at?->format('d/m/Y') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-muted small">Dernière mise à jour: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
@endsection
