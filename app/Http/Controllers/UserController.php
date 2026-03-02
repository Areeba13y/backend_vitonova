<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
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
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'contact' => $request->contact,
                'address' => $request->address,
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
            ]);

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'address' => $request->address,
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