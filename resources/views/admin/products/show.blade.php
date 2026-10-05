@extends('layouts.backend')

@section('title', 'Détail produit')
@section('page-title', 'Détail produit')

@section('content')
    <x-flash-messages />
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.products.edit', $item) }}" class="tv-btn-primary">Modifier</a>
        <a href="{{ route('admin.products.index') }}" class="tv-btn-secondary">Retour</a>
        <form action="{{ route('admin.products.destroy', $item) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf @method('DELETE')
            <button class="tv-btn-danger" type="submit">Supprimer</button>
        </form>
    </div>

    <div class="tv-card">
        <dl class="tv-dl">
            <div><dt>Nom</dt><dd>{{ $item->name ?? '—' }}</dd></div>
<div><dt>Catégorie</dt><dd>{{ $item->category ?? '—' }}</dd></div>
<div><dt>Statut</dt><dd>{{ $item->status ?? '—' }}</dd></div>
<div><dt>Producteur</dt><dd>{{ $item->producer?->company_name ?? '—' }}</dd></div>

        </dl>
    </div>
                <div class="tv-card mt-6">
                <h3 class="tv-section-title">Relations</h3>
                <dl class="tv-dl">
                    <div><dt>Producteur</dt><dd>{{ $item->producer?->company_name ?? '—' }}</dd></div>
                    <div><dt>Transformateur</dt><dd>{{ $item->transformer?->company_name ?? '—' }}</dd></div>
                    <div><dt>Empreinte</dt><dd>@if($item->environmentalFootprint)<a class="tv-link" href="{{ route('admin.environmental-footprints.show', $item->environmentalFootprint) }}">Voir</a>@else — @endif</dd></div>
                    <div><dt>Certifications</dt><dd>{{ $item->certificates->count() }}</dd></div>
                    <div><dt>Avis</dt><dd>{{ $item->reviews->count() }}</dd></div>
                    <div><dt>Alertes</dt><dd>{{ $item->alerts->count() }}</dd></div>
                </dl>
            </div>
@endsection