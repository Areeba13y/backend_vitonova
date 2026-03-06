<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\UserEvent;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function index()
    {
        $events = Event::query()
            ->withCount('registrations')
            ->latest()
            ->paginate(9);

        return view('registrations.index', compact('events'));
    }

    public function eventRegistrations(Request $request, Event $event)
    {
        $search = trim((string) $request->query('search', ''));

        $registrations = $event->registrations()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('contact', 'like', '%' . $search . '%');
                    })->orWhere('university_name', 'like', '%' . $search . '%')
                        ->orWhere('country', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('registrations.event', compact('event', 'registrations', 'search'));
    }

    public function show(Event $event, UserEvent $registration)
    {
        if ((int) $registration->event_id !== (int) $event->id) {
            abort(404);
        }

        $registration->load(['event', 'user']);

        return view('registrations.show', compact('event', 'registration'));
    }
}
