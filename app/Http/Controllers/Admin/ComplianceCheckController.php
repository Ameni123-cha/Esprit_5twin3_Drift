<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplianceCheck;
use App\Models\EnvironmentalClaim;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplianceCheckController extends Controller
{
    public function index(Request $request): View
    {
        $items = ComplianceCheck::with(['environmentalClaim', 'product'])
            ->when($request->q, function ($query, $term) {
                $query->where('check_type', 'like', '%'.$term.'%')
                    ->orWhere('status', 'like', '%'.$term.'%');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.compliance-checks.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.compliance-checks.create', [
            'environmentalClaims' => EnvironmentalClaim::orderBy('title')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = ComplianceCheck::create($this->validated($request));

        return redirect()->route('admin.compliance-checks.show', $item)
            ->with('success', 'Vérification de conformité créée.');
    }

    public function show(ComplianceCheck $compliance_check): View
    {
        $compliance_check->load(['environmentalClaim', 'product', 'alerts']);

        return view('admin.compliance-checks.show', ['item' => $compliance_check]);
    }

    public function edit(ComplianceCheck $compliance_check): View
    {
        return view('admin.compliance-checks.edit', [
            'item' => $compliance_check,
            'environmentalClaims' => EnvironmentalClaim::orderBy('title')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, ComplianceCheck $compliance_check): RedirectResponse
    {
        $compliance_check->update($this->validated($request));

        return redirect()->route('admin.compliance-checks.show', $compliance_check)
            ->with('success', 'Vérification de conformité mise à jour.');
    }

    public function destroy(ComplianceCheck $compliance_check): RedirectResponse
    {
        $compliance_check->delete();

        return redirect()->route('admin.compliance-checks.index')
            ->with('success', 'Vérification de conformité supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'environmental_claim_id' => ['required', 'exists:environmental_claims,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'check_type' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'result_summary' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'checked_at' => ['nullable', 'date'],
        ]);
    }
}
