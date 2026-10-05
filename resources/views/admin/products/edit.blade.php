@extends('layouts.backend')

@section('title', 'Modifier produit')
@section('page-title', 'Modifier produit')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.products.update', $item) }}" class="space-y-6">
            @csrf
            @method('PUT')
                        <div class="grid gap-4 md:grid-cols-2">
                <x-form.field label="Nom" name="name" :value="old('name', $item->name ?? '')" required />
                <x-form.field label="Code-barres" name="barcode" :value="old('barcode', $item->barcode ?? '')" />
                <x-form.field label="SKU" name="sku" :value="old('sku', $item->sku ?? '')" />
                <x-form.field label="Catégorie" name="category" :value="old('category', $item->category ?? '')" />
                <x-form.field label="Origine" name="origin" :value="old('origin', $item->origin ?? '')" />
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1" required>
                        @foreach(['draft','published','archived'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $item->status ?? 'draft') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="producer_id" value="Producteur" />
                    <select name="producer_id" id="producer_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($producers as $p)
                            <option value="{{ $p->id }}" @selected(old('producer_id', $item->producer_id ?? '') == $p->id)>{{ $p->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="transformer_id" value="Transformateur" />
                    <select name="transformer_id" id="transformer_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($transformers as $t)
                            <option value="{{ $t->id }}" @selected(old('transformer_id', $item->transformer_id ?? '') == $t->id)>{{ $t->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="ingredients" value="Ingrédients" />
                    <textarea name="ingredients" id="ingredients" rows="3" class="tv-input mt-1">{{ old('ingredients', $item->ingredients ?? '') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.products.show', $item) }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection