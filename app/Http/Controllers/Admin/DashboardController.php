<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Certificate;
use App\Models\Consumer;
use App\Models\Distributor;
use App\Models\PersonalRating;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Review;
use App\Models\Transformer;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'products' => Product::count(),
            'producers' => Producer::count(),
            'transformers' => Transformer::count(),
            'distributors' => Distributor::count(),
            'certificates' => Certificate::count(),
            'alerts' => Alert::count(),
            'open_alerts' => Alert::whereIn('status', ['open', 'investigating'])->count(),
            'reviews' => Review::count(),
            'consumers' => Consumer::count(),
            'ratings' => PersonalRating::count(),
            'published_products' => Product::where('status', 'published')->count(),
        ];

        $recentProducts = Product::with(['producer', 'environmentalFootprint'])
            ->latest()
            ->take(5)
            ->get();

        $recentAlerts = Alert::with('product')
            ->latest('detected_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'recentAlerts'));
    }
}
