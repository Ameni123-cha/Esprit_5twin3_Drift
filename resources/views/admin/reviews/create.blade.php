@extends('layouts.backend')

@section('title', 'Ajouter avis')
@section('page-title', 'Ajouter un avis')

@section('content')
    @php($item = $item ?? null)
    <div class="tv-card max-w-4xl">
        <form method="POST" action="{{ route('admin.reviews.store') }}" class="space-y-6">
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
                <div>
                    <x-input-label for="user_id" value="Auteur" />
                    <select name="user_id" id="user_id" class="tv-input mt-1">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id', $item?->user_id ?? '') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form.field label="Note (1-5)" name="rating" type="number" min="1" max="5" :value="old('rating', $item?->rating ?? 5)" required />
                <x-form.field label="Titre" name="title" :value="old('title', $item?->title ?? '')" />
                <div>
                    <x-input-label for="status" value="Statut" />
                    <select name="status" id="status" class="tv-input mt-1" required>
                        @foreach(['pending','approved','rejected'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $item?->status ?? 'pending') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" name="verified_purchase" id="verified_purchase" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" @checked(old('verified_purchase', $item?->verified_purchase ?? false))>
                    <label for="verified_purchase" class="text-sm text-slate-700">Achat vérifié</label>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="comment" value="Commentaire" />
                    <textarea name="comment" id="comment" rows="4" class="tv-input mt-1">{{ old('comment', $item?->comment ?? '') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="tv-btn-primary">Enregistrer</button>
                <a href="{{ route('admin.reviews.index') }}" class="tv-btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection