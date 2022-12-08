<?php

namespace App\Repositories;

use App\Submission;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserRepository
{

    /**
     *  Get user.
     *
     * @param id $userId
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get($userId = null)
    {
        if (!$userId) {
            return User::where([
                'id' => Auth::id(),
            ])->first();
        }

        return User::where([
            'id' => $userId,
        ])->first();
    }

    /**
     *  Update user.
     *
     * @param array $data
     *   User data.
     * @param id $userId
     *   User id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function update($data, $userId = null)
    {
        $user = $this->get($userId);

        $user->update($data);

        return $user;
    }

    /**
     *  Get user monthly submissions.
     *
     * @param id $userId
     *   User id.
     * @param bool $count
     *   Only return number of submissions and not records.
     *
     * @return int | \Illuminate\Support\Collection
     */
    public function getMonthlySubmissions($userId, $count = true)
    {
        $user = $this->get($userId);
        $userCreated = Carbon::parse($user->created_at);
        $currentDate = Carbon::now();

        // Get monthly interval start date.
        if ($currentDate->day >= $userCreated->day) {
            $startDate = $userCreated;
        } else {
            $prevMonth = $currentDate->month - 1;
            $startDate = Carbon::createFromDate(null, $prevMonth, $userCreated->day);
        }

        // Get monthly interval end date.
        $endDate = $startDate->copy();
        $endDate->addMonth(1);

        $submissions = Submission::where('user_id', $userId)
        ->where('created_at', '>=', $startDate)
        ->where('created_at', '<=', $endDate)
        ->where('spam', '0')
        ->get();

        if ($count) {
            return count($submissions);
        }

        return $submissions;
    }
}
