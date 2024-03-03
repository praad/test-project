<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseApiTestCase extends WebTestCase
{
    public static function setUpBeforeClass(): void
    {
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);

        $schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $appFixtures = $container->get(AppFixtures::class);
        $appFixtures->load($entityManager);
    }

    protected function getApiResponseJson(string $endPoint): Response
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', $endPoint);

        return $client->getResponse();
    }

    protected function postApiResponseJson(string $endPoint, string $jsonData): Response
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('POST', $endPoint, [],[], ['CONTENT_TYPE' => 'application/json'], $jsonData);

        return $client->getResponse();
    }
}