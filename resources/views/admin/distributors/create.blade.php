@extends('layouts.backend')

@section('title', 'Ajouter distributeur')
@section('page-title', 'Ajouter un distributeur')

@section('content')
    @php($item = $item ?? null)
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.distributors.store') }}" class="space-y-6">
            @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                <x-form.field label="Nom de l'entreprise" name="company_name" :value="old('company_name', $item?->company_name ?? '')" required />
                <div>
                    <x-input-label for="user_id" value="Compte utilisateur" />
                    <select name="user_id" id="user_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id', $item?->user_id ?? '') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Type" name="distributor_type" :value="old('distributor_type', $item?->distributor_type ?? '')" />
                <x-form.field label="Localisation" name="location" :value="old('location', $item?->location ?? '')" />
                <x-form.field label="Latitude" name="latitude" type="number" step="any" :value="old('latitude', $item?->latitude ?? '')" />
                <x-form.field label="Longitude" name="longitude" type="number" step="any" :value="old('longitude', $item?->longitude ?? '')" />
                <x-form.field label="Zone de couverture" name="coverage_area" :value="old('coverage_area', $item?->coverage_area ?? '')" />
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Enregistrer</button>
                <a href="{{ route('admin.distributors.index') }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection