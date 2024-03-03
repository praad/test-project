<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\DataFixtures\AppFixtures;
use App\ErrorMessages\ApiErrorMessages;
use App\Response\ApiError;
use App\Response\ApiUser;
use App\Response\ApiUsers;
use Generator;
use Symfony\Component\Serializer\SerializerInterface;

final class UserControllerTest extends BaseApiTestCase
{
    public function testListUsers(): void
    {
        /** @var SerializerInterface $serializer */
        $serializer = self::getContainer()->get(SerializerInterface::class);

        $response = $this->getApiResponseJson('api/users');
        /** @var ApiUsers $apiUsersResponse */
        $apiUsersResponse = $serializer->deserialize($response->getContent(), ApiUsers::class, 'json');

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(AppFixtures::SIZE, $apiUsersResponse->getUsers());
        self::assertTrue($apiUsersResponse->getSuccess());
    }

    public function testGetUser(): void
    {
        /** @var SerializerInterface $serializer */
        $serializer = self::getContainer()->get(SerializerInterface::class);

        $response = $this->getApiResponseJson('api/users/1');
        /** @var ApiUser $apiUserResponse */
        $apiUserResponse = $serializer->deserialize($response->getContent(), ApiUser::class, 'json');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('test-user-1', $apiUserResponse->getuser()->getUsername());
        self::assertTrue($apiUserResponse->getSuccess());
    }

    /**
     * @dataProvider wrongIdProvider
     */
    public function testUserNotFound(mixed $wrongId): void
    {
        /** @var SerializerInterface $serializer */
        $serializer = self::getContainer()->get(SerializerInterface::class);

        $response = $this->getApiResponseJson("api/users/$wrongId");
        /** @var ApiError $apiErrorResponse */
        $apiErrorResponse = $serializer->deserialize($response->getContent(), ApiError::class, 'json');

        self::assertSame(404, $response->getStatusCode());
        self::assertContains(ApiErrorMessages::RESOURCE_NOT_FOUND_ERROR->value, $apiErrorResponse->getErrors());
        self::assertFalse($apiErrorResponse->getSuccess());
    }

    public static function wrongIdProvider(): Generator
    {
        yield ['wrongId'];
        yield [9999999];
        yield ['9999999'];
    }
}