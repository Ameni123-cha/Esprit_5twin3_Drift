<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\Product;
use App\Models\SupplyChainTrace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SupplyChainTraceController extends Controller
{
    public function index(Request $request): View
    {
        $items = SupplyChainTrace::with(['product', 'distributor'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->q, function ($q) use ($request) {
                $q->whereHas('product', fn ($p) => $p->where('name', 'like', '%'.$request->q.'%'));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.supply-chain-traces.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.supply-chain-traces.create', [
            'products' => Product::orderBy('name')->get(),
            'distributors' => Distributor::orderBy('company_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = SupplyChainTrace::create($this->validated($request));

        return redirect()->route('admin.supply-chain-traces.show', $item)
            ->with('success', 'Trace logistique créée.');
    }

    public function show(SupplyChainTrace $supply_chain_trace): View
    {
        $supply_chain_trace->load(['product', 'distributor']);

        return view('admin.supply-chain-traces.show', ['item' => $supply_chain_trace]);
    }

    public function edit(SupplyChainTrace $supply_chain_trace): View
    {
        return view('admin.supply-chain-traces.edit', [
            'item' => $supply_chain_trace,
            'products' => Product::orderBy('name')->get(),
            'distributors' => Distributor::orderBy('company_name')->get(),
        ]);
    }

    public function update(Request $request, SupplyChainTrace $supply_chain_trace): RedirectResponse
    {
        $supply_chain_trace->update($this->validated($request));

        return redirect()->route('admin.supply-chain-traces.show', $supply_chain_trace)
            ->with('success', 'Trace logistique mise à jour.');
    }

    public function destroy(SupplyChainTrace $supply_chain_trace): RedirectResponse
    {
        $supply_chain_trace->delete();

        return redirect()->route('admin.supply-chain-traces.index')
            ->with('success', 'Trace logistique supprimée.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'distributor_id' => ['nullable', 'exists:distributors,id'],
            'current_stage' => ['nullable', 'string', 'max:100'],
            'current_location_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'current_location_lon' => ['nullable', 'numeric', 'between:-180,180'],
            'path_history_json' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['in_transit', 'delivered', 'delayed', 'completed'])],
            'total_distance_km' => ['nullable', 'numeric', 'min:0'],
        ]);

        if (! empty($data['path_history_json'])) {
            $decoded = json_decode($data['path_history_json'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'path_history_json' => 'Le JSON de l’historique est invalide.',
                ]);
            }
            $data['path_history'] = $decoded;
        } else {
            $data['path_history'] = null;
        }

        unset($data['path_history_json']);

        return $data;
    }
}
