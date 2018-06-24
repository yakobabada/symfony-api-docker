<?php

namespace App\Command;

use App\Util\WebUrlCheckStatus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebUrlCheckStatusCommand extends Command
{
    /**
     * @var WebUrlCheckStatus
     */
    private $webUrlCheckStatus;

    /**
     * @param WebUrlCheckStatus $webUrlCheckStatus
     */
    public function __construct(WebUrlCheckStatus $webUrlCheckStatus)
    {
        $this->webUrlCheckStatus = $webUrlCheckStatus;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:check-web-url-status')
            ->setDescription('Checks web urls statuses, log and send email if website is not available.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Start:',
            '============',
            '',
        ]);

        $this->webUrlCheckStatus->perform();

        $output->writeln([
            '',
            '============',
            'End:'
        ]);
    }
}