<?php

namespace App\Http\Controllers;

use App\Mail\EventRegistrationReceivedApplicant;
use App\Models\Event;
use App\Models\User;
use App\Models\UserEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    public function upcoming()
    {
        $today = Carbon::today()->toDateString();

        $events = Event::query()
            ->whereDate('submission_deadline', '>=', $today)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Upcoming events fetched successfully.',
            'date' => $today,
            'events' => $events->map(fn (Event $event) => $this->eventPayload($event))->values(),
        ]);
    }

    public function index()
    {
        $events = Event::withCount('registrations')->latest()->paginate(10);
        return view('events.index', compact('events'));
    }
    
    public function getEventsData(Request $request)
    {
        $events = Event::select('events.*');

        return DataTables::of($events)
            ->addIndexColumn()
            ->addColumn('image', function ($event) {
                return $event->image ? asset($event->image) : null;
            })
            ->addColumn('submission_deadline', function ($event) {
                return $event->submission_deadline?->format('Y-m-d') ?? '-';
            })
            ->addColumn('event_date', function ($event) {
                return $event->event_date?->format('Y-m-d') ?? '-';
            })
            ->addColumn('registrations_count', function ($event) {
                $count = $event->registrations()->withTrashed()->count();
                return '<a href="' . url('/event-registrations/' . $event->id) . '" class="cursor-pointer px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs font-medium hover:bg-indigo-200 transition-colors">' . $count . ' registration' . ($count != 1 ? 's' : '') . '</a>';
            })
            ->addColumn('actions', function ($event) {
                return '<div class="actions-menu">
                    <label class="hamburger">
                        <input type="checkbox" onchange="toggleActionsMenu(this)">
                        <svg viewBox="0 0 32 32">
                            <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                            <path class="line" d="M7 16 27 16"></path>
                        </svg>
                    </label>
                    <div class="actions-dropdown">
                        <a href="' . route('events.show', $event) . '">
                            <i class="fas fa-eye text-blue-500"></i><span>View</span>
                        </a>
                        <a href="' . route('events.edit', $event) . '">
                            <i class="fas fa-edit text-yellow-500"></i><span>Edit</span>
                        </a>
                        <button onclick="deleteEvent(' . $event->id . ', \'' . addslashes($event->title) . '\')">
                            <i class="fas fa-trash text-red-500"></i><span>Delete</span>
                        </button>
                    </div>
                </div>';
            })
            ->rawColumns(['actions', 'registrations_count'])
            ->make(true);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'submission_deadline' => 'required|date',
            'event_date' => 'required|date|after_or_equal:submission_deadline',
        ]);

        $validated['image'] = $this->storeImage($request->file('image'));

        $event = Event::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully.',
            'event' => $this->eventPayload($event->fresh()),
        ]);
    }

    public function register(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:50',
            'university_name' => 'required|string|max:255',
            'semester_degree' => 'required|string|max:255',
            'country' => 'required|string|max:120',
            'interests' => 'required',
            'soft_skills' => 'required|array|min:1',
            'soft_skills.*' => 'required|string',
            'interpersonal_skills' => 'required|array|min:1',
            'interpersonal_skills.*' => 'required|string',
            'reason_to_join' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $user = User::withTrashed()->where('email', $validated['email'])->first();

        if (! $user) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'contact' => $validated['contact'],
                'password' => Hash::make(Str::random(24)),
            ]);
        } else {
            if ($user->trashed()) {
                $user->restore();
            }

            $user->update([
                'name' => $validated['name'],
                'contact' => $validated['contact'],
            ]);
        }

        if ($user && UserEvent::where('event_id', $event->id)->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This user is already registered for this event.',
            ], 409);
        }

        $registration = UserEvent::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'university_name' => $validated['university_name'],
            'semester_degree' => $validated['semester_degree'],
            'country' => $validated['country'],
            'interests' => $validated['interests'],
            'soft_skills' => array_values($validated['soft_skills']),
            'interpersonal_skills' => array_values($validated['interpersonal_skills']),
            'reason_to_join' => $validated['reason_to_join'],
        ]);

        try {
            Mail::to($user->email)->send(
                new EventRegistrationReceivedApplicant($registration, $event, $user)
            );
        } catch (\Exception $e) {
            Log::error('Event registration mail sending failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Event registration submitted successfully.',
            'registration' => [
                'id' => $registration->id,
                'event_id' => $registration->event_id,
                'user_id' => $registration->user_id,
                'email' => $user->email,
                'name' => $user->name,
                'contact' => $user->contact,
                'university_name' => $registration->university_name,
                'semester_degree' => $registration->semester_degree,
                'country' => $registration->country,
                'interests' => $registration->interests,
                'soft_skills' => $registration->soft_skills,
                'interpersonal_skills' => $registration->interpersonal_skills,
                'reason_to_join' => $registration->reason_to_join,
                'created_at' => $registration->created_at?->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }

    public function show(Event $event)
    {
        $event->loadCount('registrations');
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'submission_deadline' => 'required|date',
            'event_date' => 'required|date|after_or_equal:submission_deadline',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImageIfExists($event->image);
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully.',
            'event' => $this->eventPayload($event->fresh()),
        ]);
    }

    public function destroy(Event $event)
    {
        $eventId = $event->id;
        $this->deleteImageIfExists($event->image);
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully.',
            'event_id' => $eventId,
        ]);
    }

    private function storeImage($image): string
    {
        $destination = public_path('uploads/events');

        if (! File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
        $image->move($destination, $filename);

        return 'uploads/events/' . $filename;
    }

    private function deleteImageIfExists(?string $relativePath): void
    {
        if (! $relativePath || ! Str::startsWith($relativePath, 'uploads/events/')) {
            return;
        }

        $fullPath = public_path($relativePath);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function eventPayload(Event $event): array
    {
        return [
            'id' => $event->id,
            'image' => $event->image,
            'image_url' => asset($event->image),
            'category' => $event->category,
            'title' => $event->title,
            'description' => $event->description,
            'submission_deadline' => $event->submission_deadline?->format('Y-m-d'),
            'event_date' => $event->event_date?->format('Y-m-d'),
            'created_at' => $event->created_at?->format('Y-m-d H:i'),
            'updated_at' => $event->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
