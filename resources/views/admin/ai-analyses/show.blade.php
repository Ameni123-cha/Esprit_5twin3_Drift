@extends('layouts.backend')

@section('title', 'Détail analyse')
@section('page-title', 'Détail analyse')

@section('content')
    <x-flash-messages />
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.ai-analyses.edit', $item) }}" class="tv-btn-primary">Modifier</a>
        <a href="{{ route('admin.ai-analyses.index') }}" class="tv-btn-secondary">Retour</a>
        <form action="{{ route('admin.ai-analyses.destroy', $item) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf @method('DELETE')
            <button class="tv-btn-danger" type="submit">Supprimer</button>
        </form>
    </div>

    <div class="tv-card">
        <dl class="tv-dl">
            <div><dt>Produit</dt><dd>{{ $item->product?->name ?? '—' }}</dd></div>
<div><dt>Type</dt><dd>{{ $item->analysis_type ?? '—' }}</dd></div>
<div><dt>Score greenwashing</dt><dd>{{ $item->greenwashing_score ?? '—' }}</dd></div>
<div><dt>Démo</dt><dd>{{ $item->is_demo ?? '—' }}</dd></div>

        </dl>
    </div>
    
@endsection