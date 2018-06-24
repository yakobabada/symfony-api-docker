<?php

namespace App\Util;

use App\Entity\WebUrl;
use Doctrine\Common\Persistence\ObjectManager;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebUrlCheckStatus
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    public $failedUrls = [];

    const HTTP_CODE_TO_CHECK = [
        Response::HTTP_INTERNAL_SERVER_ERROR,
        Response::HTTP_NOT_IMPLEMENTED,
        Response::HTTP_BAD_GATEWAY,
        Response::HTTP_SERVICE_UNAVAILABLE,
        Response::HTTP_GATEWAY_TIMEOUT,
        Response::HTTP_VERSION_NOT_SUPPORTED
    ];

    /**
     * @param Client $client
     * @param ObjectManager $objectManager
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, ObjectManager $objectManager, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->objectManager = $objectManager;
        $this->logger = $logger;
    }

    public function perform()
    {
        $urls = $this->loadUrls();
        $this->findUnavailableUrl($urls);

        if (count($this->failedUrls) !== 0) {
            $this->logErrors();
        }
    }

    /**
     * @return array
     */
    private function loadUrls(): ?array
    {
        return $this->objectManager->getRepository(WebUrl::class)->findAll();
    }

    /**
     * @param array $webUrls
     */
    private function findUnavailableUrl(array $webUrls)
    {
        foreach ($webUrls as $webUrl) {
            $response = null;

            try {
                $response = $this->client->request(Request::METHOD_GET, $webUrl->getUrl());
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse();
                }
            }

            if (in_array($response->getStatusCode(), self::HTTP_CODE_TO_CHECK)) {
                $this->failedUrls[] = $webUrl->getUrl();
            }
        }
    }

    /**
     * @param string $lineBreaker
     *
     * @return string
     */
    private function prepareMessageBody($lineBreaker = PHP_EOL): string
    {
        $message = '';

        foreach ($this->failedUrls as $url) {
            $message .= $url . $lineBreaker;
        }

        return $message;
    }

    private function logErrors()
    {
        foreach ($this->failedUrls as $url) {
            $this->logger->error($url);
        }
    }
}