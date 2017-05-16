<?php

namespace AppBundle\Service\Chat;

use AppBundle\Service\Client\ClientManager;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use AppBundle\Service\Text\MessageParser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->clients = new \SplObjectStorage;
    }

    /**
     * Triggers when a new users connects to the server
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        // Let's allow only one client per IP address join the chat
//        foreach ($this->clients as $client) {
//            if ($client->remoteAddress == $conn->remoteAddress) {
//                $conn->send(json_encode(['event' => 'error', 'data' => 'Only one connection per IP address!']));
//                $conn->close();
//
//                return;
//            }
//        }

        // Add client to array containing all active connections
        $this->clients->attach($conn);

        // Build and send message to all connected clients with connected IP addresses
        $ipList = ClientManager::getClientIPListToBroadcast($this->clients);

        foreach ($this->clients as $client) {
            $client->send(json_encode(['event' => 'ips', 'data' => $ipList, 'action' => 'joined', 'ip' => $conn->remoteAddress]));
        }
    }

    /**
     * Triggers when user sends message to the server
     * @param ConnectionInterface $from
     * @param string $msg
     * @throws \Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $message = json_decode($msg);

        // Make sure valid JSON is received
        if (!is_object($message)) {
            return;
        }

        // Just for example purposes to not set second server, avoid this
        if ($message->event == "example") {
            $from->send(json_encode(['event' => 'example', 'color' => "black", 'number' => $message->number]));

            return;
        }

        if ($message->msg == "dupa") {
            throw new \Exception("I can't stand such a sickening language. Behave yourself!");
        }

        // Build and send message to all sent by one of the connected clients
        $msgToSend = MessageParser::buildMessageToBroadcast($from, $message);

        if ($msgToSend) {
            foreach ($this->clients as $client) {
                $client->send(json_encode(['event' => 'message', 'data' => $msgToSend]));
            }

            $messageManager = $this->container->get('app.message_manager');
            $messageManager->saveMessage($message->nickname, $message->msg, $from->remoteAddress);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Remove client from active connection collection upon his disconnection
        $this->clients->detach($conn);

        // Build and send message to all connected clients with connected IP addresses
        $ipList = ClientManager::getClientIPListToBroadcast($this->clients);

        foreach ($this->clients as $client) {
            $client->send(json_encode(['event' => 'ips', 'data' => $ipList, 'action' => 'left', 'ip' => $conn->remoteAddress]));
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // An exception occured...
        $conn->send(json_encode(['event' => 'error', 'data' => $e->getMessage()]));
    }
}