<?php

namespace TwitchAnalytics\Tests\Integration\Controllers\GetUserPlatformAge;

use PHPUnit\Framework\TestCase;
use TwitchAnalytics\Application\Services\UserAccountService;
use TwitchAnalytics\Controllers\GetUserPlatformAge\GetUserPlatformAgeController;
use TwitchAnalytics\Controllers\GetUserPlatformAge\UserNameValidator;
use TwitchAnalytics\Infrastructure\ApiClient\FakeTwitchApiClient;
use TwitchAnalytics\Infrastructure\Repositories\ApiUserRepository;
use TwitchAnalytics\Infrastructure\Time\SystemTimeProvider;

class GetUserPlatformAgeControllerTest extends TestCase
{
    /**
     * @test
     */
    public function GetsErrorIfNotUserNameGiven()
    {
        $apiClient = new FakeTwitchApiClient();
        $userRepository = new ApiUserRepository($apiClient);
        $timeProvider = new SystemTimeProvider();

        $userAccountService = new UserAccountService($userRepository,$timeProvider);
        $userNameValidator = new UserNameValidator();

        $getUserPlatformAgeController = new GetUserPlatformAgeController($userAccountService,$userNameValidator);

       $response = $getUserPlatformAgeController();

       $this->assertEquals(['error' => 'INVALID_REQUEST',
            'message' => 'Name parameter is required',
            'status' =>400], $response);
    }

}
