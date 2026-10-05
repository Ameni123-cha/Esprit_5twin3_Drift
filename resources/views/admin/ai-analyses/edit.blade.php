@extends('layouts.backend')

@section('title', 'Modifier analyse')
@section('page-title', 'Modifier analyse')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.ai-analyses.update', $item) }}" class="space-y-6">
            @csrf
            @method('PUT')
                        <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="product_id" value="Produit" />
                    <select name="product_id" id="product_id" class="tv-input mt-1" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id', $item->product_id ?? '') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Type d'analyse" name="analysis_type" :value="old('analysis_type', $item->analysis_type ?? '')" />
                <x-form.field label="Score greenwashing (0-100)" name="greenwashing_score" type="number" min="0" max="100" :value="old('greenwashing_score', $item->greenwashing_score ?? '')" />
                <x-form.field label="Crédibilité" name="credibility_rating" :value="old('credibility_rating', $item->credibility_rating ?? '')" />
                <x-form.field label="Modèle utilisé" name="model_used" :value="old('model_used', $item->model_used ?? 'demo-rules-v1')" />
                <div class="flex items-center gap-2 pt-6">
                    <input type="hidden" name="is_demo" value="0">
                    <input type="checkbox" name="is_demo" id="is_demo" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" @checked(old('is_demo', $item->is_demo ?? true))>
                    <label for="is_demo" class="text-sm text-slate-700">Analyse de démonstration (pas un vrai service IA)</label>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="ai_summary" value="Résumé" />
                    <textarea name="ai_summary" id="ai_summary" rows="4" class="tv-input mt-1">{{ old('ai_summary', $item->ai_summary ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-amber-700">Un score élevé de greenwashing n'est pas une preuve définitive de fraude.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.ai-analyses.show', $item) }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection