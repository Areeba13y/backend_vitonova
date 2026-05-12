<?php

namespace App\Http\Controllers;

use App\Models\Collaboration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CollaborationController extends Controller
{
    public function list()
    {
        $collaborations = Collaboration::query()
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success' => true,
            'collaborations' => $collaborations->map(fn (Collaboration $collaboration) => $this->payload($collaboration))->values(),
        ]);
    }

    public function index()
    {
        $collaborations = Collaboration::latest()->paginate(10);
        return view('collaborations.index', compact('collaborations'));
    }
    
    public function getCollaborationsData(Request $request)
    {
        $collaborations = Collaboration::select('collaborations.*');

        return DataTables::of($collaborations)
            ->addIndexColumn()
            ->addColumn('logo', function ($item) {
                return $item->logo ? asset($item->logo) : null;
            })
            ->addColumn('representative', function ($item) {
                $rep = trim(($item->representative_designation ?: '') . ' ' . ($item->representative_name ?: ''));
                return $rep ?: '-';
            })
            ->addColumn('is_active', function ($item) {
                return $item->is_active;
            })
            ->addColumn('actions', function ($item) {
                return '<div class="actions-menu">
                    <label class="hamburger">
                        <input type="checkbox" onchange="toggleActionsMenu(this)">
                        <svg viewBox="0 0 32 32">
                            <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                            <path class="line" d="M7 16 27 16"></path>
                        </svg>
                    </label>
                    <div class="actions-dropdown">
                        <a href="' . route('collaborations.edit', $item) . '">
                            <i class="fas fa-edit text-yellow-500"></i> Edit
                        </a>
                        <button onclick="deleteCollaboration(' . $item->id . ', \'' . addslashes($item->organization_name) . '\')">
                            <i class="fas fa-trash text-red-500"></i> Delete
                        </button>
                    </div>
                </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('collaborations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'organization_name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'representative_designation' => 'nullable|string|max:255',
            'representative_name' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['logo'] = $this->storeImage($request->file('logo'));
        $validated['is_active'] = $request->boolean('is_active', true);

        $collaboration = Collaboration::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Collaboration created successfully.',
            'collaboration' => $this->payload($collaboration->fresh()),
        ]);
    }

    public function show(Collaboration $collaboration)
    {
        return view('collaborations.show', compact('collaboration'));
    }

    public function edit(Collaboration $collaboration)
    {
        return view('collaborations.edit', compact('collaboration'));
    }

    public function update(Request $request, Collaboration $collaboration)
    {
        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'organization_name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'representative_designation' => 'nullable|string|max:255',
            'representative_name' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $this->deleteImageIfExists($collaboration->logo);
            $validated['logo'] = $this->storeImage($request->file('logo'));
        }

        $validated['is_active'] = $request->boolean('is_active', false);

        $collaboration->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Collaboration updated successfully.',
            'collaboration' => $this->payload($collaboration->fresh()),
        ]);
    }

    public function destroy(Collaboration $collaboration)
    {
        $id = $collaboration->id;
        $this->deleteImageIfExists($collaboration->logo);
        $collaboration->delete();

        return response()->json([
            'success' => true,
            'message' => 'Collaboration deleted successfully.',
            'collaboration_id' => $id,
        ]);
    }

    public function toggleActive(Collaboration $collaboration)
    {
        $collaboration->update([
            'is_active' => ! $collaboration->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'collaboration' => $this->payload($collaboration->fresh()),
        ]);
    }

    private function storeImage($image): string
    {
        $destination = public_path('uploads/collaborations');

        if (! File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
        $image->move($destination, $filename);

        return 'uploads/collaborations/' . $filename;
    }

    private function deleteImageIfExists(?string $relativePath): void
    {
        if (! $relativePath || ! Str::startsWith($relativePath, 'uploads/collaborations/')) {
            return;
        }

        $fullPath = public_path($relativePath);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function payload(Collaboration $collaboration): array
    {
        return [
            'id' => $collaboration->id,
            'logo' => $collaboration->logo,
            'logo_url' => asset($collaboration->logo),
            'organization_name' => $collaboration->organization_name,
            'subtitle' => $collaboration->subtitle,
            'description' => $collaboration->description,
            'representative_designation' => $collaboration->representative_designation,
            'representative_name' => $collaboration->representative_name,
            'is_active' => $collaboration->is_active,
            'created_at' => $collaboration->created_at?->format('Y-m-d H:i'),
            'updated_at' => $collaboration->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
