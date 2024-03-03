<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\DataFixtures\AppFixtures;
use App\Dto\UserDto;
use App\Dto\UsersDto;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends BaseApiTestCase
{
    public function testListUsers(): void
    {
        $serializer = self::getContainer()->get(SerializerInterface::class);

        $response = $this->getApiResponseJson('api/users');
        /** @var UsersDto $responseDto */
        $responseDto = $serializer->deserialize($response->getContent(), UsersDto::class, 'json');

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(AppFixtures::SIZE, $responseDto->getUsers());
    }

    public function testGetUser(): void
    {
        $serializer = self::getContainer()->get(SerializerInterface::class);

        $response = $this->getApiResponseJson('api/users/1');
        /** @var UserDto $responseDto */
        $responseDto = $serializer->deserialize($response->getContent(), UserDto::class, 'json');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('test-user-1', $responseDto->getUsername());
    }
}