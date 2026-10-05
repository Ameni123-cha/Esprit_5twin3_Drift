<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DistributorController extends Controller
{
    public function index(Request $request): View
    {
        $items = Distributor::with('user')
            ->withCount('supplyChainTraces')
            ->when($request->q, fn ($q) => $q->where('company_name', 'like', '%'.$request->q.'%')
                ->orWhere('location', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.distributors.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.distributors.create', [
            'users' => User::where('role', 'distributor')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Distributor::create($this->validated($request));

        return redirect()->route('admin.distributors.show', $item)
            ->with('success', 'Distributeur créé.');
    }

    public function show(Distributor $distributor): View
    {
        $distributor->load(['user', 'supplyChainTraces.product']);

        return view('admin.distributors.show', ['item' => $distributor]);
    }

    public function edit(Distributor $distributor): View
    {
        return view('admin.distributors.edit', [
            'item' => $distributor,
            'users' => User::where('role', 'distributor')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Distributor $distributor): RedirectResponse
    {
        $distributor->update($this->validated($request));

        return redirect()->route('admin.distributors.show', $distributor)
            ->with('success', 'Distributeur mis à jour.');
    }

    public function destroy(Distributor $distributor): RedirectResponse
    {
        $distributor->delete();

        return redirect()->route('admin.distributors.index')
            ->with('success', 'Distributeur supprimé.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'company_name' => ['required', 'string', 'max:255'],
            'distributor_type' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'coverage_area' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
