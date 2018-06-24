<?php

namespace App\Tests\Util;

use App\Entity\WebUrl;
use App\Log\System;
use App\Message\Email;
use App\Util\WebUrlCheckStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class WebUrlCheckStatusTest extends TestCase
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ServiceEntityRepositoryInterface
     */
    private $webUrlRepository;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Logger
     */
    private $log;

    const HTTP_CODE_TO_CHECK = [

    ];

    public function setUp()
    {
        parent::setUp();

        $this->client = $this->createMock(Client::class);

        $this->webUrlRepository = $this->createMock(ObjectRepository::class);
        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->log = $this->createMock(Logger::class);

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->webUrlRepository);
    }

    public function testNotFoundPage()
    {
        $webUrls = [
            (new WebUrl())
                ->setUrl('https://www.shipserv.com/info/about-us')
        ];

        $response = new Response(
            \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR,
            ['Content-Length' => 2],
            'Page not found'
        );

        $this->client->expects($this->any())
            ->method('request')
            ->willReturn($response);

        $this->webUrlRepository->expects($this->any())
            ->method('findAll')
            ->willReturn($webUrls);

        $webUrlCheckStatusUtil = new WebUrlCheckStatus($this->client, $this->objectManager, $this->log);
        $webUrlCheckStatusUtil->perform();

        $this->assertContains('https://www.shipserv.com/info/about-us', $webUrlCheckStatusUtil->failedUrls);
    }

    public function testAvailablePage()
    {
        $webUrls = [
            (new WebUrl())
                ->setUrl('https://www.shipserv.com/info/about-us')
        ];

        $response = new Response(
            \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            ['Content-Length' => 2],
            'ABOUT US'
        );

        $this->client->expects($this->any())
            ->method('request')
            ->willReturn($response);

        $this->webUrlRepository->expects($this->any())
            ->method('findAll')
            ->willReturn($webUrls);

        $webUrlCheckStatusUtil = new WebUrlCheckStatus($this->client, $this->objectManager, $this->log);
        $webUrlCheckStatusUtil->perform();

        $this->assertCount(0, $webUrlCheckStatusUtil->failedUrls);
    }
}