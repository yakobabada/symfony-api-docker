<?php

namespace App\Tests\Controller\Api;

use App\Entity\WebUrl;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WebUrlControllerTest extends KernelTestCase
{
    /**
     * @var Client
     */
    private $client;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::bootKernel();
    }

    public function setUp()
    {
        parent::setUp();

        $this->client = new Client([
            'base_uri' => 'http://0.0.0.0:8000/test.php/'
        ]);

        $this->purgeDatabase();
    }

    protected function tearDown()
    {
        // purposefully not calling parent class, which shuts down the kernel
    }

    protected function getService($id)
    {
        return self::$kernel->getContainer()
            ->get($id);
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getService('doctrine')->getManager());
        $purger->purge();
    }

    public function testPost()
    {
        $response = $this->client->post('api/web-url', [
            'json' => [
                'url' => 'https://www.shipserv.com/info/about-us'
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('https://www.shipserv.com/info/about-us', $result);
    }

    public function testPostWithInvalidUrl()
    {
        $response = null;

        try {
            $response = $this->client->post('api/web-url', [
                'json' => [
                    'url' => 'test'
                ]
            ]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            }
        }

        $result = json_decode($response->getBody(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('There was a validation error', $result);
    }

    public function testList()
    {
        $webUrl = (new WebUrl())->setUrl('https://www.shipserv.com/info/about-us');

        $this->getService('doctrine')->getManager()->persist($webUrl);
        $this->getService('doctrine')->getManager()->flush();

        $response = $this->client->get('api/web-url');

        $result = json_decode($response->getBody(true), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $result);
    }

    public function testGet()
    {
        $webUrl = (new WebUrl())->setUrl('https://www.shipserv.com/info/about-us');

        $this->getService('doctrine')->getManager()->persist($webUrl);
        $this->getService('doctrine')->getManager()->flush();
        $this->getService('doctrine')->getManager()->refresh($webUrl);

        $response = $this->client->get('api/web-url/' . $webUrl->getId());

        $result = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('https://www.shipserv.com/info/about-us', $result);
    }

    public function testDelete()
    {
        $webUrl = (new WebUrl())->setUrl('https://www.shipserv.com/info/about-us');

        $this->getService('doctrine')->getManager()->persist($webUrl);
        $this->getService('doctrine')->getManager()->flush();
        $this->getService('doctrine')->getManager()->refresh($webUrl);

        $response = $this->client->delete('api/web-url/' . $webUrl->getId());

        $this->assertEquals(204, $response->getStatusCode());
    }
}