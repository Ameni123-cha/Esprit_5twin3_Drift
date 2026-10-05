<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function index(Request $request): View
    {
        $items = Certificate::with('product')
            ->when($request->certificate_type, fn ($q) => $q->where('certificate_type', $request->certificate_type))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->q, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('issuer', 'like', '%'.$request->q.'%')
                        ->orWhere('certificate_number', 'like', '%'.$request->q.'%')
                        ->orWhereHas('product', fn ($p) => $p->where('name', 'like', '%'.$request->q.'%'));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.certificates.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.certificates.create', [
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Certificate::create($this->validated($request));

        return redirect()->route('admin.certificates.show', $item)
            ->with('success', 'Certification enregistrée (statut non vérifié automatiquement).');
    }

    public function show(Certificate $certificate): View
    {
        $certificate->load('product');

        return view('admin.certificates.show', ['item' => $certificate]);
    }

    public function edit(Certificate $certificate): View
    {
        return view('admin.certificates.edit', [
            'item' => $certificate,
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $certificate->update($this->validated($request));

        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Certification mise à jour.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certification supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'certificate_type' => ['required', 'string', 'max:100'],
            'issuer' => ['nullable', 'string', 'max:255'],
            'certificate_number' => ['nullable', 'string', 'max:100'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'status' => ['required', Rule::in(['pending', 'verified', 'expired', 'rejected'])],
        ]);
    }
}
