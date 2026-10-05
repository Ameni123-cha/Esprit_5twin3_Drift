<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $items = Review::with(['product', 'user'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->rating, fn ($q) => $q->where('rating', $request->rating))
            ->when($request->q, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%'.$request->q.'%')
                        ->orWhereHas('product', fn ($p) => $p->where('name', 'like', '%'.$request->q.'%'));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.reviews.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.reviews.create', [
            'products' => Product::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Review::create($this->validated($request));

        return redirect()->route('admin.reviews.show', $item)
            ->with('success', 'Avis créé.');
    }

    public function show(Review $review): View
    {
        $review->load(['product', 'user']);

        return view('admin.reviews.show', ['item' => $review]);
    }

    public function edit(Review $review): View
    {
        return view('admin.reviews.edit', [
            'item' => $review,
            'products' => Product::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $review->update($this->validated($request));

        return redirect()->route('admin.reviews.show', $review)
            ->with('success', 'Avis mis à jour.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Avis supprimé.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string'],
            'verified_purchase' => ['sometimes', 'boolean'],
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
        ]) + [
            'verified_purchase' => $request->boolean('verified_purchase'),
        ];
    }
}
