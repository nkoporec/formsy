<?php

namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\Redis;

class RateLimit
{

    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        // Allow 100 jobs per 5 seconds.
        Redis::throttle('key')
            ->block(0)->allow(100)->every(5)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release(5);
            });
    }
}
