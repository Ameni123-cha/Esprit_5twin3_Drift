<?php
/**
 * Generate professional admin Blade CRUD views for Trace Verte.
 */
$base = __DIR__.'/resources/views/admin';

function w(string $path, string $content): void
{
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($path, $content);
    echo "OK $path\n";
}

function arrayJoinExpr(string $field): string
{
    return "{{ is_array(\$item->{$field} ?? null) ? implode(', ', \$item->{$field}) : (\$item->{$field} ?? '—') }}";
}

$entities = [
    'products' => [
        'title' => 'Produits',
        'singular' => 'produit',
        'route' => 'products',
        'columns' => [
            ['label' => 'Nom', 'key' => 'name'],
            ['label' => 'Catégorie', 'key' => 'category'],
            ['label' => 'Statut', 'key' => 'status', 'badge' => true],
            ['label' => 'Producteur', 'key' => 'producer.company_name'],
        ],
        'form' => <<<'BLADE'
            <div class="grid gap-4 md:grid-cols-2">
                <x-form.field label="Nom" name="name" :value="old('name', $item->name ?? '')" required />
                <x-form.field label="Code-barres" name="barcode" :value="old('barcode', $item->barcode ?? '')" />
                <x-form.field label="SKU" name="sku" :value="old('sku', $item->sku ?? '')" />
                <x-form.field label="Catégorie" name="category" :value="old('category', $item->category ?? '')" />
                <x-form.field label="Origine" name="origin" :value="old('origin', $item->origin ?? '')" />
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1" required>
                        @foreach(['draft','published','archived'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $item->status ?? 'draft') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="producer_id" value="Producteur" />
                    <select name="producer_id" id="producer_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($producers as $p)
                            <option value="{{ $p->id }}" @selected(old('producer_id', $item->producer_id ?? '') == $p->id)>{{ $p->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="transformer_id" value="Transformateur" />
                    <select name="transformer_id" id="transformer_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($transformers as $t)
                            <option value="{{ $t->id }}" @selected(old('transformer_id', $item->transformer_id ?? '') == $t->id)>{{ $t->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="ingredients" value="Ingrédients" />
                    <textarea name="ingredients" id="ingredients" rows="3" class="tv-input mt-1">{{ old('ingredients', $item->ingredients ?? '') }}</textarea>
                </div>
            </div>
BLADE,
        'show_extra' => <<<'BLADE'
            <div class="tv-card mt-6">
                <h3 class="tv-section-title">Relations</h3>
                <dl class="tv-dl">
                    <div><dt>Producteur</dt><dd>{{ $item->producer?->company_name ?? '—' }}</dd></div>
                    <div><dt>Transformateur</dt><dd>{{ $item->transformer?->company_name ?? '—' }}</dd></div>
                    <div><dt>Empreinte</dt><dd>@if($item->environmentalFootprint)<a class="tv-link" href="{{ route('admin.environmental-footprints.show', $item->environmentalFootprint) }}">Voir</a>@else — @endif</dd></div>
                    <div><dt>Certifications</dt><dd>{{ $item->certificates->count() }}</dd></div>
                    <div><dt>Avis</dt><dd>{{ $item->reviews->count() }}</dd></div>
                    <div><dt>Alertes</dt><dd>{{ $item->alerts->count() }}</dd></div>
                </dl>
            </div>
BLADE,
    ],
];

// Simpler generic entities
$generic = [
    'environmental-footprints' => [
        'title' => 'Empreintes environnementales',
        'singular' => 'empreinte',
        'route' => 'environmental-footprints',
        'param' => 'environmental_footprint',
        'columns' => [
            ['label' => 'Produit', 'key' => 'product.name'],
            ['label' => 'CO₂ (kg)', 'key' => 'co2_emissions'],
            ['label' => 'Eau (L)', 'key' => 'water_usage'],
            ['label' => 'Score', 'key' => 'ai_score'],
        ],
        'form' => <<<'BLADE'
            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-input-label for="product_id" value="Produit" />
                    <select name="product_id" id="product_id" class="tv-input mt-1" required>
                        <option value="">Sélectionner…</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id', $item->product_id ?? '') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('product_id')" class="mt-1" />
                </div>
                <x-form.field label="Émissions CO₂ (kg)" name="co2_emissions" type="number" step="0.01" :value="old('co2_emissions', $item->co2_emissions ?? '')" />
                <x-form.field label="Usage eau (L)" name="water_usage" type="number" step="0.01" :value="old('water_usage', $item->water_usage ?? '')" />
                <x-form.field label="Usage terres" name="land_usage" type="number" step="0.01" :value="old('land_usage', $item->land_usage ?? '')" />
                <x-form.field label="Score environnemental (0-100)" name="ai_score" type="number" min="0" max="100" :value="old('ai_score', $item->ai_score ?? '')" />
            </div>
BLADE,
    ],
    'producers' => [
        'title' => 'Producteurs',
        'singular' => 'producteur',
        'route' => 'producers',
        'columns' => [
            ['label' => 'Entreprise', 'key' => 'company_name'],
            ['label' => 'Lieu', 'key' => 'location'],
            ['label' => 'Méthode', 'key' => 'farming_method'],
            ['label' => 'Produits', 'key' => 'products_count'],
        ],
        'form' => <<<'BLADE'
            <div class="grid gap-4 md:grid-cols-2">
                <x-form.field label="Nom de l'entreprise" name="company_name" :value="old('company_name', $item->company_name ?? '')" required />
                <div>
                    <x-input-label for="user_id" value="Compte utilisateur" />
                    <select name="user_id" id="user_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id', $item->user_id ?? '') == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Localisation" name="location" :value="old('location', $item->location ?? '')" />
                <x-form.field label="Méthode agricole" name="farming_method" :value="old('farming_method', $item->farming_method ?? '')" />
                <x-form.field label="Latitude" name="latitude" type="number" step="any" :value="old('latitude', $item->latitude ?? '')" />
                <x-form.field label="Longitude" name="longitude" type="number" step="any" :value="old('longitude', $item->longitude ?? '')" />
                <x-form.field label="Capacité de production" name="production_capacity" :value="old('production_capacity', $item->production_capacity ?? '')" />
                <x-form.field label="Cultures (séparées par des virgules)" name="crop_types" :value="old('crop_types', isset($item) && is_array($item->crop_types) ? implode(', ', $item->crop_types) : '')" />
                <x-form.field label="Certifications (virgules)" name="certifications" :value="old('certifications', isset($item) && is_array($item->certifications) ? implode(', ', $item->certifications) : '')" />
            </div>
BLADE,
    ],
    'transformers' => [
        'title' => 'Transformateurs',
        'singular' => 'transformateur',
        'route' => 'transformers',
        'columns' => [
            ['label' => 'Entreprise', 'key' => 'company_name'],
            ['label' => 'Type', 'key' => 'transformation_type'],
            ['label' => 'Lieu', 'key' => 'location'],
            ['label' => 'Produits', 'key' => 'products_count'],
        ],
        'form' => <<<'BLADE'
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
BLADE,
    ],
    'distributors' => [
        'title' => 'Distributeurs',
        'singular' => 'distributeur',
        'route' => 'distributors',
        'columns' => [
            ['label' => 'Entreprise', 'key' => 'company_name'],
            ['label' => 'Type', 'key' => 'distributor_type'],
            ['label' => 'Couverture', 'key' => 'coverage_area'],
            ['label' => 'Traces', 'key' => 'supply_chain_traces_count'],
        ],
        'form' => <<<'BLADE'
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
                <x-form.field label="Type" name="distributor_type" :value="old('distributor_type', $item->distributor_type ?? '')" />
                <x-form.field label="Localisation" name="location" :value="old('location', $item->location ?? '')" />
                <x-form.field label="Latitude" name="latitude" type="number" step="any" :value="old('latitude', $item->latitude ?? '')" />
                <x-form.field label="Longitude" name="longitude" type="number" step="any" :value="old('longitude', $item->longitude ?? '')" />
                <x-form.field label="Zone de couverture" name="coverage_area" :value="old('coverage_area', $item->coverage_area ?? '')" />
            </div>
BLADE,
    ],
    'supply-chain-traces' => [
        'title' => 'Traces logistiques',
        'singular' => 'trace',
        'route' => 'supply-chain-traces',
        'param' => 'supply_chain_trace',
        'columns' => [
            ['label' => 'Produit', 'key' => 'product.name'],
            ['label' => 'Étape', 'key' => 'current_stage'],
            ['label' => 'Statut', 'key' => 'status', 'badge' => true],
            ['label' => 'Distance (km)', 'key' => 'total_distance_km'],
        ],
        'form' => <<<'BLADE'
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
BLADE,
    ],
    'certificates' => [
        'title' => 'Certifications',
        'singular' => 'certification',
        'route' => 'certificates',
        'columns' => [
            ['label' => 'Produit', 'key' => 'product.name'],
            ['label' => 'Type', 'key' => 'certificate_type'],
            ['label' => 'Émetteur', 'key' => 'issuer'],
            ['label' => 'Statut', 'key' => 'status', 'badge' => true],
        ],
        'filters' => <<<'BLADE'
            <select name="status" class="tv-input">
                <option value="">Tous statuts</option>
                @foreach(['pending','verified','expired','rejected'] as $st)
                    <option value="{{ $st }}" @selected(request('status')===$st)>{{ $st }}</option>
                @endforeach
            </select>
BLADE,
        'form' => <<<'BLADE'
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
BLADE,
    ],
    'reviews' => [
        'title' => 'Avis',
        'singular' => 'avis',
        'route' => 'reviews',
        'columns' => [
            ['label' => 'Produit', 'key' => 'product.name'],
            ['label' => 'Note', 'key' => 'rating'],
            ['label' => 'Titre', 'key' => 'title'],
            ['label' => 'Statut', 'key' => 'status', 'badge' => true],
        ],
        'filters' => <<<'BLADE'
            <select name="status" class="tv-input">
                <option value="">Tous</option>
                @foreach(['pending','approved','rejected'] as $st)
                    <option value="{{ $st }}" @selected(request('status')===$st)>{{ $st }}</option>
                @endforeach
            </select>
BLADE,
        'form' => <<<'BLADE'
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
                    <x-input-label for="user_id" value="Auteur" />
                    <select name="user_id" id="user_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id', $item->user_id ?? '') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Note (1-5)" name="rating" type="number" min="1" max="5" :value="old('rating', $item->rating ?? 5)" required />
                <x-form.field label="Titre" name="title" :value="old('title', $item->title ?? '')" />
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1" required>
                        @foreach(['pending','approved','rejected'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $item->status ?? 'pending') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" name="verified_purchase" id="verified_purchase" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" @checked(old('verified_purchase', $item->verified_purchase ?? false))>
                    <label for="verified_purchase" class="text-sm text-slate-700">Achat vérifié</label>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="comment" value="Commentaire" />
                    <textarea name="comment" id="comment" rows="4" class="tv-input mt-1">{{ old('comment', $item->comment ?? '') }}</textarea>
                </div>
            </div>
BLADE,
    ],
    'alerts' => [
        'title' => 'Alertes',
        'singular' => 'alerte',
        'route' => 'alerts',
        'columns' => [
            ['label' => 'Titre', 'key' => 'title'],
            ['label' => 'Type', 'key' => 'alert_type'],
            ['label' => 'Sévérité', 'key' => 'severity', 'badge' => true],
            ['label' => 'Statut', 'key' => 'status', 'badge' => true],
        ],
        'filters' => <<<'BLADE'
            <select name="severity" class="tv-input">
                <option value="">Sévérité</option>
                @foreach(['low','medium','high','critical'] as $st)
                    <option value="{{ $st }}" @selected(request('severity')===$st)>{{ $st }}</option>
                @endforeach
            </select>
            <select name="status" class="tv-input">
                <option value="">Statut</option>
                @foreach(['open','investigating','resolved','dismissed'] as $st)
                    <option value="{{ $st }}" @selected(request('status')===$st)>{{ $st }}</option>
                @endforeach
            </select>
BLADE,
        'form' => <<<'BLADE'
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="product_id" value="Produit" />
                    <select name="product_id" id="product_id" class="tv-input mt-1" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id', $item->product_id ?? '') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Type" name="alert_type" :value="old('alert_type', $item->alert_type ?? '')" required />
                <x-form.field label="Titre" name="title" :value="old('title', $item->title ?? '')" required />
                <div>
                    <x-input-label for="severity" value="Sévérité" />
                    <select name="severity" id="severity" class="tv-input mt-1" required>
                        @foreach(['low','medium','high','critical'] as $st)
                            <option value="{{ $st }}" @selected(old('severity', $item->severity ?? 'medium') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1" required>
                        @foreach(['open','investigating','resolved','dismissed'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $item->status ?? 'open') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Détectée le" name="detected_at" type="datetime-local" :value="old('detected_at', isset($item) && $item->detected_at ? $item->detected_at->format('Y-m-d\\TH:i') : '')" />
                <div class="md:col-span-2">
                    <x-input-label for="description" value="Description" />
                    <textarea name="description" id="description" rows="3" class="tv-input mt-1">{{ old('description', $item->description ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="resolution" value="Résolution" />
                    <textarea name="resolution" id="resolution" rows="2" class="tv-input mt-1">{{ old('resolution', $item->resolution ?? '') }}</textarea>
                </div>
            </div>
BLADE,
    ],
    'ai-analyses' => [
        'title' => 'Analyses IA',
        'singular' => 'analyse',
        'route' => 'ai-analyses',
        'param' => 'ai_analysis',
        'columns' => [
            ['label' => 'Produit', 'key' => 'product.name'],
            ['label' => 'Type', 'key' => 'analysis_type'],
            ['label' => 'Score greenwashing', 'key' => 'greenwashing_score'],
            ['label' => 'Démo', 'key' => 'is_demo'],
        ],
        'form' => <<<'BLADE'
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="product_id" value="Produit" />
                    <select name="product_id" id="product_id" class="tv-input mt-1" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id', $item->product_id ?? '') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Type d'analyse" name="analysis_type" :value="old('analysis_type', $item->analysis_type ?? '')" />
                <x-form.field label="Score greenwashing (0-100)" name="greenwashing_score" type="number" min="0" max="100" :value="old('greenwashing_score', $item->greenwashing_score ?? '')" />
                <x-form.field label="Crédibilité" name="credibility_rating" :value="old('credibility_rating', $item->credibility_rating ?? '')" />
                <x-form.field label="Modèle utilisé" name="model_used" :value="old('model_used', $item->model_used ?? 'demo-rules-v1')" />
                <div class="flex items-center gap-2 pt-6">
                    <input type="hidden" name="is_demo" value="0">
                    <input type="checkbox" name="is_demo" id="is_demo" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" @checked(old('is_demo', $item->is_demo ?? true))>
                    <label for="is_demo" class="text-sm text-slate-700">Analyse de démonstration (pas un vrai service IA)</label>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="ai_summary" value="Résumé" />
                    <textarea name="ai_summary" id="ai_summary" rows="4" class="tv-input mt-1">{{ old('ai_summary', $item->ai_summary ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-amber-700">Un score élevé de greenwashing n'est pas une preuve définitive de fraude.</p>
                </div>
            </div>
BLADE,
    ],
    'consumers' => [
        'title' => 'Consommateurs',
        'singular' => 'consommateur',
        'route' => 'consumers',
        'columns' => [
            ['label' => 'Utilisateur', 'key' => 'user.name'],
            ['label' => 'Niveau', 'key' => 'sustainability_level'],
            ['label' => 'Budget', 'key' => 'budget_range'],
            ['label' => 'Évaluations', 'key' => 'personal_ratings_count'],
        ],
        'form' => <<<'BLADE'
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="user_id" value="Utilisateur" />
                    <select name="user_id" id="user_id" class="tv-input mt-1" required>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id', $item->user_id ?? '') == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Niveau durabilité" name="sustainability_level" :value="old('sustainability_level', $item->sustainability_level ?? '')" />
                <x-form.field label="Budget" name="budget_range" :value="old('budget_range', $item->budget_range ?? '')" />
                <x-form.field label="Préférences (virgules)" name="preferences" :value="old('preferences', isset($item) && is_array($item->preferences) ? implode(', ', $item->preferences) : '')" />
                <x-form.field label="Restrictions alimentaires (virgules)" name="dietary_restrictions" :value="old('dietary_restrictions', isset($item) && is_array($item->dietary_restrictions) ? implode(', ', $item->dietary_restrictions) : '')" />
                <x-form.field label="Allergies (virgules)" name="allergies" :value="old('allergies', isset($item) && is_array($item->allergies) ? implode(', ', $item->allergies) : '')" />
            </div>
BLADE,
    ],
    'personal-ratings' => [
        'title' => 'Évaluations personnalisées',
        'singular' => 'évaluation',
        'route' => 'personal-ratings',
        'param' => 'personal_rating',
        'columns' => [
            ['label' => 'Consommateur', 'key' => 'consumer.user.name'],
            ['label' => 'Produit', 'key' => 'product.name'],
            ['label' => 'Score', 'key' => 'personalized_score'],
            ['label' => 'Raison', 'key' => 'reason'],
        ],
        'form' => <<<'BLADE'
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="consumer_id" value="Consommateur" />
                    <select name="consumer_id" id="consumer_id" class="tv-input mt-1" required>
                        @foreach($consumers as $c)
                            <option value="{{ $c->id }}" @selected(old('consumer_id', $item->consumer_id ?? '') == $c->id)>{{ $c->user?->name ?? ('#'.$c->id) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="product_id" value="Produit" />
                    <select name="product_id" id="product_id" class="tv-input mt-1" required>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id', $item->product_id ?? '') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Score personnalisé (0-100)" name="personalized_score" type="number" min="0" max="100" :value="old('personalized_score', $item->personalized_score ?? '')" />
                <div class="md:col-span-2">
                    <x-input-label for="reason" value="Raison" />
                    <textarea name="reason" id="reason" rows="2" class="tv-input mt-1">{{ old('reason', $item->reason ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="recommendation_reason" value="Raison de recommandation" />
                    <textarea name="recommendation_reason" id="recommendation_reason" rows="2" class="tv-input mt-1">{{ old('recommendation_reason', $item->recommendation_reason ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Ce score n'est pas un diagnostic de santé.</p>
                </div>
            </div>
BLADE,
    ],
];

// Merge products into generic for generation
$all = array_merge([
    'products' => $entities['products'],
], $generic);

foreach ($all as $folder => $cfg) {
    $route = $cfg['route'];
    $title = $cfg['title'];
    $singular = $cfg['singular'];
    $form = $cfg['form'];
    $filters = $cfg['filters'] ?? '';
    $showExtra = $cfg['show_extra'] ?? '';

    // Build table headers/cells
    $ths = '';
    $tds = '';
    foreach ($cfg['columns'] as $col) {
        $ths .= "<th>{$col['label']}</th>";
        $key = $col['key'];
        if (str_contains($key, '.')) {
            [$rel, $attr] = explode('.', $key, 2);
            $expr = "\$item->{$rel}?->{$attr} ?? '—'";
        } else {
            $expr = "\$item->{$key} ?? '—'";
        }
        if (! empty($col['badge'])) {
            $tds .= "<td><x-status-badge :status=\"{$expr}\" /></td>";
        } else {
            $tds .= "<td>{{ {$expr} }}</td>";
        }
    }

    $index = <<<BLADE
@extends('layouts.backend')

@section('title', '{$title}')
@section('page-title', '{$title}')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher…" class="tv-input w-56">
            {$filters}
            <button class="tv-btn-secondary" type="submit">Filtrer</button>
        </form>
        <a href="{{ route('admin.{$route}.create') }}" class="tv-btn-primary">Ajouter</a>
    </div>

    <x-flash-messages />

    <div class="tv-card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="tv-table">
                <thead>
                    <tr>
                        {$ths}
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\$items as \$item)
                        <tr>
                            {$tds}
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.{$route}.show', \$item) }}" class="tv-link">Voir</a>
                                <a href="{{ route('admin.{$route}.edit', \$item) }}" class="tv-link ml-2">Modifier</a>
                                <form action="{{ route('admin.{$route}.destroy', \$item) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Confirmer la suppression ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count(\$cfg['columns']) + 1 }}">
                                <x-empty-state title="Aucun {$singular}" message="Commencez par créer une entrée." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(\$items->hasPages())
            <div class="p-4 border-t border-slate-100">{{ \$items->links() }}</div>
        @endif
    </div>
@endsection
BLADE;

    // Fix empty colspan - the generator has a bug with \$cfg in the string. Fix it:
    $colspan = count($cfg['columns']) + 1;
    $index = str_replace('{{ count(\$cfg[\'columns\']) + 1 }}', (string) $colspan, $index);

    $create = <<<BLADE
@extends('layouts.backend')

@section('title', 'Ajouter {$singular}')
@section('page-title', 'Ajouter un {$singular}')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.{$route}.store') }}" class="space-y-6">
            @csrf
            {$form}
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Enregistrer</button>
                <a href="{{ route('admin.{$route}.index') }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
BLADE;

    // For create, \$item is undefined - forms use \$item->x ?? ''. Need to set \$item = null or use optional.
    // Controllers don't pass \$item on create. Fix forms to work: they already use \$item->x ?? '' which errors if \$item undefined.
    // I'll pass @php \$item = \$item ?? null; or use nullsafe. Better: prepend in create/edit.

    $create = str_replace(
        '@section(\'content\')',
        "@section('content')\n    @php(\$item = \$item ?? null)",
        $create
    );

    $edit = <<<BLADE
@extends('layouts.backend')

@section('title', 'Modifier {$singular}')
@section('page-title', 'Modifier {$singular}')

@section('content')
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.{$route}.update', \$item) }}" class="space-y-6">
            @csrf
            @method('PUT')
            {$form}
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.{$route}.show', \$item) }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
BLADE;

    $showFields = '';
    foreach ($cfg['columns'] as $col) {
        $key = $col['key'];
        if (str_contains($key, '.')) {
            [$rel, $attr] = explode('.', $key, 2);
            $expr = "\$item->{$rel}?->{$attr} ?? '—'";
        } else {
            $expr = "\$item->{$key} ?? '—'";
        }
        $showFields .= "<div><dt>{$col['label']}</dt><dd>{{ {$expr} }}</dd></div>\n";
    }

    $show = <<<BLADE
@extends('layouts.backend')

@section('title', 'Détail {$singular}')
@section('page-title', 'Détail {$singular}')

@section('content')
    <x-flash-messages />
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.{$route}.edit', \$item) }}" class="tv-btn-primary">Modifier</a>
        <a href="{{ route('admin.{$route}.index') }}" class="tv-btn-secondary">Retour</a>
        <form action="{{ route('admin.{$route}.destroy', \$item) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
            @csrf @method('DELETE')
            <button class="tv-btn-danger" type="submit">Supprimer</button>
        </form>
    </div>

    <div class="tv-card">
        <dl class="tv-dl">
            {$showFields}
        </dl>
    </div>
    {$showExtra}
@endsection
BLADE;

    w("$base/$folder/index.blade.php", $index);
    w("$base/$folder/create.blade.php", $create);
    w("$base/$folder/edit.blade.php", $edit);
    w("$base/$folder/show.blade.php", $show);
}

echo "Admin views generated.\n";
