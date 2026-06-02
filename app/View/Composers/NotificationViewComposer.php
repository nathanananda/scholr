<?php

namespace App\View\Composers;

use App\Models\Notification;
use Illuminate\View\View;

class NotificationViewComposer
{
    public function compose(View $view): void
    {
        $notifications = collect();
        $unreadNotifCount = 0;

        if (auth()->check()) {
            $notifications = Notification::where('user_id', auth()->id())
                ->latest()
                ->take(20)
                ->get()
                ->map(fn($n) => [
                    'id'     => $n->id,
                    'title'  => $n->title,
                    'desc'   => $n->body,
                    'time'   => $n->created_at->diffForHumans(),
                    'read'   => !is_null($n->read_at),
                    'icon'   => $n->data['icon'] ?? 'fa-solid fa-bell',
                    'iconBg' => $n->data['icon_bg'] ?? 'bg-teal-50 text-teal-600',
                ]);

            $unreadNotifCount = $notifications->where('read', false)->count();
        }

        $view->with('notifications', $notifications);
        $view->with('unreadNotifCount', $unreadNotifCount);
    }
}
