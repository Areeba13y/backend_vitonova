<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\UserEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $events = Event::latest()->paginate(10);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Create form data loaded.',
            ]);
        }

        return redirect()->route('events.index');
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
        $interestOptions = [
            'Scholarship Aspirant',
            'Lead Ambassador',
            'Member Research Team',
        ];

        $softSkillOptions = [
            'Graphic Designing',
            'Video Editor',
            'Social Media Handling',
            'Web development',
            'Scientific Writing',
            'N/A',
        ];

        $interpersonalSkillOptions = [
            'Leadership skills',
            'Communication skills',
            'Event Management',
        ];

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:50',
            'university_name' => 'required|string|max:255',
            'semester_degree' => 'required|string|max:255',
            'country' => 'required|string|max:120',
            'interests' => 'required|in:' . implode(',', $interestOptions),
            'soft_skills' => 'required|array|min:1',
            'soft_skills.*' => 'required|string|in:' . implode(',', $softSkillOptions),
            'interpersonal_skills' => 'required|array|min:1',
            'interpersonal_skills.*' => 'required|string|in:' . implode(',', $interpersonalSkillOptions),
            'reason_to_join' => 'required|string',
        ]);

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

    public function show(Event $event, Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'event' => $this->eventPayload($event),
            ]);
        }

        return redirect()->route('events.index');
    }

    public function edit(Event $event, Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'event' => $this->eventPayload($event),
            ]);
        }

        return redirect()->route('events.index');
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
