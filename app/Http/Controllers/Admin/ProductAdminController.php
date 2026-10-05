<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Transformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductAdminController extends Controller
{
    public function index(Request $request): View
    {
        $items = Product::with(['producer', 'transformer', 'environmentalFootprint'])
            ->when($request->q, fn ($q) => $q->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('barcode', 'like', '%'.$request->q.'%')
                    ->orWhere('sku', 'like', '%'.$request->q.'%');
            }))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'producers' => Producer::orderBy('company_name')->get(),
            'transformers' => Transformer::orderBy('company_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $product = Product::create($data);

        return redirect()
            ->route('admin.products.show', $product)
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Product $product): View
    {
        $product->load([
            'producer', 'transformer', 'environmentalFootprint',
            'certificates', 'reviews.user', 'alerts', 'aiAnalyses',
            'supplyChainTraces.distributor',
        ]);

        return view('admin.products.show', ['item' => $product]);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'item' => $product,
            'producers' => Producer::orderBy('company_name')->get(),
            'transformers' => Transformer::orderBy('company_name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validated($request, $product));

        return redirect()
            ->route('admin.products.show', $product)
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produit supprimé.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:64', Rule::unique('products', 'barcode')->ignore($product)],
            'sku' => ['nullable', 'string', 'max:64', Rule::unique('products', 'sku')->ignore($product)],
            'category' => ['nullable', 'string', 'max:100'],
            'origin' => ['nullable', 'string', 'max:255'],
            'ingredients' => ['nullable', 'string'],
            'producer_id' => ['nullable', 'exists:producers,id'],
            'transformer_id' => ['nullable', 'exists:transformers,id'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
        ]);
    }
}
