<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Unit::create([
            'name' => $validated['name'],
            'code' => $this->generateCode($validated['name']),
        ]);

        return back()->with('success', 'Unit created successfully.');
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $unit->update([
            'name' => $validated['name'],
            'code' => $this->generateCode($validated['name'], $unit->id),
        ]);

        return back()->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit deleted successfully.');
    }

    private function generateCode(string $name, ?int $ignoreId = null): string
    {
        $base = (string) Str::of($name)
            ->lower()
            ->replaceMatches('/[^a-z0-9\s]/', '')
            ->replaceMatches('/\s+/', '_')
            ->trim('_');

        $code = $base;
        $counter = 1;

        while (
            Unit::where('code', $code)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $counter++;
            $code = $base . '_' . $counter;
        }

        return $code;
    }
}

