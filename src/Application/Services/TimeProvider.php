<?php

declare(strict_types=1);

namespace TwitchAnalytics\Application\Services;
interface TimeProvider
{
    public function now(): \DateTime;
}
