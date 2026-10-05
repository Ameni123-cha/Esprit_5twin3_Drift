@extends('layouts.backend')

@section('title', 'Ajouter alerte')
@section('page-title', 'Ajouter un alerte')

@section('content')
    @php($item = $item ?? null)
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.alerts.store') }}" class="space-y-6">
            @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="product_id" value="Produit" />
                    <select name="product_id" id="product_id" class="tv-input mt-1" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id', $item?->product_id ?? '') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Type" name="alert_type" :value="old('alert_type', $item?->alert_type ?? '')" required />
                <x-form.field label="Titre" name="title" :value="old('title', $item?->title ?? '')" required />
                <div>
                    <x-input-label for="severity" value="Sévérité" />
                    <select name="severity" id="severity" class="tv-input mt-1" required>
                        @foreach(['low','medium','high','critical'] as $st)
                            <option value="{{ $st }}" @selected(old('severity', $item?->severity ?? 'medium') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1" required>
                        @foreach(['open','investigating','resolved','dismissed'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $item?->status ?? 'open') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Détectée le" name="detected_at" type="datetime-local" :value="old('detected_at', isset($item) && $item?->detected_at ? $item?->detected_at->format('Y-m-d\\TH:i') : '')" />
                <div class="md:col-span-2">
                    <x-input-label for="description" value="Description" />
                    <textarea name="description" id="description" rows="3" class="tv-input mt-1">{{ old('description', $item?->description ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="resolution" value="Résolution" />
                    <textarea name="resolution" id="resolution" rows="2" class="tv-input mt-1">{{ old('resolution', $item?->resolution ?? '') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Enregistrer</button>
                <a href="{{ route('admin.alerts.index') }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection