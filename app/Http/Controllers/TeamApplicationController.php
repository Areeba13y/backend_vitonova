<?php

namespace App\Http\Controllers;

use App\Models\TeamApplication;
use App\Mail\ApplicationSubmittedAdmin;
use App\Mail\ApplicationReceivedApplicant;
use App\Mail\ApplicationApprovedApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TeamApplicationController extends Controller
{
    /**
     * Display a listing of team applications for admin.
     */
    public function index()
    {
        $applications = TeamApplication::latest()->paginate(10);
        return view('registrations.team-index', compact('applications'));
    }

    /**
     * Store a newly created team application in storage (Public API).
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'position' => 'required|string|in:Research Positions,Internships,Study Abroad Guidance,Mentorship Program',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB limit
            ]);

            // Handle file upload
            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $path = $file->store('resumes');
                $validated['resume_path'] = $path;
                $validated['resume_original_name'] = $file->getClientOriginalName();
            }

            $application = TeamApplication::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'position' => $validated['position'],
                'resume_path' => $validated['resume_path'],
                'resume_original_name' => $validated['resume_original_name'] ?? null,
                'status' => 'pending',
            ]);

            // Send emails
            try {
                // To Admin
                Mail::to('admin@web.com')->send(new ApplicationSubmittedAdmin($application));
                
                // To Applicant
                Mail::to($application->email)->send(new ApplicationReceivedApplicant($application));
            } catch (\Exception $e) {
                Log::error('Mail sending failed: ' . $e->getMessage());
                // We still returned success as the application is saved.
            }

            return response()->json([
                'success' => true,
                'message' => 'Your application has been submitted successfully.',
                'application' => $application
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Team Application Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your application.'
            ], 500);
        }
    }

    /**
     * Approve the specific team application.
     */
    public function approve($id)
    {
        try {
            $application = TeamApplication::findOrFail($id);
            $application->update(['status' => 'approved']);

            // Send approval email
            try {
                Mail::to($application->email)->send(new ApplicationApprovedApplicant($application));
            } catch (\Exception $e) {
                Log::error('Approval mail sending failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Application approved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve application.'
            ], 500);
        }
    }

    /**
     * Reject/Reset the specific team application.
     */
    public function destroy($id)
    {
        try {
            $application = TeamApplication::findOrFail($id);
            $application->delete(); // Soft delete

            return response()->json([
                'success' => true,
                'message' => 'Application removed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove application.'
            ], 500);
        }
    }

    /**
     * Download the resume file.
     */
    public function download($id)
    {
        try {
            $application = TeamApplication::findOrFail($id);
            if (!Storage::exists($application->resume_path)) {
                abort(404, 'File not found');
            }
            
            return Storage::download(
                $application->resume_path, 
                $application->resume_original_name ?? 'resume.pdf'
            );
        } catch (\Exception $e) {
            abort(500, 'Error downloading file');
        }
    }
}
