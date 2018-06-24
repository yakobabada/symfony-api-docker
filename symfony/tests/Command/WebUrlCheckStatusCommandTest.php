<?php

namespace App\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WebUrlCheckStatusCommandTest extends KernelTestCase
{
    public function testOutputCommand()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);

        $command = $application->find('app:check-web-url-status');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName()
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Start:', $output);
    }
}