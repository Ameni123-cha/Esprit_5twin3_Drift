<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\PersonalRating;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsumerController extends Controller
{
    public function profile(Request $request): View
    {
        $consumer = $this->resolveConsumer($request, true);
        $consumer->load(['personalRatings.product.environmentalFootprint']);

        return view('frontend.consumer.profile', compact('consumer'));
    }

    public function edit(Request $request): View
    {
        $consumer = $this->resolveConsumer($request, true);

        return view('frontend.consumer.edit', compact('consumer'));
    }

    public function update(Request $request): RedirectResponse
    {
        $consumer = $this->resolveConsumer($request, true);

        $data = $request->validate([
            'preferences' => ['nullable', 'string'],
            'sustainability_level' => ['nullable', 'string', 'max:50'],
            'dietary_restrictions' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'budget_range' => ['nullable', 'string', 'max:50'],
        ]);

        foreach (['preferences', 'dietary_restrictions', 'allergies'] as $field) {
            $value = $data[$field] ?? null;
            $data[$field] = ($value === null || trim($value) === '')
                ? null
                : array_values(array_filter(array_map('trim', explode(',', $value))));
        }

        $consumer->update($data);

        return redirect()->route('consumer.profile')
            ->with('success', 'Préférences mises à jour.');
    }

    public function recommendations(Request $request): View
    {
        $consumer = $this->resolveConsumer($request, true);
        $preferences = collect($consumer->preferences ?? []);

        $ratings = PersonalRating::with(['product.environmentalFootprint', 'product.producer'])
            ->where('consumer_id', $consumer->id)
            ->orderByDesc('personalized_score')
            ->get();

        $suggested = Product::with('environmentalFootprint')
            ->where('status', 'published')
            ->when($preferences->isNotEmpty(), function ($q) use ($preferences) {
                $q->where(function ($query) use ($preferences) {
                    if ($preferences->contains('bio')) {
                        $query->orWhereHas('certificates', fn ($c) => $c->where('certificate_type', 'like', '%Bio%'));
                    }
                    if ($preferences->contains('faible_co2')) {
                        $query->orWhereHas('environmentalFootprint', fn ($f) => $f->where('co2_emissions', '<', 5));
                    }
                    if ($preferences->contains('local')) {
                        $query->orWhere('origin', 'like', '%France%');
                    }
                });
            })
            ->take(8)
            ->get();

        return view('frontend.consumer.recommendations', compact('consumer', 'ratings', 'suggested'));
    }

    private function resolveConsumer(Request $request, bool $create = false): Consumer
    {
        $user = $request->user();
        abort_unless($user, 403);

        $consumer = $user->consumer;

        if (! $consumer && $create) {
            $consumer = Consumer::create([
                'user_id' => $user->id,
                'preferences' => [],
                'sustainability_level' => 'débutant',
            ]);
        }

        abort_unless($consumer && $consumer->user_id === $user->id, 403);

        return $consumer;
    }
}
