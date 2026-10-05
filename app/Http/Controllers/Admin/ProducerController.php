<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProducerController extends Controller
{
    public function index(Request $request): View
    {
        $items = Producer::with('user')
            ->withCount('products')
            ->when($request->q, fn ($q) => $q->where('company_name', 'like', '%'.$request->q.'%')
                ->orWhere('location', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.producers.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.producers.create', [
            'users' => User::where('role', 'producer')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Producer::create($this->validated($request));

        return redirect()->route('admin.producers.show', $item)
            ->with('success', 'Producteur créé.');
    }

    public function show(Producer $producer): View
    {
        $producer->load(['user', 'products.environmentalFootprint']);

        return view('admin.producers.show', ['item' => $producer]);
    }

    public function edit(Producer $producer): View
    {
        return view('admin.producers.edit', [
            'item' => $producer,
            'users' => User::where('role', 'producer')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Producer $producer): RedirectResponse
    {
        $producer->update($this->validated($request));

        return redirect()->route('admin.producers.show', $producer)
            ->with('success', 'Producteur mis à jour.');
    }

    public function destroy(Producer $producer): RedirectResponse
    {
        $producer->delete();

        return redirect()->route('admin.producers.index')
            ->with('success', 'Producteur supprimé.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'farming_method' => ['nullable', 'string', 'max:100'],
            'crop_types' => ['nullable', 'string'],
            'production_capacity' => ['nullable', 'string', 'max:100'],
            'certifications' => ['nullable', 'string'],
        ]);

        $data['crop_types'] = $this->csvToArray($data['crop_types'] ?? null);
        $data['certifications'] = $this->csvToArray($data['certifications'] ?? null);

        return $data;
    }

    private function csvToArray(?string $value): ?array
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}
