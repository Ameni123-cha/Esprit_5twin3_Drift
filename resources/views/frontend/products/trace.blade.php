@extends('layouts.frontend')

@section('title', 'Traçabilité de '.$product->name)

@section('content')
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="d-flex flex-wrap gap-3 align-items-center mb-4">
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary">Retour au produit</a>
            </div>

            <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 text-uppercase fw-semibold">Traçabilité</span>
            <h1 class="mt-4 display-5 fw-bold text-dark">{{ $product->name }}</h1>
        </div>
    </section>

    <section class="container pb-5">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-4 px-4 px-lg-5">
                <h2 class="h4 fw-bold text-dark mb-0">Historique de parcours</h2>
            </div>
            <div class="card-body p-4 p-lg-5">
                <x-trace-timeline :traces="$product->supplyChainTraces" />
            </div>
        </div>
    </section>
@endsection
