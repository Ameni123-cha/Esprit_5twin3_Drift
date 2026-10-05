<?php

namespace App\Http\Controllers;

use App\Models\Producer;
use Illuminate\View\View;

class ProducerController extends Controller
{
    public function index(): View
    {
        $producers = Producer::query()
            ->whereHas('products', function ($query) {
                $query->where('status', 'published');
            })
            ->withCount(['products' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('company_name')
            ->paginate(12);

        return view('frontend.producers.index', compact('producers'));
    }

    public function show(Producer $producer): View
    {
        $producer->load(['products' => fn ($q) => $q->where('status', 'published')->with('environmentalFootprint')]);

        return view('frontend.producers.show', compact('producer'));
    }
}
