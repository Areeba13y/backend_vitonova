@extends('layouts.master')

@section('title', 'Registration Details')
@section('page_title', 'Event Registrations')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <a href="{{ route('event-registrations.event', $event) }}" class="inline-flex items-center text-sm text-green-700 hover:text-green-800 mb-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to {{ $event->title }} Registrations
            </a>
            <h4 class="text-xl font-semibold text-gray-800">Registered Participant Profile</h4>
            <p class="text-sm text-gray-500 mt-1">Complete participant and registration details.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">User Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Full Name</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $registration->user?->name ?? 'Unknown User' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Email</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $registration->user?->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Contact</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $registration->user?->contact ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Country</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $registration->country }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">University</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $registration->university_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Semester / Degree</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $registration->semester_degree }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Application Responses</h3>
                <div class="space-y-5">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Area of Interest</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $registration->interests }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2">Soft Skills</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(($registration->soft_skills ?? []) as $skill)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 border border-green-200">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2">Interpersonal Skills</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(($registration->interpersonal_skills ?? []) as $skill)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Reason to Join</p>
                        <p class="text-sm text-gray-700 mt-2 leading-relaxed whitespace-pre-line">{{ $registration->reason_to_join }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Registration Summary</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Registration ID</p>
                        <p class="text-base font-medium text-gray-900 mt-1">#{{ $registration->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Submitted At</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $registration->created_at?->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Last Updated</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $registration->updated_at?->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Event Title</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $event->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Category</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $event->category ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Submission Deadline</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $event->submission_deadline?->format('Y-m-d') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Event Date</p>
                        <p class="text-base font-medium text-gray-900 mt-1">{{ $event->event_date?->format('Y-m-d') ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
