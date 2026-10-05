@extends('layouts.backend')

@section('title', 'Détail trace')
@section('page-title', 'Détail trace')

@section('content')
    <x-flash-messages />
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.supply-chain-traces.edit', $item) }}" class="tv-btn-primary">Modifier</a>
        <a href="{{ route('admin.supply-chain-traces.index') }}" class="tv-btn-secondary">Retour</a>
        <form action="{{ route('admin.supply-chain-traces.destroy', $item) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf @method('DELETE')
            <button class="tv-btn-danger" type="submit">Supprimer</button>
        </form>
    </div>

    <div class="tv-card">
        <dl class="tv-dl">
            <div><dt>Produit</dt><dd>{{ $item->product?->name ?? '—' }}</dd></div>
<div><dt>Étape</dt><dd>{{ $item->current_stage ?? '—' }}</dd></div>
<div><dt>Statut</dt><dd>{{ $item->status ?? '—' }}</dd></div>
<div><dt>Distance (km)</dt><dd>{{ $item->total_distance_km ?? '—' }}</dd></div>

        </dl>
    </div>
    
@endsection