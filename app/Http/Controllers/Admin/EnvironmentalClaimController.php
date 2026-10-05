<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnvironmentalClaim;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnvironmentalClaimController extends Controller
{
    public function index(Request $request): View
    {
        $items = EnvironmentalClaim::with('product')
            ->when($request->q, function ($query, $term) {
                $query->where('title', 'like', '%'.$term.'%')
                    ->orWhere('claim_type', 'like', '%'.$term.'%');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.environmental-claims.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.environmental-claims.create', [
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = EnvironmentalClaim::create($this->validated($request));

        return redirect()->route('admin.environmental-claims.show', $item)
            ->with('success', 'Déclaration environnementale créée.');
    }

    public function show(EnvironmentalClaim $environmental_claim): View
    {
        $environmental_claim->load(['product', 'complianceChecks']);

        return view('admin.environmental-claims.show', ['item' => $environmental_claim]);
    }

    public function edit(EnvironmentalClaim $environmental_claim): View
    {
        return view('admin.environmental-claims.edit', [
            'item' => $environmental_claim,
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, EnvironmentalClaim $environmental_claim): RedirectResponse
    {
        $environmental_claim->update($this->validated($request));

        return redirect()->route('admin.environmental-claims.show', $environmental_claim)
            ->with('success', 'Déclaration environnementale mise à jour.');
    }

    public function destroy(EnvironmentalClaim $environmental_claim): RedirectResponse
    {
        $environmental_claim->delete();

        return redirect()->route('admin.environmental-claims.index')
            ->with('success', 'Déclaration environnementale supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'claim_type' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source_document' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:50'],
            'confidence_score' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);
    }
}
