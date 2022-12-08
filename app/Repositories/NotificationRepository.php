<?php

namespace App\Repositories;

use App\Notification;

class NotificationRepository
{

    /**
     *  Get last five notifications.
     *
     * @param int $user_id
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get($user_id)
    {
        $notifications = Notification::where([
            'user_id' => $user_id,
        ])->orderBy('updated_at', 'DESC')->take(5)->get();

        return $notifications;
    }

    /**
     *  Get unread notifications.
     *
     * @param int $user_id
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUnread($user_id)
    {
        $notifications = Notification::where([
            'user_id' => $user_id,
            'status' => 1,
        ])->orderBy('updated_at', 'DESC')->get();

        return $notifications;
    }

    /**
     *  Clear unread notifications.
     *
     * @param int $user_id
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function clear($user_id)
    {
        $notifications = $this->getUnread($user_id);
        foreach ($notifications as $item) {
            $item->status = 0;
            $item->save();
        }

        return $notifications;
    }

    /**
     *  Create a new notification entry.
     *
     * @param int $user_id
     *   User id.
     * @param string $type
     *   Notification type.
     * @param string $message
     *   Notification message.
     *
     * @return \Illuminate\Support\Collection
     */
    public function create($user_id, $type, $message)
    {
        $notification = Notification::create([
            'user_id' => $user_id,
            'type' => $type,
            'message' => $message,
            'status' => 1,
        ]);

        return $notification;
    }
}
