<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function updateOwnProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'designation' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'address' => $request->address,
            'designation' => $request->designation,
        ];

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $updateData['profile_picture'] = $request->file('profile_picture')->store('uploads/user-pictures', 'public');
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!'
        ]);
    }

    public function updateOwnPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
                'errors' => ['current_password' => ['Current password is incorrect.']]
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!'
        ]);
    }

    public function index(Request $request)
    {
        $selectedRoleId = $request->input('role_id');
        $selectedEventId = $request->input('event_id');
        $selectedUnitId = $request->input('unit_id');
        $search = $request->input('search');
        
        $teamMemberRole = Role::query()->where('code', 'team_member')->first();
        if (!$selectedRoleId && $teamMemberRole) {
            $selectedRoleId = $teamMemberRole->id;
        }
        
        $eventRegistrantRole = Role::query()->where('code', 'event_registrant')->first();
        $showEventFilter = $selectedRoleId && $eventRegistrantRole && (int) $selectedRoleId === (int) $eventRegistrantRole->id;

        $eventsForFilter = collect();
        if ($showEventFilter) {
            $eventsForFilter = Event::query()
                ->latest()
                ->get(['id', 'title']);
        }

        $users = User::query()
            ->with(['role', 'unit'])
            ->when($selectedRoleId, function ($query) use ($selectedRoleId) {
                $query->where('role_id', $selectedRoleId);
            })
            ->when($selectedUnitId, function ($query) use ($selectedUnitId) {
                $query->where('unit_id', $selectedUnitId);
            })
            ->when($showEventFilter && $selectedEventId, function ($query) use ($selectedEventId) {
                $query->whereHas('eventRegistrations', function ($registrationQuery) use ($selectedEventId) {
                    $registrationQuery->where('event_id', $selectedEventId);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('designation', 'like', '%' . $search . '%')
                      ->orWhere('details', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->expectsJson()) {
            $rowsHtml = '';
            foreach ($users as $index => $user) {
                $rowsHtml .= view('users.partials.table-row', ['user' => $user, 'index' => $index])->render();
            }
            return response()->json([
                'success' => true,
                'rows_html' => $rowsHtml,
                'pagination_html' => $users->hasPages() ? $users->links()->render() : '',
                'is_empty' => $users->isEmpty(),
                'show_event_filter' => (bool) $showEventFilter,
                'selected_event_id' => $showEventFilter ? $selectedEventId : null,
                'events' => $eventsForFilter->map(fn ($event) => [
                    'id' => $event->id,
                    'title' => $event->title,
                ])->values(),
            ]);
        }

        $roles = Role::query()->orderBy('name')->get(['id', 'name', 'code']);
        $units = Unit::query()->orderBy('name')->get(['id', 'name']);

        return view('users.index', compact('users', 'roles', 'units', 'selectedRoleId', 'selectedEventId', 'selectedUnitId', 'showEventFilter', 'eventsForFilter', 'search'));
    }

    public function create()
    {
        $units = Unit::query()->orderBy('name')->get(['id', 'name']);
        return view('users.create', compact('units'));
    }

    public function store(Request $request)
    {
        try {
            $teamMemberRole = Role::where('code', 'team_member')->first();
            
            $rules = [
                'unit_id' => 'required|exists:units,id',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'contact' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'designation' => 'nullable|string|max:255',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'details' => 'nullable|string',
            ];
            
            $request->validate($rules);

            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('uploads/user-pictures', 'public');
            }

            $user = User::create([
                'role_id' => $teamMemberRole?->id,
                'unit_id' => $request->unit_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(16)),
                'contact' => $request->contact,
                'address' => $request->address,
                'designation' => $request->designation,
                'profile_picture' => $profilePicturePath,
                'details' => $request->details,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'New user created successfully!',
                'redirect' => route('users.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('users.show', compact('user'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
    }

    public function edit(User $user)
    {
        $roles = Role::query()->orderBy('name')->get(['id', 'name', 'code']);
        $units = Unit::query()->orderBy('name')->get(['id', 'name']);
        return view('users.edit', compact('user', 'roles', 'units'));
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
                'errors' => ['current_password' => ['Current password is incorrect.']]
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!'
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info('Update request received', [
            'user_id' => $id,
            'request_data' => $request->except(['password', 'password_confirmation']),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        try {
            $user = User::findOrFail($id);
            

            $role = Role::find($request->role_id);
            $isAdmin = $role && $role->code === 'admin';
            $rules = [
                'role_id' => 'nullable|exists:roles,id',
                'unit_id' => 'nullable|exists:units,id',
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
                'contact' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'designation' => 'nullable|string|max:255',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'details' => 'nullable|string',
            ];
            if ($isAdmin) {
                $rules['password'] = 'nullable|string|min:8|confirmed';
            } else {
                $rules['password'] = 'nullable|string|min:8|confirmed';
            }

            if ($role && $role->code === 'team_member') {
                $rules['unit_id'] = 'required|exists:units,id';
            }
            $request->validate($rules);

            $updateData = [
                'role_id' => $request->role_id,
                'unit_id' => $request->unit_id,
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'address' => $request->address,
                'designation' => $request->designation,
                'details' => $request->details,
            ];

            if ($request->password) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $updateData['profile_picture'] = $request->file('profile_picture')->store('uploads/user-pictures', 'public');
            }

            $user->update($updateData);

            Log::info('User updated successfully', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'user' => $user->fresh()
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('User not found', ['user_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        Log::info('Delete request received', [
            'user_id' => $id,
            'url' => request()->url()
        ]);

        try {
            $user = User::findOrFail($id);
            $userName = $user->name;
            
            $user->delete();

            Log::info('User deleted successfully', ['user_id' => $id, 'user_name' => $userName]);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('User not found for deletion', ['user_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Delete error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users data for DataTables (Server-side rendering)
     */
    public function getUsersData(Request $request)
    {
        $teamMemberRole = Role::where('code', 'team_member')->first();
        
        $users = User::with(['unit'])
            ->where('role_id', $teamMemberRole->id)
            ->where('id', '!=', auth()->id())
            ->when($request->unit_id, function ($query) use ($request) {
                $query->where('unit_id', $request->unit_id);
            })
            ->select('users.*');

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('name', function ($user) {
                $avatar = $user->profile_picture 
                    ? '<img class="w-8 h-8 rounded-full object-cover mr-3" src="' . asset('storage/' . $user->profile_picture) . '" alt="' . $user->name . '">'
                    : '<div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold text-xs mr-3">' . strtoupper(substr($user->name, 0, 1)) . '</div>';
                return '<div class="flex items-center">' . $avatar . '<span class="font-medium text-gray-900">' . $user->name . '</span></div>';
            })
            ->addColumn('email', function ($user) {
                return $user->email ?? '-';
            })
            ->addColumn('unit', function ($user) {
                return $user->unit?->name ?? '-';
            })
            ->addColumn('designation', function ($user) {
                return $user->designation ?? '-';
            })
            ->addColumn('contact', function ($user) {
                return $user->contact ?? '-';
            })
            ->addColumn('actions', function ($user) {
                return '<div class="actions-menu">
                    <label class="hamburger">
                        <input type="checkbox" onchange="toggleActionsMenu(this)">
                        <svg viewBox="0 0 32 32">
                            <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                            <path class="line" d="M7 16 27 16"></path>
                        </svg>
                    </label>
                    <div class="actions-dropdown">
                        <a href="' . route('users.show', $user) . '">
                            <i class="fas fa-eye text-blue-500"></i><span>View</span>
                        </a>
                        <a href="' . route('users.edit', $user) . '">
                            <i class="fas fa-edit text-yellow-500"></i><span>Edit</span>
                        </a>
                        <button onclick="deleteUser(' . $user->id . ', \'' . addslashes($user->name) . '\')">
                            <i class="fas fa-trash text-red-500"></i><span>Delete</span>
                        </button>
                    </div>
                </div>';
            })
            ->rawColumns(['name', 'actions'])
            ->make(true);
    }

    /**
     * API: Get all users, filter by role (default: team_member)
     */
    public function apiGetUsers(Request $request)
    {
        $roleCode = $request->query('role', 'team_member');
        $role = Role::where('code', $roleCode)->first();
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role not found.'], 404);
        }
        $users = User::with('unit')
            ->where('role_id', $role->id)
            ->get();

        $groupedUsers = $users
            ->groupBy(function ($user) {
                return $user->unit?->name ?? 'Unassigned';
            })
            ->map(function ($unitUsers, $unitName) {
                return [
                    'unit_name' => $unitName,
                    'count' => $unitUsers->count(),
                    'users' => $unitUsers->values()->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'role_id' => $user->role_id,
                            'unit_id' => $user->unit_id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'email_verified_at' => $user->email_verified_at,
                            'contact' => $user->contact,
                            'address' => $user->address,
                            'designation' => $user->designation,
                            'profile_picture' => $user->profile_picture,
                            'details' => $user->details,
                            'created_at' => $user->created_at,
                            'updated_at' => $user->updated_at,
                            'deleted_at' => $user->deleted_at,
                        ];
                    }),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'role' => $roleCode,
            'groups' => $groupedUsers,
        ]);
    }
}
