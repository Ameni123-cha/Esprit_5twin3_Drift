@extends('layouts.frontend')

@section('title', 'Mon profil')

@section('content')
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 text-uppercase fw-semibold">Profil</span>
                    <h1 class="mt-4 display-5 fw-bold text-dark mb-0">Mon espace consommateur</h1>
                </div>
                <a href="{{ route('consumer.recommendations') }}" class="btn btn-success btn-lg">Voir mes recommandations</a>
            </div>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4 p-lg-5">
                            <h2 class="h4 fw-bold text-dark mb-4">Informations</h2>
                            <div class="list-group list-group-flush rounded-4 border">
                                <div class="list-group-item px-0 py-3">
                                    <small class="text-uppercase text-muted">Nom</small>
                                    <div class="fw-semibold mt-1 text-dark">{{ auth()->user()->name }}</div>
                                </div>
                                <div class="list-group-item px-0 py-3">
                                    <small class="text-uppercase text-muted">Email</small>
                                    <div class="fw-semibold mt-1 text-dark">{{ auth()->user()->email }}</div>
                                </div>
                                <div class="list-group-item px-0 py-3">
                                    <small class="text-uppercase text-muted">Niveau de durabilité</small>
                                    <div class="fw-semibold mt-1 text-dark">{{ $consumer->sustainability_level ?? '—' }}</div>
                                </div>
                                <div class="list-group-item px-0 py-3 border-0">
                                    <small class="text-uppercase text-muted">Budget</small>
                                    <div class="fw-semibold mt-1 text-dark">{{ $consumer->budget_range ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('consumer.edit') }}" class="btn btn-outline-secondary">Modifier mes préférences</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4 p-lg-5">
                            <h2 class="h4 fw-bold text-dark mb-4">Préférences</h2>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($consumer->preferences ?? [] as $pref)
                                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2">{{ $pref }}</span>
                                @empty
                                    <p class="text-secondary mb-0">Aucune préférence renseignée pour le moment.</p>
                                @endforelse
                            </div>

                            <div class="row g-4 mt-2">
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100">
                                        <small class="text-uppercase text-muted">Restrictions alimentaires</small>
                                        <p class="mb-0 mt-2 text-dark">{{ empty($consumer->dietary_restrictions ?? []) ? 'Aucune' : implode(', ', $consumer->dietary_restrictions) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100">
                                        <small class="text-uppercase text-muted">Allergies</small>
                                        <p class="mb-0 mt-2 text-dark">{{ empty($consumer->allergies ?? []) ? 'Aucune' : implode(', ', $consumer->allergies) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container pb-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 fw-bold text-dark mb-4">Mes évaluations</h2>
                @if($consumer->personalRatings->isEmpty())
                    <x-empty-state title="Aucune évaluation" message="Les recommandations personnalisées apparaîtront ici lorsque vous aurez évalué des produits." />
                @else
                    <div class="row g-3">
                        @foreach($consumer->personalRatings as $rating)
                            <div class="col-12">
                                <div class="border rounded-4 p-3">
                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $rating->product?->name ?? 'Produit' }}</div>
                                            <small class="text-muted">Score personnalisé : {{ $rating->personalized_score }}/100</small>
                                        </div>
                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2">{{ $rating->personalized_score }}%</span>
                                    </div>
                                    <p class="mb-0 mt-3 text-secondary">{{ $rating->recommendation_reason ?? 'Aucune raison détaillée.' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
