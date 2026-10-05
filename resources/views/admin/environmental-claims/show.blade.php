@extends('layouts.backend')

@section('title', 'Détail de la déclaration')
@section('page-title', 'Détail de la déclaration')

@section('content')
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.environmental-claims.edit', $item) }}" class="tv-btn-primary">Modifier</a>
        <a href="{{ route('admin.environmental-claims.index') }}" class="tv-btn-secondary">Retour</a>
        <form action="{{ route('admin.environmental-claims.destroy', $item) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf @method('DELETE')
            <button class="tv-btn-danger" type="submit">Supprimer</button>
        </form>
    </div>

    <div class="tv-card">
        <dl class="tv-dl">
            <div><dt>Produit</dt><dd>{{ $item->product?->name ?? '—' }}</dd></div>
            <div><dt>Type</dt><dd>{{ $item->claim_type ?? '—' }}</dd></div>
            <div><dt>Titre</dt><dd>{{ $item->title ?? '—' }}</dd></div>
            <div><dt>Statut</dt><dd>{{ $item->status ?? '—' }}</dd></div>
            <div><dt>Score</dt><dd>{{ $item->confidence_score ?? '—' }}</dd></div>
            <div><dt>Document source</dt><dd>{{ $item->source_document ?? '—' }}</dd></div>
            <div class="sm:col-span-2"><dt>Description</dt><dd>{{ $item->description ?? '—' }}</dd></div>
        </dl>
    </div>

    <div class="tv-card mt-6">
        <h3 class="tv-section-title">Vérifications associées</h3>
        <ul class="list-disc pl-5 text-sm text-slate-700">
            @forelse($item->complianceChecks as $check)
                <li>{{ $check->check_type }} — {{ $check->status }}</li>
            @empty
                <li>Aucune vérification créée pour cette déclaration.</li>
            @endforelse
        </ul>
    </div>
@endsection
