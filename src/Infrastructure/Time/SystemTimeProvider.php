<?php

declare(strict_types=1);

namespace TwitchAnalytics\Infrastructure\Time;

use TwitchAnalytics\Application\Services\TimeProvider;


class SystemTimeProvider implements TimeProvider
{
    public function now(): \DateTime
    {
        return new \DateTime();
    }
}