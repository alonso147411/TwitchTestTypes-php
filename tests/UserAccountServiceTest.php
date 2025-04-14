<?php

namespace TwitchAnalytics\Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use TwitchAnalytics\Application\Services\UserAccountService;
use TwitchAnalytics\Domain\Exceptions\UserNotFoundException;
use TwitchAnalytics\Domain\Interfaces\UserRepositoryInterface;
use TwitchAnalytics\Domain\Models\User;

class UserAccountServiceTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function getAccountAgeWithInvalidUser()
    {
        $this->expectException(UserNotFoundException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findByDisplayName')->willReturn(null);

        $service = new UserAccountService($userRepository);

        $service->getAccountAge('invalid_user');
    }

    /**
     * @test
     * @throws Exception
     */
    public function getAccountAge()
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->method('getDisplayName')->willReturn('valid_user');
        $mockUser->method('getCreatedAt')->willReturn('2023-01-01');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findByDisplayName')->willReturn($mockUser);

        $service = new UserAccountService($userRepository);

        $result = $service->getAccountAge('valid_user');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('days_since_creation', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertEquals('valid_user', $result['name']);
        $this->assertEquals('2023-01-01', $result['created_at']);
    }
}
