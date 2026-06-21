<?php

namespace App\Http\Controllers;

use App\Models\PreviousHighlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PreviousHighlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('previous-highlights.index');
    }

    /**
     * Get highlights data for DataTables.
     */
    public function getHighlightsData(Request $request)
    {
        try {
            $highlights = PreviousHighlight::select('previous_highlights.*');

            return DataTables::of($highlights)
                ->addIndexColumn()
                ->addColumn('image', function ($highlight) {
                    if ($highlight->image) {
                        return '<img src="' . asset($highlight->image) . '" alt="' . $highlight->title . '" class="w-12 h-12 object-cover rounded">';
                    }
                    return '<div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center"><i class="fas fa-image text-gray-400"></i></div>';
                })
                ->addColumn('url', function ($highlight) {
                    if ($highlight->url) {
                        return '<a href="' . $highlight->url . '" target="_blank" class="text-blue-500 hover:text-blue-700"><i class="fab fa-linkedin"></i> View</a>';
                    }
                    return '<span class="text-gray-400">-</span>';
                })
                ->addColumn('actions', function ($highlight) {
                    return '<div class="actions-menu">
                        <label class="hamburger">
                            <input type="checkbox" onchange="toggleActionsMenu(this)">
                            <svg viewBox="0 0 32 32">
                                <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.8 22 22 22L7 22"></path>
                                <path class="line" d="M7 16 27 16"></path>
                            </svg>
                        </label>
                        <div class="actions-dropdown">
                            <a href="' . route('previous-highlights.edit', $highlight->id) . '">
                                <i class="fas fa-edit text-yellow-500"></i><span>Edit</span>
                            </a>
                            <button onclick="deleteHighlight(' . $highlight->id . ', \'' . addslashes($highlight->title) . '\')">
                                <i class="fas fa-trash text-red-500"></i><span>Delete</span>
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['image', 'url', 'actions'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('DataTable Error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('previous-highlights.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|url|max:500',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['image'] = $this->storeImage($request->file('image'));

        $highlight = PreviousHighlight::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Highlight created successfully.',
            'highlight' => $this->highlightPayload($highlight->fresh()),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PreviousHighlight $previousHighlight)
    {
        return view('previous-highlights.show', compact('previousHighlight'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PreviousHighlight $previousHighlight)
    {
        return view('previous-highlights.edit', compact('previousHighlight'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PreviousHighlight $previousHighlight)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImageIfExists($previousHighlight->image);
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        $previousHighlight->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Highlight updated successfully.',
            'highlight' => $this->highlightPayload($previousHighlight->fresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PreviousHighlight $previousHighlight)
    {
        $highlightId = $previousHighlight->id;
        
        $this->deleteImageIfExists($previousHighlight->image);
        $previousHighlight->delete();

        return response()->json([
            'success' => true,
            'message' => 'Highlight deleted successfully.',
            'highlight_id' => $highlightId,
        ]);
    }

    /**
     * Store image in uploads directory.
     */
    private function storeImage($image): string
    {
        $destination = public_path('uploads/highlights');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
        $image->move($destination, $filename);

        return 'uploads/highlights/' . $filename;
    }

    /**
     * Delete image file if exists.
     */
    private function deleteImageIfExists(?string $relativePath): void
    {
        if (!$relativePath || !Str::startsWith($relativePath, 'uploads/highlights/')) {
            return;
        }

        $fullPath = public_path($relativePath);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
            Log::info('Deleted highlight image: ' . $relativePath);
        }
    }

    /**
     * Format highlight data for API response.
     */
    private function highlightPayload(PreviousHighlight $highlight): array
    {
        return [
            'id' => $highlight->id,
            'title' => $highlight->title,
            'url' => $highlight->url,
            'image' => $highlight->image,
            'image_url' => asset($highlight->image),
            'created_at' => $highlight->created_at?->format('Y-m-d H:i'),
            'updated_at' => $highlight->updated_at?->format('Y-m-d H:i'),
        ];
    }

    /**
     * Get highlights for frontend display (API endpoint).
     */
    public function getHighlights()
    {
        try {
            $highlights = PreviousHighlight::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Highlights fetched successfully.',
                'highlights' => $highlights->map(fn ($highlight) => $this->highlightPayload($highlight))->values(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching highlights: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching highlights: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single highlight by ID (API endpoint).
     */
    public function getHighlight($id)
    {
        try {
            $highlight = PreviousHighlight::find($id);
            
            if (!$highlight) {
                return response()->json([
                    'success' => false,
                    'message' => 'Highlight not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Highlight fetched successfully.',
                'highlight' => $this->highlightPayload($highlight),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}