<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AIAnalysis;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AIAnalysisController extends Controller
{
    public function index(Request $request): View
    {
        $items = AIAnalysis::with('product')
            ->when($request->analysis_type, fn ($q) => $q->where('analysis_type', $request->analysis_type))
            ->when($request->q, function ($q) use ($request) {
                $q->whereHas('product', fn ($p) => $p->where('name', 'like', '%'.$request->q.'%'));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.ai-analyses.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.ai-analyses.create', [
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = AIAnalysis::create($this->validated($request));

        return redirect()->route('admin.ai-analyses.show', $item)
            ->with('success', 'Analyse enregistrée. Les analyses de démonstration ne constituent pas une preuve de fraude.');
    }

    public function show(AIAnalysis $ai_analysis): View
    {
        $ai_analysis->load('product');

        return view('admin.ai-analyses.show', ['item' => $ai_analysis]);
    }

    public function edit(AIAnalysis $ai_analysis): View
    {
        return view('admin.ai-analyses.edit', [
            'item' => $ai_analysis,
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, AIAnalysis $ai_analysis): RedirectResponse
    {
        $ai_analysis->update($this->validated($request));

        return redirect()->route('admin.ai-analyses.show', $ai_analysis)
            ->with('success', 'Analyse mise à jour.');
    }

    public function destroy(AIAnalysis $ai_analysis): RedirectResponse
    {
        $ai_analysis->delete();

        return redirect()->route('admin.ai-analyses.index')
            ->with('success', 'Analyse supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'analysis_type' => ['nullable', 'string', 'max:100'],
            'greenwashing_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'credibility_rating' => ['nullable', 'string', 'max:50'],
            'ai_summary' => ['nullable', 'string'],
            'model_used' => ['nullable', 'string', 'max:100'],
            'is_demo' => ['sometimes', 'boolean'],
        ]) + [
            'is_demo' => $request->boolean('is_demo', true),
        ];
    }
}
