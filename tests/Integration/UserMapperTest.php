<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Dto\UserDto;
use App\Entity\User;
use AutoMapperPlus\AutoMapperInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserMapperTest extends WebTestCase
{
    public function testMap(): void
    {
        $container = static::getContainer();
        $autoMapper = $container->get(AutoMapperInterface::class);

        /** @var UserDto $userDto */
        $userDto = $autoMapper->map((new User())->setFirstName('firstName')->setLastName('lastName'), UserDto::class);

        self::assertEquals('firstName lastName', $userDto->getFullName());
    }
}