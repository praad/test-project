<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Repository\UserRepository;
use App\Response\ApiUser;
use App\Response\ApiUsers;
use AutoMapperPlus\AutoMapperInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use OpenApi\Attributes as OA;

#[Route('/api', name: 'api_')]
class UsersController extends AbstractController
{
    public const ERROR_USER_NOT_FOUND = 'User (id: %s) not found';

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: ApiUsers::class)
    )]
    #[Route('/users', name: 'users', methods: 'get')]
    public function list(
        UserRepository $userRepository,
        AutoMapperInterface $autoMapper
    ): JsonResponse {
        $users = $autoMapper->mapMultiple($userRepository->findAll(), UserDto::class);

        return $this->json((new ApiUsers())->setUsers($users)->setSuccess(true));
    }

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: ApiUser::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found response',
        content: new Model(type: ApiUsers::class)
    )]
    #[Route('/users/{id}', name: 'show_user', methods: 'get')]
    public function show(
        string $id,
        UserRepository $userRepository,
        AutoMapperInterface $autoMapper
    ): JsonResponse {
        $user = $autoMapper->map($userRepository->findOneById((int) $id), UserDto::class);

        if (!$user) {
            throw new ResourceNotFoundException(sprintf(self::ERROR_USER_NOT_FOUND, $id));
        }

        return $this->json(
            (new ApiUser())->setUser($user)->setSuccess(true)
        );
    }
}