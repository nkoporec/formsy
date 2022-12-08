<?php

namespace App\Repositories;

use App\FormEvent;
use Illuminate\Support\Facades\Auth;

class FormEventRepository
{

    /**
     *  Get event entry.
     *
     * @param int $id
     *   Event id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get($id)
    {
        $event = FormEvent::where([
            'id' => $id,
        ])->first();

        return $event;
    }

    /**
     *  Get all events for a user.
     *
     * @param int $user_id
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll($user_id = null, $paginate = false)
    {
        if (!$user_id) {
            $events = FormEvent::where([
                'user_id' => Auth::id(),
            ])->orderBy('updated_at', 'desc');
        } else {
            $events = FormEvent::where([
                'user_id' => $user_id,
            ])->orderBy('updated_at', 'desc');
        }

        if ($paginate) {
            return $events->paginate($paginate);
        }

        return $events->get();
    }


    /**
     *  Get all events for a form.
     *
     * @param int $form_id
     *   Form id.
     * @param int $user_id
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFormEvents($form_id, $user_id = null, $paginate = false)
    {
        if (!$user_id) {
            $events = FormEvent::where([
                'form_id' => $form_id,
                'user_id' => Auth::id(),
            ])->orderBy('updated_at', 'desc');
        } else {
            $events = FormEvent::where([
                'form_id' => $form_id,
                'user_id' => $user_id,
            ])->orderBy('updated_at', 'desc');
        }

        if ($paginate) {
            return $events->paginate($paginate);
        }


        return $events->get();
    }


    /**
     *  Create a new event entry.
     *
     * @param int $form_id
     *   Form id.
     * @param int $user_id
     *   User id.
     * @param string $type
     *   Event type.
     * @param string $message
     *   Event message.
     *
     * @return \Illuminate\Support\Collection
     */
    public function create($form_id, $user_id, $type, $message)
    {
        $event = FormEvent::create([
            'form_id' => $form_id,
            'user_id' => $user_id,
            'type' => $type,
            'message' => $message,
        ]);

        return $event;
    }
}
