@extends('layouts.backend')

@section('title', 'Détail de la vérification')
@section('page-title', 'Détail de la vérification')

@section('content')
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.compliance-checks.edit', $item) }}" class="tv-btn-primary">Modifier</a>
        <a href="{{ route('admin.compliance-checks.index') }}" class="tv-btn-secondary">Retour</a>
        <form action="{{ route('admin.compliance-checks.destroy', $item) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf @method('DELETE')
            <button class="tv-btn-danger" type="submit">Supprimer</button>
        </form>
    </div>

    <div class="tv-card">
        <dl class="tv-dl">
            <div><dt>Déclaration</dt><dd>{{ $item->environmentalClaim?->title ?? '—' }}</dd></div>
            <div><dt>Produit</dt><dd>{{ $item->product?->name ?? '—' }}</dd></div>
            <div><dt>Type</dt><dd>{{ $item->check_type ?? '—' }}</dd></div>
            <div><dt>Statut</dt><dd>{{ $item->status ?? '—' }}</dd></div>
            <div><dt>Date</dt><dd>{{ $item->checked_at?->format('d/m/Y') ?? '—' }}</dd></div>
            <div class="sm:col-span-2"><dt>Résultat</dt><dd>{{ $item->result_summary ?? '—' }}</dd></div>
            <div class="sm:col-span-2"><dt>Notes</dt><dd>{{ $item->notes ?? '—' }}</dd></div>
        </dl>
    </div>

    <div class="tv-card mt-6">
        <h3 class="tv-section-title">Alertes associées</h3>
        <ul class="list-disc pl-5 text-sm text-slate-700">
            @forelse($item->alerts as $alert)
                <li>{{ $alert->title }} — {{ $alert->status }}</li>
            @empty
                <li>Aucune alerte liée à cette vérification.</li>
            @endforelse
        </ul>
    </div>
@endsection
