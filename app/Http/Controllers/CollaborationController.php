<?php

namespace App\Http\Controllers;

use App\Models\Collaboration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

    public function create()
    {
        return redirect()->route('collaborations.index');
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
        return response()->json([
            'success' => true,
            'collaboration' => $this->payload($collaboration),
        ]);
    }

    public function edit(Collaboration $collaboration)
    {
        return response()->json([
            'success' => true,
            'collaboration' => $this->payload($collaboration),
        ]);
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
