@extends('layouts.frontend')

@section('title', 'Modifier mes préférences')

@section('content')
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-lg-5">
                            <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 text-uppercase fw-semibold">Profil</span>
                            <h1 class="mt-4 display-6 fw-bold text-dark">Modifier mes préférences</h1>
                            <p class="text-secondary mt-3 mb-4">Ces informations servent à personnaliser les recommandations de produits, sans constituer un diagnostic médical.</p>

                            <form method="POST" action="{{ route('consumer.update') }}" novalidate>
                                @csrf
                                @method('PATCH')

                                @if(session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <div class="row g-4">
                                    <div class="col-12">
                                        <label for="preferences" class="form-label fw-semibold">Préférences</label>
                                        <input id="preferences" name="preferences" value="{{ old('preferences', implode(', ', $consumer->preferences ?? [])) }}" class="form-control form-control-lg" placeholder="local, bio, faible_co2">
                                        <small class="form-text text-muted">Séparez les préférences par des virgules (ex. local, bio).</small>
                                        @error('preferences')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="sustainability_level" class="form-label fw-semibold">Niveau de durabilité</label>
                                        <select id="sustainability_level" name="sustainability_level" class="form-select form-select-lg">
                                            @foreach(['débutant','intermédiaire','engagé','expert'] as $level)
                                                <option value="{{ $level }}" @selected(old('sustainability_level', $consumer->sustainability_level) === $level)>{{ $level }}</option>
                                            @endforeach
                                        </select>
                                        @error('sustainability_level')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="budget_range" class="form-label fw-semibold">Budget</label>
                                        <select id="budget_range" name="budget_range" class="form-select form-select-lg">
                                            @foreach(['éco','moyen','premium'] as $range)
                                                <option value="{{ $range }}" @selected(old('budget_range', $consumer->budget_range) === $range)>{{ $range }}</option>
                                            @endforeach
                                        </select>
                                        @error('budget_range')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="dietary_restrictions" class="form-label fw-semibold">Restrictions alimentaires</label>
                                        <input id="dietary_restrictions" name="dietary_restrictions" value="{{ old('dietary_restrictions', implode(', ', $consumer->dietary_restrictions ?? [])) }}" class="form-control form-control-lg" placeholder="végétarien, sans gluten">
                                        @error('dietary_restrictions')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="allergies" class="form-label fw-semibold">Allergies</label>
                                        <input id="allergies" name="allergies" value="{{ old('allergies', implode(', ', $consumer->allergies ?? [])) }}" class="form-control form-control-lg" placeholder="gluten, lactose">
                                        @error('allergies')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-3 mt-5">
                                    <button type="submit" class="btn btn-success btn-lg">Enregistrer</button>
                                    <a href="{{ route('consumer.profile') }}" class="btn btn-outline-secondary btn-lg">Annuler</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
