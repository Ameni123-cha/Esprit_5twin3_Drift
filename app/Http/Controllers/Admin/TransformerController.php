<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transformer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransformerController extends Controller
{
    public function index(Request $request): View
    {
        $items = Transformer::with('user')
            ->withCount('products')
            ->when($request->q, fn ($q) => $q->where('company_name', 'like', '%'.$request->q.'%')
                ->orWhere('location', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.transformers.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.transformers.create', [
            'users' => User::where('role', 'transformer')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Transformer::create($this->validated($request));

        return redirect()->route('admin.transformers.show', $item)
            ->with('success', 'Transformateur créé.');
    }

    public function show(Transformer $transformer): View
    {
        $transformer->load(['user', 'products']);

        return view('admin.transformers.show', ['item' => $transformer]);
    }

    public function edit(Transformer $transformer): View
    {
        return view('admin.transformers.edit', [
            'item' => $transformer,
            'users' => User::where('role', 'transformer')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Transformer $transformer): RedirectResponse
    {
        $transformer->update($this->validated($request));

        return redirect()->route('admin.transformers.show', $transformer)
            ->with('success', 'Transformateur mis à jour.');
    }

    public function destroy(Transformer $transformer): RedirectResponse
    {
        $transformer->delete();

        return redirect()->route('admin.transformers.index')
            ->with('success', 'Transformateur supprimé.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'transformation_type' => ['nullable', 'string', 'max:100'],
            'process_description' => ['nullable', 'string'],
            'production_capacity' => ['nullable', 'string', 'max:100'],
            'certifications' => ['nullable', 'string'],
        ]);

        if (isset($data['certifications'])) {
            $data['certifications'] = $data['certifications'] === ''
                ? null
                : array_values(array_filter(array_map('trim', explode(',', $data['certifications']))));
        }

        return $data;
    }
}
