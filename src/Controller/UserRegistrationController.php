<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Dto\UserRegistrationDto;
use App\Entity\User;
use App\Response\ApiUser;
use AutoMapperPlus\AutoMapperInterface;
use Doctrine\Persistence\ManagerRegistry;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[Route('/api', name: 'api_')]
class UserRegistrationController extends AbstractController
{
    public const ERROR_USER_REGISTRATION = 'Registration was not successfully';

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: ApiUser::class)
    )]
    #[OA\RequestBody(
        description: "User data.",
        required: true,
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                ref: new Model(type: UserRegistrationDto::class)
            )
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
    )]
    #[Route('/register', name: 'register', methods: 'post')]
    public function register(
        ManagerRegistry $doctrine,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        AutoMapperInterface $autoMapper
    ): JsonResponse {
        $entityManager = $doctrine->getManager();

        $userRegistration = $serializer->deserialize($request->getContent(), UserRegistrationDto::class, 'json');
        $user = $autoMapper->map($userRegistration, User::class);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidationFailedException(self::ERROR_USER_REGISTRATION, $errors);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json((new ApiUser())->setUser($autoMapper->map($user, UserDto::class))->setSuccess(true));
    }
}