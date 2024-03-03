<?php

declare(strict_types=1);

namespace App\AutoMapper;

use App\Dto\UserRegistrationDto as UserModel;
use App\Dto\UserDto;
use App\Entity\User;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class UserMapper implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(User::class, UserDto::class)
            ->forMember('fullName', function (User $user) {
                return $user->getFirstName() . ' ' . $user->getLastName();
            });

        $config->registerMapping(UserModel::class, User::class)
            ->forMember('username', function (UserModel $user) {
                return $user->getEmail();
            });
    }
}