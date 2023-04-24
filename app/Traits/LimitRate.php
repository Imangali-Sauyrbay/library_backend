<?php

namespace App\Traits;

use Illuminate\Http\Response as Code;
use Illuminate\Support\Facades\RateLimiter;

trait LimitRate
{
    protected int $defaultMaxAttempts = 3;
    protected int $defaultDecaySeconds = 60;

    private function limitRate($key)
    {
        $isReachedLimit = RateLimiter::tooManyAttempts(
            $key,
            $this->getMaxAttempts(),
            $this->getDecaySeconds()
        );

        if (! $isReachedLimit) {
            RateLimiter::hit(
                $key,
                $this->getDecaySeconds()
            );
            return false;
        }

        return $this->getTooManyAttemptsResponce($key);
    }

    private function getMaxAttempts()
    {
        return $this->maxAttempts ?: $this->defaultMaxAttempts;
    }

    private function getDecaySeconds()
    {
        return $this->decaySeconds ?: $this->defaultDecaySeconds;
    }

    private function getTooManyAttemptsResponce($key)
    {
        return response()->json([
            'error' => true,
            'mesage' => 'Too Many Attempts.',
            'available_at' => $this->getRemainingTime($key),
        ], Code::HTTP_TOO_MANY_REQUESTS);
    }

    private function clearRate($key)
    {
        return RateLimiter::clear($key);
    }

    private function getRemainingTime($key)
    {
        return now()->addSeconds(RateLimiter::availableIn($key));
    }

    private function getRemainingAttempts($key)
    {
        return RateLimiter::remaining(
            $key,
            $this->getMaxAttempts()
        );
    }

    private function getLoginFailResponce($key, $base)
    {
        $attempts = $this->getRemainingAttempts($key);

        return response()
            ->json(array_merge($base, [
                'max_attempts' => $this->getMaxAttempts(),
                'remaining_attempts' => $attempts,
                'remaining_time' => $attempts ? null : $this->getRemainingTime($key),
            ]), Code::HTTP_UNAUTHORIZED);
    }
}
