@extends('layouts.frontend')

@section('title', 'Transformateurs')

@section('content')
    <section class="py-5 bg-light">
        <div class="container py-5">
            <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 text-uppercase fw-semibold">Acteurs</span>
            <h1 class="mt-4 display-5 fw-bold text-dark">Transformateurs</h1>
            <p class="lead text-secondary mt-3 mb-0">Les structures qui transforment les matières premières et assurent la qualité du produit fini.</p>
        </div>
    </section>

    <section class="container py-5">
        @if($transformers->isEmpty())
            <x-empty-state title="Aucun transformateur" message="Les transformateurs apparaîtront ici dès leur inscription." />
        @else
            <div class="row g-4">
                @foreach($transformers as $transformer)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary to-success p-4 text-white">
                                <span class="badge rounded-pill bg-white text-primary px-2 py-1">Transformation</span>
                                <h2 class="mt-3 h4 fw-bold mb-0">{{ $transformer->company_name }}</h2>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <p class="text-secondary mb-3">{{ $transformer->location ?? 'Localisation non renseignée' }}</p>
                                <div class="mt-auto d-flex align-items-center justify-content-between">
                                    <span class="text-muted small">{{ $transformer->products_count ?? 0 }} produit(s)</span>
                                    <a href="{{ route('transformers.show', $transformer) }}" class="btn btn-link text-success p-0">Voir le profil</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">
                {{ $transformers->links() }}
            </div>
        @endif
    </section>
@endsection
