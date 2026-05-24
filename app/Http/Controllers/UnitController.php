<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    public function index()
    {
        return view('units.index');
    }
    
    public function getUnitsData(Request $request)
    {
        $units = Unit::withCount('users');

        return DataTables::of($units)
            ->addIndexColumn()
            ->addColumn('members_count', function ($unit) {
                $count = $unit->users_count ?? 0;
                if ($count > 0) {
                    return '<a href="' . route('users.index') . '?unit_id=' . $unit->id . '" class="cursor-pointer px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs font-medium hover:bg-indigo-200 transition-colors">' . $count . ' member' . ($count != 1 ? 's' : '') . '</a>';
                }
                return '<span class="px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs font-medium">' . $count . ' member' . ($count != 1 ? 's' : '') . '</span>';
            })
            ->addColumn('actions', function ($unit) {
                return '<div class="actions-menu">
                    <label class="hamburger">
                        <input type="checkbox" onchange="toggleActionsMenu(this)">
                        <svg viewBox="0 0 32 32">
                            <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                            <path class="line" d="M7 16 27 16"></path>
                        </svg>
                    </label>
                    <div class="actions-dropdown">
                        <a href="' . route('units.edit', $unit) . '">
                            <i class="fas fa-edit text-yellow-500"></i><span>Edit</span>
                        </a>
                        <button onclick="deleteUnit(' . $unit->id . ')">
                            <i class="fas fa-trash text-red-500"></i><span>Delete</span>
                        </button>
                    </div>
                </div>';
            })
            ->rawColumns(['members_count', 'actions'])
            ->make(true);
    }

    public function create()
    {
        return view('units.create');
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $unit = Unit::create([
            'name' => $validated['name'],
            'code' => $this->generateCode($validated['name']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Unit created successfully.',
            'unit' => [
                'id' => $unit->id,
                'name' => $unit->name,
            ],
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Unit updated successfully.'
        ]);
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return response()->json([
            'success' => true,
            'message' => 'Unit deleted successfully.'
        ]);
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
