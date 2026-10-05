@extends('layouts.frontend')

@section('title', 'Produits')

@section('content')
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row align-items-end justify-content-between g-4">
                <div class="col-lg-6">
                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 text-uppercase fw-semibold">Catalogue</span>
                    <h1 class="mt-4 display-5 fw-bold text-dark mb-2">Produits traçables</h1>
                    <p class="lead text-secondary mb-0">Explorez les produits publiés avec leur origine, leur chaîne de transformation et leur impact environnemental.</p>
                </div>

                <div class="col-lg-6">
                    <form method="GET" class="card border-0 shadow-sm rounded-4 p-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-lg" placeholder="Rechercher un produit...">
                            </div>
                            <div class="col-md-4">
                                <select name="category" class="form-select form-select-lg">
                                    <option value="">Toutes catégories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button type="submit" class="btn btn-success btn-lg">Filtrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        @if($products->isEmpty())
            <x-empty-state title="Aucun produit disponible" message="Les produits publiés apparaîtront ici dès qu’ils seront renseignés dans le back-office." />
        @else
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-md-6 col-xl-4">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @endif
    </section>
@endsection
