@extends('layouts.backend')

@section('title', 'Modifier évaluation')
@section('page-title', 'Modifier évaluation')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.personal-ratings.update', $item) }}" class="space-y-6">
            @csrf
            @method('PUT')
                        <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="consumer_id" value="Consommateur" />
                    <select name="consumer_id" id="consumer_id" class="tv-input mt-1" required>
                        @foreach($consumers as $c)
                            <option value="{{ $c->id }}" @selected(old('consumer_id', $item->consumer_id ?? '') == $c->id)>{{ $c->user?->name ?? ('#'.$c->id) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="product_id" value="Produit" />
                    <select name="product_id" id="product_id" class="tv-input mt-1" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id', $item->product_id ?? '') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Score personnalisé (0-100)" name="personalized_score" type="number" min="0" max="100" :value="old('personalized_score', $item->personalized_score ?? '')" />
                <div class="md:col-span-2">
                    <x-input-label for="reason" value="Raison" />
                    <textarea name="reason" id="reason" rows="2" class="tv-input mt-1">{{ old('reason', $item->reason ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="recommendation_reason" value="Raison de recommandation" />
                    <textarea name="recommendation_reason" id="recommendation_reason" rows="2" class="tv-input mt-1">{{ old('recommendation_reason', $item->recommendation_reason ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Ce score n'est pas un diagnostic de santé.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.personal-ratings.show', $item) }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection