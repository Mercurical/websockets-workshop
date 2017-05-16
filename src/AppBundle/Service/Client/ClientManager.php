<?php

namespace AppBundle\Service\Client;

use AppBundle\Service\Text\Decorator\Prettifier;

class ClientManager
{
    /**
     * @param \SplObjectStorage $clients
     * @return string
     */
    public static function getClientIPListToBroadcast(\SplObjectStorage $clients)
    {
        $list = [];

        foreach ($clients as $client) {
            $list[] = $client->remoteAddress;
        }

        return Prettifier::getPrettyClientIPList($list);
    }
}