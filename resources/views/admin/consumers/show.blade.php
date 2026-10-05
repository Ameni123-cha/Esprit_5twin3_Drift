@extends('layouts.backend')

@section('title', 'Détail consommateur')
@section('page-title', 'Détail consommateur')

@section('content')
    <x-flash-messages />
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.consumers.edit', $item) }}" class="tv-btn-primary">Modifier</a>
        <a href="{{ route('admin.consumers.index') }}" class="tv-btn-secondary">Retour</a>
        <form action="{{ route('admin.consumers.destroy', $item) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf @method('DELETE')
            <button class="tv-btn-danger" type="submit">Supprimer</button>
        </form>
    </div>

    <div class="tv-card">
        <dl class="tv-dl">
            <div><dt>Utilisateur</dt><dd>{{ $item->user?->name ?? '—' }}</dd></div>
<div><dt>Niveau</dt><dd>{{ $item->sustainability_level ?? '—' }}</dd></div>
<div><dt>Budget</dt><dd>{{ $item->budget_range ?? '—' }}</dd></div>
<div><dt>Évaluations</dt><dd>{{ $item->personal_ratings_count ?? '—' }}</dd></div>

        </dl>
    </div>
    
@endsection