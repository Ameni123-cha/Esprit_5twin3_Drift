@extends('layouts.backend')

@section('title', 'Analyses IA')
@section('page-title', 'Analyses IA')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher…" class="tv-input w-56">
            
            <button class="tv-btn-secondary" type="submit">Filtrer</button>
        </form>
        <a href="{{ route('admin.ai-analyses.create') }}" class="tv-btn-primary">Ajouter</a>
    </div>

    <x-flash-messages />

    <div class="tv-card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="tv-table">
                <thead>
                    <tr>
                        <th>Produit</th><th>Type</th><th>Score greenwashing</th><th>Démo</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->product?->name ?? '—' }}</td><td>{{ $item->analysis_type ?? '—' }}</td><td>{{ $item->greenwashing_score ?? '—' }}</td><td>{{ $item->is_demo ?? '—' }}</td>
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.ai-analyses.show', $item) }}" class="tv-link">Voir</a>
                                <a href="{{ route('admin.ai-analyses.edit', $item) }}" class="tv-link ml-2">Modifier</a>
                                <form action="{{ route('admin.ai-analyses.destroy', $item) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Confirmer la suppression ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($cfg['columns']) + 1 }}">
                                <x-empty-state title="Aucun analyse" message="Commencez par créer une entrée." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div class="p-4 border-t border-slate-100">{{ $items->links() }}</div>
        @endif
    </div>
@endsection