<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $selectedRoleId = $request->input('role_id');
        $selectedEventId = $request->input('event_id');
        $eventRegistrantRole = Role::query()->where('code', 'event_registrant')->first();
        $showEventFilter = $selectedRoleId && $eventRegistrantRole && (int) $selectedRoleId === (int) $eventRegistrantRole->id;

        $eventsForFilter = collect();
        if ($showEventFilter) {
            $eventsForFilter = Event::query()
                ->latest()
                ->get(['id', 'title']);
        }

        $users = User::query()
            ->with('role')
            ->when($selectedRoleId, function ($query) use ($selectedRoleId) {
                $query->where('role_id', $selectedRoleId);
            })
            ->when($showEventFilter && $selectedEventId, function ($query) use ($selectedEventId) {
                $query->whereHas('eventRegistrations', function ($registrationQuery) use ($selectedEventId) {
                    $registrationQuery->where('event_id', $selectedEventId);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'rows_html' => view('users.partials.table-rows', compact('users'))->render(),
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

        return view('users.index', compact('users', 'roles', 'selectedRoleId', 'selectedEventId', 'showEventFilter', 'eventsForFilter'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'contact' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'designation' => 'nullable|string|max:255',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'contact' => $request->contact,
                'address' => $request->address,
                'designation' => $request->designation,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'user' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
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
        return view('users.edit', compact('user'));
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
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'contact' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'designation' => 'nullable|string|max:255',
            ]);

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'address' => $request->address,
                'designation' => $request->designation,
            ];

            if ($request->password) {
                $updateData['password'] = Hash::make($request->password);
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
}
