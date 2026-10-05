@extends('layouts.frontend')

@section('title', $product->name)

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('products.index') }}" class="tv-btn-secondary">Retour au catalogue</a>
            <a href="{{ route('products.trace', $product) }}" class="tv-btn-primary">Voir la traçabilité</a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-brand-800 via-brand-700 to-brand-500 p-8 text-white">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-brand-100">Produit</p>
                        <h1 class="mt-2 font-display text-4xl font-semibold">{{ $product->name }}</h1>
                    </div>
                    @if($product->environmentalFootprint)
                        <x-footprint-score :score="(int) $product->environmentalFootprint->ai_score" />
                    @endif
                </div>
            </div>
            <div class="grid gap-6 p-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-6">
                    <div class="tv-card">
                        <h2 class="tv-section-title">Informations principales</h2>
                        <dl class="tv-dl">
                            <div><dt>Catégorie</dt><dd>{{ $product->category ?? '—' }}</dd></div>
                            <div><dt>Origine</dt><dd>{{ $product->origin ?? '—' }}</dd></div>
                            <div><dt>Code-barres</dt><dd>{{ $product->barcode ?? '—' }}</dd></div>
                            <div><dt>SKU</dt><dd>{{ $product->sku ?? '—' }}</dd></div>
                            <div><dt>Producteur</dt><dd>{{ $product->producer?->company_name ?? '—' }}</dd></div>
                            <div><dt>Transformateur</dt><dd>{{ $product->transformer?->company_name ?? '—' }}</dd></div>
                            <div class="sm:col-span-2"><dt>Ingrédients</dt><dd>{{ $product->ingredients ?? '—' }}</dd></div>
                        </dl>
                    </div>

                    @if($product->environmentalFootprint)
                        <div class="tv-card">
                            <h2 class="tv-section-title">Empreinte environnementale</h2>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div class="rounded-xl bg-brand-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-brand-700">CO₂</p>
                                    <p class="mt-2 text-2xl font-semibold text-brand-800">{{ $product->environmentalFootprint->co2_emissions ?? '—' }} kg</p>
                                </div>
                                <div class="rounded-xl bg-brand-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-brand-700">Eau</p>
                                    <p class="mt-2 text-2xl font-semibold text-brand-800">{{ $product->environmentalFootprint->water_usage ?? '—' }} L</p>
                                </div>
                                <div class="rounded-xl bg-brand-50 p-4">
                                    <p class="text-xs uppercase tracking-wide text-brand-700">Terres</p>
                                    <p class="mt-2 text-2xl font-semibold text-brand-800">{{ $product->environmentalFootprint->land_usage ?? '—' }} ha</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="tv-card">
                        <h2 class="tv-section-title">Certifications</h2>
                        @if($product->certificates->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->certificates as $certificate)
                                    <x-certificate-badge :status="$certificate->status" :label="$certificate->certificate_type" />
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-600">Aucune certification enregistrée pour ce produit.</p>
                        @endif
                    </div>

                    <div class="tv-card">
                        <h2 class="tv-section-title">Avis consommateurs</h2>
                        @if($product->approvedReviews->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($product->approvedReviews->take(3) as $review)
                                    <x-review-card :review="$review" />
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-600">Aucun avis vérifié n’a encore été publié pour ce produit.</p>
                        @endif
                    </div>

                    <div class="tv-card">
                        <h2 class="tv-section-title">Déclarations environnementales M5</h2>
                        @if($product->environmentalClaims->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($product->environmentalClaims as $claim)
                                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.18em] text-emerald-700">{{ $claim->claim_type ?? 'Déclaration' }}</p>
                                                <h3 class="mt-1 text-lg font-semibold text-slate-800">{{ $claim->title }}</h3>
                                            </div>
                                            <span class="inline-flex rounded-full border border-emerald-300 bg-white px-2.5 py-1 text-xs font-medium text-emerald-700">
                                                {{ ucfirst($claim->status ?? 'pending') }}
                                            </span>
                                        </div>

                                        <p class="mt-3 text-sm text-slate-700">{{ $claim->description }}</p>

                                        <div class="mt-3 flex flex-wrap gap-4 text-xs text-slate-600">
                                            <span>Confiance: {{ $claim->confidence_score ?? 0 }}%</span>
                                            <span>Document: {{ $claim->source_document ?? 'Non référencé' }}</span>
                                        </div>

                                        @if($claim->complianceChecks->isNotEmpty())
                                            <div class="mt-4 space-y-2 border-t border-emerald-200 pt-3">
                                                @foreach($claim->complianceChecks as $check)
                                                    <div class="rounded-xl bg-white p-3">
                                                        <div class="flex items-center justify-between gap-2">
                                                            <span class="text-sm font-medium text-slate-700">{{ ucfirst(str_replace('_', ' ', $check->check_type ?? 'vérification')) }}</span>
                                                            <span class="text-xs font-medium {{ $check->status === 'passed' ? 'text-emerald-700' : ($check->status === 'failed' ? 'text-red-700' : 'text-amber-700') }}">
                                                                {{ str_replace('_', ' ', $check->status) }}
                                                            </span>
                                                        </div>
                                                        <p class="mt-1 text-xs text-slate-600">{{ $check->result_summary }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-600">Aucune déclaration environnementale n’a été publiée pour ce produit.</p>
                        @endif
                    </div>

                    <div class="tv-card">
                        <h2 class="tv-section-title">Vérifications de conformité</h2>
                        @if($product->complianceChecks->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($product->complianceChecks as $check)
                                    <div class="rounded-xl border border-slate-200 p-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-sm font-medium text-slate-700">{{ ucfirst(str_replace('_', ' ', $check->check_type ?? 'vérification')) }}</span>
                                            <span class="text-xs font-medium {{ $check->status === 'passed' ? 'text-emerald-700' : ($check->status === 'failed' ? 'text-red-700' : 'text-amber-700') }}">
                                                {{ str_replace('_', ' ', $check->status) }}
                                            </span>
                                        </div>
                                        <p class="mt-2 text-sm text-slate-600">{{ $check->result_summary }}</p>
                                        @if($check->environmentalClaim)
                                            <p class="mt-2 text-xs text-slate-500">Déclaration: {{ $check->environmentalClaim->title }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-600">Aucune vérification de conformité enregistrée pour ce produit.</p>
                        @endif
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="tv-card">
                        <h2 class="tv-section-title">Alertes publiques</h2>
                        @if($product->openAlerts->isNotEmpty())
                            @foreach($product->openAlerts as $alert)
                                <div class="mb-3 rounded-xl border border-red-100 bg-red-50 p-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <x-alert-badge :alert="$alert" />
                                        <span class="text-xs text-red-700">{{ $alert->severity }}</span>
                                    </div>
                                    <p class="mt-2 text-sm font-medium text-slate-800">{{ $alert->title }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-slate-600">Aucune alerte publique active pour ce produit.</p>
                        @endif
                    </div>

                    <div class="tv-card">
                        <h2 class="tv-section-title">Analyses IA</h2>
                        @if($product->aiAnalyses->isNotEmpty())
                            @foreach($product->aiAnalyses->take(2) as $analysis)
                                <div class="rounded-xl border border-slate-200 p-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm font-medium text-slate-700">{{ $analysis->analysis_type ?? 'Analyse' }}</span>
                                        <x-footprint-score :score="(int) ($analysis->greenwashing_score ?? 0)" />
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500">Modèle : {{ $analysis->model_used ?? 'Démonstration' }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-slate-600">Aucune analyse de démonstration enregistrée.</p>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
