@extends('layouts.backend')

@section('title', 'Ajouter producteur')
@section('page-title', 'Ajouter un producteur')

@section('content')
    @php($item = $item ?? null)
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.producers.store') }}" class="space-y-6">
            @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                <x-form.field label="Nom de l'entreprise" name="company_name" :value="old('company_name', $item?->company_name ?? '')" required />
                <div>
                    <x-input-label for="user_id" value="Compte utilisateur" />
                    <select name="user_id" id="user_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id', $item?->user_id ?? '') == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Localisation" name="location" :value="old('location', $item?->location ?? '')" />
                <x-form.field label="Méthode agricole" name="farming_method" :value="old('farming_method', $item?->farming_method ?? '')" />
                <x-form.field label="Latitude" name="latitude" type="number" step="any" :value="old('latitude', $item?->latitude ?? '')" />
                <x-form.field label="Longitude" name="longitude" type="number" step="any" :value="old('longitude', $item?->longitude ?? '')" />
                <x-form.field label="Capacité de production" name="production_capacity" :value="old('production_capacity', $item?->production_capacity ?? '')" />
                <x-form.field label="Cultures (séparées par des virgules)" name="crop_types" :value="old('crop_types', isset($item) && is_array($item?->crop_types) ? implode(', ', $item?->crop_types) : '')" />
                <x-form.field label="Certifications (virgules)" name="certifications" :value="old('certifications', isset($item) && is_array($item?->certifications) ? implode(', ', $item?->certifications) : '')" />
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Enregistrer</button>
                <a href="{{ route('admin.producers.index') }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection