<?php

namespace TwitchAnalytics\Tests\Unit\Application\Service;

use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use TwitchAnalytics\Application\Services\TimeProvider;
use TwitchAnalytics\Application\Services\UserAccountService;
use TwitchAnalytics\Domain\Exceptions\UserNotFoundException;
use TwitchAnalytics\Domain\Interfaces\UserRepositoryInterface;
use TwitchAnalytics\Domain\Models\User;

class UserAccountServiceTest extends TestCase
{
    private UserRepositoryInterface $userRepository;
    private TimeProvider $timeProvider;
    private UserAccountService $userAccountService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->timeProvider = Mockery::mock(TimeProvider::class);
        $this->userAccountService = new UserAccountService(
            $this->userRepository,
            $this->timeProvider
        );
    }

    /**
     * @test
     *
     */
    public function returnsErrorIfUserDoesNotExist()
    {
        $displayName = 'nonexistent_user';
        $userAccountService = new UserAccountService($this->userRepository, $this->timeProvider);

        $this->userRepository->expects('findByDisplayName')
            ->with($displayName)
            ->andReturnNull();

        $this->expectException(UserNotFoundException::class);

        $this->expectExceptionMessage("No user found with given name: {$displayName}");

        $userAccountService->getAccountAge($displayName);

    }

    /**
     * @test
     *
     */

    public function getUserAgeIfUserExists()
    {
        $displayName = 'TestUser';
        $createdAt = '2023-01-01T00:00:00Z';
        $oneYearSinceCreation = new DateTime('2024-01-01T00:00:00Z');

        $user = $this->getUser($displayName, $createdAt);

        $this->userRepository->expects('findByDisplayName')
            ->with($displayName)
            ->andReturns($user);
        $this->timeProvider->expects('now')
            ->andReturns($oneYearSinceCreation);

        $accountAge = $this->userAccountService->getAccountAge($displayName);

        $this->assertEquals([
            'name' => $displayName,
            'days_since_creation' => 365,
            'created_at' => $createdAt
        ], $accountAge);
    }

    /**
     * @param string $displayName
     * @param string $createdAt
     * @return User
     */
    public function getUser(string $displayName, string $createdAt): User
    {
        return new User(
            '12345',
            'ninja',
            $displayName,
            '',
            'partner',
            'Professional Gamer and Streamer',
            'https://example.com/ninja.jpg',
            'https://example.com/ninja-offline.jpg',
            365,
            $createdAt
        );
    }
}
