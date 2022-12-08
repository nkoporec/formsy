<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    /** @var \App\Repositories\NotificationRepository */
    protected $notificationRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->middleware('auth');
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Get user notifications.
     */
    public function get($user_id)
    {
        $data = [];
        $notifications = $this->notificationRepository->get($user_id);
        foreach ($notifications as $key => $item) {
            $data[$key]['id'] = $item->id;
            $data[$key]['message'] = $item->message;
            $data[$key]['time'] = $item->updated_at->format('F j, Y, g:i a');
            $data[$key]['status'] = $item->status;
        }

        return response()->json($data, 200);
    }

    /**
     * Clear unread user notifications.
     */
    public function getUnread($user_id)
    {
        $notifications = $this->notificationRepository->getUnread($user_id);

        return response()->json(['count' => count($notifications)]);
    }

    /**
     * Clear unread user notifications.
     */
    public function clear($user_id)
    {
        $notifications = $this->notificationRepository->clear($user_id);

        return response()->json(['success']);
    }
}
