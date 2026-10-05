@extends('layouts.backend')

@section('title', 'Détail avis')
@section('page-title', 'Détail avis')

@section('content')
    <x-flash-messages />
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.reviews.edit', $item) }}" class="tv-btn-primary">Modifier</a>
        <a href="{{ route('admin.reviews.index') }}" class="tv-btn-secondary">Retour</a>
        <form action="{{ route('admin.reviews.destroy', $item) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf @method('DELETE')
            <button class="tv-btn-danger" type="submit">Supprimer</button>
        </form>
    </div>

    <div class="tv-card">
        <dl class="tv-dl">
            <div><dt>Produit</dt><dd>{{ $item->product?->name ?? '—' }}</dd></div>
<div><dt>Note</dt><dd>{{ $item->rating ?? '—' }}</dd></div>
<div><dt>Titre</dt><dd>{{ $item->title ?? '—' }}</dd></div>
<div><dt>Statut</dt><dd>{{ $item->status ?? '—' }}</dd></div>

        </dl>
    </div>
    
@endsection