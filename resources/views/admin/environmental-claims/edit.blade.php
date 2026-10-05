@extends('layouts.backend')

@section('title', 'Modifier une déclaration')
@section('page-title', 'Modifier une déclaration')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.environmental-claims.update', $item) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="product_id" value="Produit" />
                    <select name="product_id" id="product_id" class="tv-input mt-1" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id', $item->product_id) == $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Type de déclaration" name="claim_type" :value="old('claim_type', $item->claim_type)" required />
                <x-form.field label="Titre" name="title" :value="old('title', $item->title)" required />
                <x-form.field label="Document source" name="source_document" :value="old('source_document', $item->source_document)" />
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1">
                        @foreach(['pending','verified','rejected'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $item->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Score de confiance" name="confidence_score" type="number" :value="old('confidence_score', $item->confidence_score)" />
                <div class="md:col-span-2">
                    <x-input-label for="description" value="Description" />
                    <textarea name="description" id="description" rows="3" class="tv-input mt-1">{{ old('description', $item->description) }}</textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Enregistrer</button>
                <a href="{{ route('admin.environmental-claims.index') }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
