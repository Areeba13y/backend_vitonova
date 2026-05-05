<?php

namespace App\Http\Controllers;

use App\Models\TeamApplication;
use App\Models\Role;
use App\Models\Unit;
use App\Mail\ApplicationSubmittedAdmin;
use App\Mail\ApplicationReceivedApplicant;
use App\Mail\ApplicationApprovedApplicant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $applications = TeamApplication::with('user')->latest()->paginate(10);
        $units = Unit::orderBy('name')->get(['id', 'name']);
        return view('registrations.team-index', compact('applications', 'units'));
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
                'position' => 'required|string',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB limit
            ]);

            // Handle file upload
            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $path = $file->store('resumes');
                $validated['resume_path'] = $path;
                $validated['resume_original_name'] = $file->getClientOriginalName();
            }

            $applicantRoleId = Role::where('code', 'team_applicant')->value('id');

            $user = User::withTrashed()->firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'password' => bcrypt(uniqid()),
                    'role_id' => $applicantRoleId,
                ]
            );

            if ($user->trashed()) {
                $user->restore();
            }

            $user->update([
                'name' => $validated['name'],
                'role_id' => $applicantRoleId ?? $user->role_id,
            ]);

            $alreadyRequested = TeamApplication::where('user_id', $user->id)->exists();
            if ($alreadyRequested) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already requested.',
                ], 409);
            }

            // Create application linked to submitted email user
            $application = TeamApplication::create([
                'user_id' => $user->id,
                'position' => $validated['position'],
                'resume_path' => $validated['resume_path'],
                'resume_original_name' => $validated['resume_original_name'] ?? null,
                'status' => 'pending',
            ]);

            $application->load('user');

            // Send admin email (independent)
            try {
                $adminEmail = User::whereHas('role', function ($query) {
                    $query->where('code', 'admin');
                })->value('email');

                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new ApplicationSubmittedAdmin($application));
                } else {
                    Log::warning('No admin user found to receive team application email.');
                }
            } catch (\Exception $e) {
                Log::error('Admin mail sending failed: ' . $e->getMessage());
            }

            // Send applicant submission confirmation (independent)
            try {
                if ($application->user?->email) {
                    Mail::to($application->user->email)->send(new ApplicationReceivedApplicant($application));
                } else {
                    Log::warning('Applicant email missing for team application ID: ' . $application->id);
                }
            } catch (\Exception $e) {
                Log::error('Applicant submission mail sending failed: ' . $e->getMessage());
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
    public function approve(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'unit_id' => 'required|exists:units,id',
                'designation' => 'required|string|max:255',
            ]);

            $application = TeamApplication::with('user')->findOrFail($id);
            $application->update(['status' => 'approved']);

            $teamMemberRoleId = Role::where('code', 'team_member')->value('id');
            if ($teamMemberRoleId && $application->user) {
                $application->user->update([
                    'role_id' => $teamMemberRoleId,
                    'unit_id' => $validated['unit_id'],
                    'designation' => $validated['designation'],
                ]);
            }

            // Send approval email
            try {
                if ($application->user?->email) {
                    Mail::to($application->user->email)->send(new ApplicationApprovedApplicant($application));
                } else {
                    Log::warning('Applicant email missing for approved application ID: ' . $application->id);
                }
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
