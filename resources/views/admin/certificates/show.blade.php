@extends('layouts.backend')

@section('title', 'Détail certification')
@section('page-title', 'Détail certification')

@section('content')
    <x-flash-messages />
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.certificates.edit', $item) }}" class="tv-btn-primary">Modifier</a>
        <a href="{{ route('admin.certificates.index') }}" class="tv-btn-secondary">Retour</a>
        <form action="{{ route('admin.certificates.destroy', $item) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf @method('DELETE')
            <button class="tv-btn-danger" type="submit">Supprimer</button>
        </form>
    </div>

    <div class="tv-card">
        <dl class="tv-dl">
            <div><dt>Produit</dt><dd>{{ $item->product?->name ?? '—' }}</dd></div>
<div><dt>Type</dt><dd>{{ $item->certificate_type ?? '—' }}</dd></div>
<div><dt>Émetteur</dt><dd>{{ $item->issuer ?? '—' }}</dd></div>
<div><dt>Statut</dt><dd>{{ $item->status ?? '—' }}</dd></div>

        </dl>
    </div>
    
@endsection