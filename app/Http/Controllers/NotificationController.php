<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // For admin users, show notifications for all admin users
        // For other users, show only their own notifications
        if (in_array($user->role, ['admin', 'super_admin'])) {
            // Get all admin user IDs
            $adminUserIds = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->pluck('id');
            
            // Fetch notifications for all admin users
            $notifications = CustomNotification::whereIn('user_id', $adminUserIds)
                ->whereIn('type', [
                    'NewFundRequestSubmitted',
                    'NewManuscriptSubmitted',
                    'fund_request',
                    'manuscript',
                    'stipend_notification'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // For non-admin users, show only their own notifications
            $notifications = CustomNotification::where('user_id', $user->id)
                ->whereIn('type', [
                    'NewFundRequestSubmitted',
                    'NewManuscriptSubmitted',
                    'fund_request',
                    'manuscript',
                    'stipend_notification'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        // Use Livewire-based index view
        return view('admin.notifications.index-livewire', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'super_admin'])) {
            // Admin users can mark any admin notification as read
            $adminUserIds = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->pluck('id');
            $notification = CustomNotification::where('id', $id)
                ->whereIn('user_id', $adminUserIds)
                ->firstOrFail();
        } else {
            // Non-admin users can only mark their own notifications as read
            $notification = CustomNotification::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }

        $notification->is_read = true;
        $notification->read_at = now();
        $notification->save();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'super_admin'])) {
            // Admin users can mark all admin notifications as read
            $adminUserIds = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->pluck('id');
            $updatedCount = CustomNotification::whereIn('user_id', $adminUserIds)
                ->where('is_read', false)
                ->whereIn('type', [
                    'NewFundRequestSubmitted',
                    'NewManuscriptSubmitted',
                    'fund_request',
                    'manuscript',
                    'stipend_notification'
                ])
                ->update(['is_read' => true]);
        } else {
            // Non-admin users can only mark their own notifications as read
            $updatedCount = CustomNotification::where('user_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'updated_count' => $updatedCount
            ]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Get unread notifications count for the authenticated user (admin or scholar).
     */
    public function getUnreadCount()
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'super_admin'])) {
            $adminUserIds = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->pluck('id');
            $count = CustomNotification::whereIn('user_id', $adminUserIds)
                ->where('is_read', false)
                ->whereIn('type', [
                    'NewFundRequestSubmitted',
                    'NewManuscriptSubmitted',
                    'fund_request',
                    'manuscript',
                    'stipend_notification'
                ])
                ->count();
        } else {
            // For scholars, filter by relevant notification types
            $count = CustomNotification::where('user_id', $user->id)
                ->where('is_read', false)
                ->whereIn('type', [
                    'fund_request',
                    'manuscript',
                    'stipend_notification',
                    'stipend_disbursement',
                    'profile_update',
                    'document'
                ])
                ->count();
        }

        return response()->json(['count' => $count]);
    }
}
