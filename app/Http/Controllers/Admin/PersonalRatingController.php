<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consumer;
use App\Models\PersonalRating;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PersonalRatingController extends Controller
{
    public function index(Request $request): View
    {
        $items = PersonalRating::with(['consumer.user', 'product'])
            ->when($request->q, function ($q) use ($request) {
                $q->whereHas('product', fn ($p) => $p->where('name', 'like', '%'.$request->q.'%'));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.personal-ratings.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.personal-ratings.create', [
            'consumers' => Consumer::with('user')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = PersonalRating::create($this->validated($request));

        return redirect()->route('admin.personal-ratings.show', $item)
            ->with('success', 'Évaluation personnalisée créée.');
    }

    public function show(PersonalRating $personal_rating): View
    {
        $personal_rating->load(['consumer.user', 'product']);

        return view('admin.personal-ratings.show', ['item' => $personal_rating]);
    }

    public function edit(PersonalRating $personal_rating): View
    {
        return view('admin.personal-ratings.edit', [
            'item' => $personal_rating,
            'consumers' => Consumer::with('user')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, PersonalRating $personal_rating): RedirectResponse
    {
        $personal_rating->update($this->validated($request, $personal_rating));

        return redirect()->route('admin.personal-ratings.show', $personal_rating)
            ->with('success', 'Évaluation personnalisée mise à jour.');
    }

    public function destroy(PersonalRating $personal_rating): RedirectResponse
    {
        $personal_rating->delete();

        return redirect()->route('admin.personal-ratings.index')
            ->with('success', 'Évaluation personnalisée supprimée.');
    }

    private function validated(Request $request, ?PersonalRating $item = null): array
    {
        return $request->validate([
            'consumer_id' => ['required', 'exists:consumers,id'],
            'product_id' => [
                'required',
                'exists:products,id',
                Rule::unique('personal_ratings', 'product_id')
                    ->where(fn ($q) => $q->where('consumer_id', $request->consumer_id))
                    ->ignore($item),
            ],
            'personalized_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'reason' => ['nullable', 'string'],
            'recommendation_reason' => ['nullable', 'string'],
        ]);
    }
}
