<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consumer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ConsumerController extends Controller
{
    public function index(Request $request): View
    {
        $items = Consumer::with('user')
            ->withCount('personalRatings')
            ->when($request->q, function ($q) use ($request) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%'));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.consumers.index', compact('items'));
    }

    public function create(): View
    {
        $users = User::where('role', 'consumer')
            ->whereDoesntHave('consumer')
            ->orderBy('name')
            ->get();

        return view('admin.consumers.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Consumer::create($this->validated($request));

        return redirect()->route('admin.consumers.show', $item)
            ->with('success', 'Profil consommateur créé.');
    }

    public function show(Consumer $consumer): View
    {
        $consumer->load(['user', 'personalRatings.product']);

        return view('admin.consumers.show', ['item' => $consumer]);
    }

    public function edit(Consumer $consumer): View
    {
        $users = User::where('role', 'consumer')
            ->where(function ($q) use ($consumer) {
                $q->whereDoesntHave('consumer')->orWhere('id', $consumer->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.consumers.edit', [
            'item' => $consumer,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Consumer $consumer): RedirectResponse
    {
        $consumer->update($this->validated($request, $consumer));

        return redirect()->route('admin.consumers.show', $consumer)
            ->with('success', 'Profil consommateur mis à jour.');
    }

    public function destroy(Consumer $consumer): RedirectResponse
    {
        $consumer->delete();

        return redirect()->route('admin.consumers.index')
            ->with('success', 'Profil consommateur supprimé.');
    }

    private function validated(Request $request, ?Consumer $consumer = null): array
    {
        $data = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('consumers', 'user_id')->ignore($consumer),
            ],
            'preferences' => ['nullable', 'string'],
            'sustainability_level' => ['nullable', 'string', 'max:50'],
            'dietary_restrictions' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'budget_range' => ['nullable', 'string', 'max:50'],
        ]);

        foreach (['preferences', 'dietary_restrictions', 'allergies'] as $field) {
            $data[$field] = $this->csvToArray($data[$field] ?? null);
        }

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
