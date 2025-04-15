<?php

namespace TwitchAnalytics\Infrastructure\Repositories;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use TwitchAnalytics\Domain\Models\User;
use TwitchAnalytics\Infrastructure\ApiClient\TwitchApiClientInterface;


class ApiUserRepositoryTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */

    public function findByDisplayNameWithValidUser(): void
    {
        $mockUser = new User(
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
        );

        $apiClient = $this->createMock(TwitchApiClientInterface::class);
        $apiClient->method('getUserByDisplayName')->with('Ninja')->willReturn($mockUser);

        $repository = new ApiUserRepository($apiClient);

        $result = $repository->findByDisplayName('Ninja');


        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('Ninja', $result->getDisplayName());
    }


    /**
     * @test
     * @throws Exception
     */
    public function findByDisplayNameWithInvalidUser(): void
    {
        $apiClient = $this->createMock(TwitchApiClientInterface::class);
        $apiClient->method('getUserByDisplayName')->with('InvalidUser')->willReturn(null);

        $repository = new ApiUserRepository($apiClient);

        $result = $repository->findByDisplayName('InvalidUser');

        $this->assertNull($result);
    }

}
