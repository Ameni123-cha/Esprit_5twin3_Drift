<?php

namespace App\Http\Controllers;

use App\Models\Transformer;
use Illuminate\View\View;

class TransformerController extends Controller
{
    public function index(): View
    {
        $transformers = Transformer::withCount(['products' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('company_name')
            ->paginate(12);

        return view('frontend.transformers.index', compact('transformers'));
    }

    public function show(Transformer $transformer): View
    {
        $transformer->load(['products' => fn ($q) => $q->where('status', 'published')]);

        return view('frontend.transformers.show', compact('transformer'));
    }
}
