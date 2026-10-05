@extends('layouts.backend')

@section('title', 'Modifier transformateur')
@section('page-title', 'Modifier transformateur')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.transformers.update', $item) }}" class="space-y-6">
            @csrf
            @method('PUT')
                        <div class="grid gap-4 md:grid-cols-2">
                <x-form.field label="Nom de l'entreprise" name="company_name" :value="old('company_name', $item->company_name ?? '')" required />
                <div>
                    <x-input-label for="user_id" value="Compte utilisateur" />
                    <select name="user_id" id="user_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id', $item->user_id ?? '') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Localisation" name="location" :value="old('location', $item->location ?? '')" />
                <x-form.field label="Type de transformation" name="transformation_type" :value="old('transformation_type', $item->transformation_type ?? '')" />
                <x-form.field label="Latitude" name="latitude" type="number" step="any" :value="old('latitude', $item->latitude ?? '')" />
                <x-form.field label="Longitude" name="longitude" type="number" step="any" :value="old('longitude', $item->longitude ?? '')" />
                <x-form.field label="Capacité" name="production_capacity" :value="old('production_capacity', $item->production_capacity ?? '')" />
                <x-form.field label="Certifications (virgules)" name="certifications" :value="old('certifications', isset($item) && is_array($item->certifications) ? implode(', ', $item->certifications) : '')" />
                <div class="md:col-span-2">
                    <x-input-label for="process_description" value="Description du process" />
                    <textarea name="process_description" id="process_description" rows="3" class="tv-input mt-1">{{ old('process_description', $item->process_description ?? '') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.transformers.show', $item) }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection