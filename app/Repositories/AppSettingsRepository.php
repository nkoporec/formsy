<?php

namespace App\Repositories;

use App\AppId;

class AppSettingsRepository
{

    /**
     *  Checks if app key exists.
     *
     * @param string $key
     *   App key.
     *
     * @return bool
     */
    public function keyExists($key)
    {
        $appKey = AppId::where([
            'user_app_string' => $key
        ])->first();

        if (!$appKey) {
            return false;
        }

        return true;
    }

    /**
     *  Get user key.
     *
     * @param int $userId
     *   User id.
     * @param bool $collection
     *   Return results as collection.
     *
     * @return string|\Illuminate\Support\Collection
     */
    public function getKey($userId, $collection = false)
    {
        $appKey = AppId::where([
            'user_id' => $userId,
        ])->first();

        if (!$appKey) {
            return null;
        }

        if ($collection) {
            return $appKey;
        }

        return $appKey->user_app_string;
    }

    /**
     *  Set user key.
     *
     * @param int $userId
     *   User id.
     *
     * @return string
     */
    public function setKey($userId, $key)
    {
        $appKey = $this->getKey($userId);
        if ($appKey) {
            $appKey->user_app_string = $key;
            return $appKey->save();
        }

        $appKey = AppId::create([
            'user_app_string' => $key,
            'user_id' => $userId,
        ]);

        return $appKey;
    }


    /**
     *  Get user by key.
     *
     * @param string $key
     *   App key.
     * @param bool $collection
     *   Return results as collection.
     *
     * @return string
     */
    public function getUserByKey($key, $collection = false)
    {
        $appKey = AppId::where([
            'user_app_string' => $key,
        ])->first();

        if (!$appKey) {
            return false;
        }

        if ($collection) {
            return $appKey;
        }

        return $appKey->user_id;
    }
}
