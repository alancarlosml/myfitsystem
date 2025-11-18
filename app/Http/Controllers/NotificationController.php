<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get unread notifications for current user/student
     */
    public function unread(Request $request)
    {
        // Try to detect guard automatically
        $guard = null;
        if (Auth::guard('student')->check()) {
            $guard = 'student';
        } elseif (Auth::guard('user')->check()) {
            $guard = 'user';
        } else {
            $guard = $request->get('guard', 'user'); // Fallback
        }
        
        if ($guard === 'student') {
            $studentId = Auth::guard('student')->id();
            $notifications = $this->notificationService->getUnreadForStudent($studentId);
        } else {
            $userId = Auth::guard('user')->id();
            $notifications = $this->notificationService->getUnreadForUser($userId);
        }

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count(),
        ]);
    }

    /**
     * Get all notifications for current user/student
     */
    public function index(Request $request)
    {
        // Try to detect guard automatically
        $guard = null;
        if (Auth::guard('student')->check()) {
            $guard = 'student';
        } elseif (Auth::guard('user')->check()) {
            $guard = 'user';
        } else {
            $guard = $request->get('guard', 'user'); // Fallback
        }
        
        $perPage = $request->get('per_page', 20);
        
        if ($guard === 'student') {
            $studentId = Auth::guard('student')->id();
            $notifications = $this->notificationService->getAllForStudent($studentId, $perPage);
            $unreadCount = $this->notificationService->getUnreadForStudent($studentId)->count();
        } else {
            $userId = Auth::guard('user')->id();
            $notifications = $this->notificationService->getAllForUser($userId, $perPage);
            $unreadCount = $this->notificationService->getUnreadForUser($userId)->count();
        }

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($notifications);
        }

        // Otherwise return view
        $submenu = $guard === 'student' ? 'app' : 'gestao';
        // Para views, 'user' guard usa a pasta 'admin'
        $viewGuard = $guard === 'user' ? 'admin' : $guard;
        
        return view($viewGuard . '.notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'guard' => $guard,
            'submenu' => $submenu,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        // Check if user owns this notification
        $guard = null;
        if (Auth::guard('student')->check()) {
            $guard = 'student';
        } elseif (Auth::guard('user')->check()) {
            $guard = 'user';
        }
        
        $notification = \App\Models\Notification::findOrFail($id);
        
        // Verify ownership
        if ($guard === 'student' && $notification->student_id !== Auth::guard('student')->id()) {
            abort(403);
        } elseif ($guard === 'user' && $notification->user_id !== Auth::guard('user')->id()) {
            abort(403);
        }
        
        $notification = $this->notificationService->markAsRead($id);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'notification' => $notification,
            ]);
        }
        
        return redirect()->back()->with('success', 'Notificação marcada como lida');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        // Try to detect guard automatically
        $guard = null;
        if (Auth::guard('student')->check()) {
            $guard = 'student';
        } elseif (Auth::guard('user')->check()) {
            $guard = 'user';
        } else {
            $guard = $request->get('guard', 'user'); // Fallback
        }
        
        if ($guard === 'student') {
            $studentId = Auth::guard('student')->id();
            $count = $this->notificationService->markAllAsReadForStudent($studentId);
        } else {
            $userId = Auth::guard('user')->id();
            $count = $this->notificationService->markAllAsReadForUser($userId);
        }

        return response()->json([
            'success' => true,
            'marked_count' => $count,
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $this->notificationService->delete($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Notificação excluída com sucesso',
        ]);
    }
}
