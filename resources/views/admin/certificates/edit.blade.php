@extends('layouts.backend')

@section('title', 'Modifier certification')
@section('page-title', 'Modifier certification')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.certificates.update', $item) }}" class="space-y-6">
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
                <x-form.field label="Type" name="certificate_type" :value="old('certificate_type', $item->certificate_type ?? '')" required />
                <x-form.field label="Émetteur" name="issuer" :value="old('issuer', $item->issuer ?? '')" />
                <x-form.field label="Numéro" name="certificate_number" :value="old('certificate_number', $item->certificate_number ?? '')" />
                <x-form.field label="Date d'émission" name="issue_date" type="date" :value="old('issue_date', isset($item) && $item->issue_date ? $item->issue_date->format('Y-m-d') : '')" />
                <x-form.field label="Date d'expiration" name="expiry_date" type="date" :value="old('expiry_date', isset($item) && $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '')" />
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1" required>
                        @foreach(['pending','verified','expired','rejected'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $item->status ?? 'pending') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Une certification enregistrée n'est pas automatiquement vérifiée.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.certificates.show', $item) }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection