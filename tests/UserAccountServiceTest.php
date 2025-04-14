<?php

namespace TwitchAnalytics\Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use TwitchAnalytics\Application\Services\UserAccountService;
use TwitchAnalytics\Domain\Exceptions\UserNotFoundException;
use TwitchAnalytics\Domain\Interfaces\UserRepositoryInterface;
use TwitchAnalytics\Domain\Models\User;

/**
 * @coversDefaultClass \TwitchAnalytics\Application\Services\UserAccountService
 * @covers \TwitchAnalytics\Application\Services\UserAccountService::getAccountAge
 * @covers \TwitchAnalytics\Domain\Exceptions\UserNotFoundException
 */
class UserAccountServiceTest extends TestCase
{

    /**
     * @test
     * @throws Exception
     * @covers ::getAccountAge
     */
    public function getAccountAge()
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findByDisplayName')->willReturn(
            new User(
                '12345',
                'ninja',
                'Ninja',
                '',
                'partner',
                'Professional Gamer and Streamer',
                'https://example.com/ninja.jpg',
                'https://example.com/ninja-offline.jpg',
                500000,
                '2011-11-20T00:00:00Z'
            )
        );
        $service = new UserAccountService($userRepository);
        $result = $service->getAccountAge('ninja');
        $this->assertIsArray($result);

    }

    /**
     * @test
     * @throws Exception
     * @covers ::getAccountAge
     */
    public function getAccountAgeWithInvalidUser()
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findByDisplayName')->willReturn(null);

        $service = new UserAccountService($userRepository);

        $this->expectException(UserNotFoundException::class);

        $this->expectExceptionMessage('No user found with given name: invalid_user');

        $service->getAccountAge('invalid_user');


    }

}
