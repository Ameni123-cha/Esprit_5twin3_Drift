@extends('layouts.backend')

@section('title', 'Ajouter consommateur')
@section('page-title', 'Ajouter un consommateur')

@section('content')
    @php($item = $item ?? null)
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.consumers.store') }}" class="space-y-6">
            @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="user_id" value="Utilisateur" />
                    <select name="user_id" id="user_id" class="tv-input mt-1" required>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id', $item?->user_id ?? '') == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Niveau durabilité" name="sustainability_level" :value="old('sustainability_level', $item?->sustainability_level ?? '')" />
                <x-form.field label="Budget" name="budget_range" :value="old('budget_range', $item?->budget_range ?? '')" />
                <x-form.field label="Préférences (virgules)" name="preferences" :value="old('preferences', isset($item) && is_array($item?->preferences) ? implode(', ', $item?->preferences) : '')" />
                <x-form.field label="Restrictions alimentaires (virgules)" name="dietary_restrictions" :value="old('dietary_restrictions', isset($item) && is_array($item?->dietary_restrictions) ? implode(', ', $item?->dietary_restrictions) : '')" />
                <x-form.field label="Allergies (virgules)" name="allergies" :value="old('allergies', isset($item) && is_array($item?->allergies) ? implode(', ', $item?->allergies) : '')" />
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Enregistrer</button>
                <a href="{{ route('admin.consumers.index') }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection