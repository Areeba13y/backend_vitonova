<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // If user is authenticated, reuse their account.
        // Otherwise, validate guest identity and reuse the same account by email.
        if (Auth::check()) {
            $contactUser = Auth::user();
        } else {
            $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email',
            ]);

            // Store guest email exactly as submitted.
            // Reuse existing user record when email already exists.
            $contactUser = User::withTrashed()->firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'password' => bcrypt(uniqid()),
                ]
            );

            if ($contactUser->trashed()) {
                $contactUser->restore();
            }

            // Keep latest submitted guest name in sync.
            if ($contactUser->name !== $request->name) {
                $contactUser->update(['name' => $request->name]);
            }
        }

        // Always create a new message row for the same user/email and mark as unread.
        ContactMessage::create([
            'user_id' => $contactUser->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully. Thanks for contacting us.',
        ]);
    }

    public function index()
    {
        $filter = request()->query('status', 'all');
        if (! in_array($filter, ['all', 'unread', 'read'], true)) {
            $filter = 'all';
        }

        $latestMessageIds = ContactMessage::query()
            ->selectRaw('MAX(id) as id')
            ->whereNotNull('user_id')
            ->groupBy('user_id');

        $messagesQuery = ContactMessage::with([
            'user.contactMessages' => function ($query) {
                $query->latest();
            },
        ])
            ->whereIn('id', $latestMessageIds)
            ->latest();

        if ($filter === 'unread') {
            $messagesQuery->whereIn('user_id', function ($query) {
                $query->select('user_id')
                    ->from('contact_messages')
                    ->where('is_read', false)
                    ->whereNotNull('user_id')
                    ->groupBy('user_id');
            });
        }

        if ($filter === 'read') {
            $messagesQuery->whereNotIn('user_id', function ($query) {
                $query->select('user_id')
                    ->from('contact_messages')
                    ->where('is_read', false)
                    ->whereNotNull('user_id')
                    ->groupBy('user_id');
            });
        }

        $messages = $messagesQuery
            ->paginate(10)
            ->withQueryString();

        $unreadUsersCount = ContactMessage::query()
            ->whereNotNull('user_id')
            ->where('is_read', false)
            ->distinct('user_id')
            ->count('user_id');

        return view('admin.contacts.index', compact('messages', 'unreadUsersCount', 'filter'));
    }

    public function markRead(User $user)
    {
        $user->contactMessages()->where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All messages marked as read for this user.');
    }

    public function destroy(User $user)
    {
        $user->contactMessages()->delete();
        return back()->with('success', 'All messages deleted for this user.');
    }
}
