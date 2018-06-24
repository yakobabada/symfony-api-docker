<?php

namespace App\Tests\Entity;

use App\Entity\WebUrl;
use PHPUnit\Framework\TestCase;

class WebUrlTest extends TestCase
{
    public function testSameUrl()
    {
        $webUrl = new WebUrl();
        $webUrl->setUrl('http://test.com');

        $this->assertEquals('http://test.com', $webUrl->getUrl());
    }
}