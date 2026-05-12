<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

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

        if ($contactUser->name !== $request->name) {
            $contactUser->update(['name' => $request->name]);
        }

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
        $unreadUsersCount = ContactMessage::query()
            ->whereNotNull('user_id')
            ->where('is_read', false)
            ->distinct('user_id')
            ->count('user_id');

        return view('admin.contacts.index', compact('unreadUsersCount'));
    }

    public function getMessagesData(Request $request)
    {
        $latestMessageIds = ContactMessage::query()
            ->selectRaw('MAX(id) as id')
            ->whereNotNull('user_id')
            ->groupBy('user_id');

        $messages = ContactMessage::with(['user.contactMessages'])
            ->whereIn('id', $latestMessageIds)
            ->latest();

        return DataTables::of($messages)
            ->addIndexColumn()
            ->addColumn('name', function ($msg) {
                return $msg->user?->name ?? 'Guest';
            })
            ->addColumn('email', function ($msg) {
                return $msg->user?->email ?? 'N/A';
            })
            ->addColumn('message', function ($msg) {
                return '<span class="truncate max-w-xs block">' . Str::limit($msg->message, 80) . '</span>';
            })
            ->addColumn('status', function ($msg) {
                $hasUnread = $msg->user?->contactMessages->contains(fn ($item) => !$item->is_read) ?? false;
                $bg = $hasUnread ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700';
                $text = $hasUnread ? 'Unread' : 'Read';
                return '<span class="px-2 py-1 rounded text-xs font-medium ' . $bg . '">' . $text . '</span>';
            })
            ->addColumn('date', function ($msg) {
                return $msg->created_at->format('d M Y, h:i A');
            })
            ->addColumn('actions', function ($msg) {
                $userId = $msg->user_id;
                $userName = $msg->user?->name ?? 'Guest';
                $userEmail = $msg->user?->email ?? 'N/A';
                $hasUnread = $msg->user?->contactMessages->contains(fn ($item) => !$item->is_read) ?? false;
                
                return '<div class="actions-menu">
                    <label class="hamburger">
                        <input type="checkbox" onchange="toggleActionsMenu(this)">
                        <svg viewBox="0 0 32 32">
                            <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                            <path class="line" d="M7 16 27 16"></path>
                        </svg>
                    </label>
                    <div class="actions-dropdown">
                        <button onclick="openHistoryModal(' . $userId . ', \'' . addslashes($userName) . '\', \'' . addslashes($userEmail) . '\')">
                            <i class="fas fa-history text-blue-500"></i><span>View History</span>
                        </button>
                        ' . ($hasUnread ? '<button onclick="markAsRead(' . $userId . ')"><i class="fas fa-check text-green-500"></i><span>Mark Read</span></button>' : '') . '
                        <button onclick="deleteMessages(' . $userId . ')">
                            <i class="fas fa-trash text-red-500"></i><span>Delete</span>
                        </button>
                    </div>
                </div>';
            })
            ->rawColumns(['message', 'status', 'actions'])
            ->make(true);
    }

    public function getUserMessages(User $user)
    {
        $messages = $user->contactMessages()->latest()->get();
        return response()->json([
            'messages' => $messages->map(function ($msg) {
                return [
                    'message' => $msg->message,
                    'formatted_date' => $msg->created_at->format('d M Y, h:i A'),
                ];
            })
        ]);
    }

    public function markRead(User $user)
    {
        $user->contactMessages()->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true, 'message' => 'Messages marked as read']);
    }

    public function destroy(User $user)
    {
        $user->contactMessages()->delete();
        return response()->json(['success' => true, 'message' => 'Messages deleted']);
    }
}
