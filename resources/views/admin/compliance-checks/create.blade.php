@extends('layouts.backend')

@section('title', 'Ajouter une vérification')
@section('page-title', 'Ajouter une vérification')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.compliance-checks.store') }}" class="space-y-6">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="environmental_claim_id" value="Déclaration environnementale" />
                    <select name="environmental_claim_id" id="environmental_claim_id" class="tv-input mt-1" required>
                        @foreach($environmentalClaims as $claim)
                            <option value="{{ $claim->id }}" @selected(old('environmental_claim_id') == $claim->id)>{{ $claim->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="product_id" value="Produit" />
                    <select name="product_id" id="product_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Type de vérification" name="check_type" :value="old('check_type')" required />
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1" required>
                        @foreach(['pending','passed','failed','needs_review'] as $status)
                            <option value="{{ $status }}" @selected(old('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Date de vérification" name="checked_at" type="date" :value="old('checked_at')" />
                <div class="md:col-span-2">
                    <x-input-label for="result_summary" value="Résultat" />
                    <textarea name="result_summary" id="result_summary" rows="3" class="tv-input mt-1">{{ old('result_summary') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="notes" value="Notes" />
                    <textarea name="notes" id="notes" rows="3" class="tv-input mt-1">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Enregistrer</button>
                <a href="{{ route('admin.compliance-checks.index') }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
