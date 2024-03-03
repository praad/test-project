<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Dto\UserRegistrationDto;
use App\Response\ApiError;
use App\Response\ApiUser;
use Generator;
use Symfony\Component\Serializer\SerializerInterface;

final class UserRegistrationControllerTest extends BaseApiTestCase
{
    /**
     * @dataProvider validUserRegistrationDtoProvider
     */
    public function testPostUser(UserRegistrationDto $userRegistrationDto): void
    {
        /** @var SerializerInterface $serializer */
        $serializer = self::getContainer()->get(SerializerInterface::class);

        $userRegistrationJson = $serializer->serialize($userRegistrationDto, 'json');

        $response = $this->postApiResponseJson('api/register', $userRegistrationJson);

        $apiUserResponse = $serializer->deserialize($response->getContent(), ApiUser::class, 'json');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame($userRegistrationDto->getEmail(), $apiUserResponse->getuser()->getEmail());
        self::assertSame($userRegistrationDto->getEmail(), $apiUserResponse->getuser()->getUsername());
        self::assertSame($userRegistrationDto->getLastName(), $apiUserResponse->getuser()->getLastName());
        self::assertSame($userRegistrationDto->getFirstName(), $apiUserResponse->getuser()->getFirstName());
        self::assertSame(
            $userRegistrationDto->getFirstName() . ' ' . $userRegistrationDto->getLastName(),
            $apiUserResponse->getuser()->getFullName()
        );
        self::assertTrue($apiUserResponse->getSuccess());
    }

    /**
     * @dataProvider invalidUserRegistrationDtoProvider
     */

    public function testUserValidation(UserRegistrationDto $userRegistrationDto): void
    {
        /** @var SerializerInterface $serializer */
        $serializer = self::getContainer()->get(SerializerInterface::class);

        $userRegistrationJson = $serializer->serialize($userRegistrationDto, 'json');

        $response = $this->postApiResponseJson('api/register', $userRegistrationJson);

        $apiError = $serializer->deserialize($response->getContent(), ApiError::class, 'json');

        self::assertSame(400, $response->getStatusCode());
        self::assertFalse($apiError->getSuccess());
    }

    public function invalidUserRegistrationDtoProvider(): Generator
    {
        yield [
            (new UserRegistrationDto())
                ->setPassword('')
                ->setEmail('new-test-email@gmail.com')
                ->setFirstName('test')
                ->setLastName('test')
        ];
        yield [
            (new UserRegistrationDto())
                ->setPassword('test')
                ->setEmail('')
                ->setFirstName('test')
                ->setLastName('test')
        ];
        yield [
            (new UserRegistrationDto())
                ->setPassword('test')
                ->setEmail('new-test-email@gmail.com')
                ->setFirstName('')
                ->setLastName('test')
        ];
        yield [
            (new UserRegistrationDto())
                ->setPassword('test')
                ->setEmail('new-test-email@gmail.com')
                ->setFirstName('test')
                ->setLastName('')
        ];
    }

    public static function validUserRegistrationDtoProvider(): Generator
    {
        yield [
            (new UserRegistrationDto())
                ->setPassword('test')
                ->setEmail('new-test-email@gmail.com')
                ->setFirstName('test')
                ->setLastName('test')
        ];
        yield [
            (new UserRegistrationDto())
                ->setPassword('test1')
                ->setEmail('new-test-email1@gmail.com')
                ->setFirstName('test1')
                ->setLastName('test2')
        ];
    }
}