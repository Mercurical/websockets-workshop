<?php

namespace AppBundle\Command;

use AppBundle\Service\Chat\Chat;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class WsServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:ws-server:run')
            ->setDescription('Run WebSocket server')
            ->setHelp('Optional parameter: port [number] - sets port on which WebSocket server is ran (default 8080).')
            ->addArgument('port', InputArgument::OPTIONAL, 'WebSocket server port');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat($this->getContainer())
                )
            ),
            $input->getArgument('port') ?: 8080
        );

        $server->run();
    }
}