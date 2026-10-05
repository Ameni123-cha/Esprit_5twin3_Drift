@extends('layouts.backend')

@section('title', 'Modifier trace')
@section('page-title', 'Modifier trace')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.supply-chain-traces.update', $item) }}" class="space-y-6">
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
                <div>
                    <x-input-label for="distributor_id" value="Distributeur" />
                    <select name="distributor_id" id="distributor_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($distributors as $d)
                            <option value="{{ $d->id }}" @selected(old('distributor_id', $item->distributor_id ?? '') == $d->id)>{{ $d->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Étape actuelle" name="current_stage" :value="old('current_stage', $item->current_stage ?? '')" />
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1" required>
                        @foreach(['in_transit','delivered','delayed','completed'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $item->status ?? 'in_transit') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Latitude" name="current_location_lat" type="number" step="any" :value="old('current_location_lat', $item->current_location_lat ?? '')" />
                <x-form.field label="Longitude" name="current_location_lon" type="number" step="any" :value="old('current_location_lon', $item->current_location_lon ?? '')" />
                <x-form.field label="Distance totale (km)" name="total_distance_km" type="number" step="0.01" :value="old('total_distance_km', $item->total_distance_km ?? '')" />
                <div class="md:col-span-2">
                    <x-input-label for="path_history_json" value="Historique (JSON)" />
                    <textarea name="path_history_json" id="path_history_json" rows="4" class="tv-input mt-1 font-mono text-sm">{{ old('path_history_json', isset($item) && $item->path_history ? json_encode($item->path_history, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
                    <x-input-error :messages="$errors->get('path_history_json')" class="mt-1" />
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.supply-chain-traces.show', $item) }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection