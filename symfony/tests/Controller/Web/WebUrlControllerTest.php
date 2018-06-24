<?php

namespace App\Tests\Contoller\Web;

use App\Entity\WebUrl;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebUrlControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();

        parent::setUp();
    }

    public function testAccessIndexAction()
    {
        $this->client->request('GET', '/web-url');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAccessAddAction()
    {

        $this->client->request('GET', '/web-url/add');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAccessDeleteAction()
    {
        $kernel = static::bootKernel([]);

        $webUrl = (new WebUrl())->setUrl('https://www.shipserv.com/info/about-us');

        $entityManager = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        $entityManager->persist($webUrl);
        $entityManager->flush();
        $entityManager->refresh($webUrl);

        $this->client->request('GET', '/web-url/delete/' . $webUrl->getId());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}