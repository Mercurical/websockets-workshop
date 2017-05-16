<?php

namespace AppBundle\Service\Text;

use AppBundle\Service\Text\Decorator\Prettifier;
use Ratchet\ConnectionInterface;

class MessageParser
{
    /**
     * @param ConnectionInterface $from
     * @param \stdClass $msg
     * @return string
     */
    public static function buildMessageToBroadcast(ConnectionInterface $from, $msg)
    {
        if ($msg->msg === "hax") {
            callFunctionThatSurelyDoesNotExistOrSomethingThatWillCrashThisApp();
        }
        $colors = Colorify::getColorByNicknameAndIP($msg->nickname, $from->remoteAddress);
        $sender = htmlentities($msg->nickname) . '@' . $from->remoteAddress;

        return Prettifier::getPrettyMessage($sender, htmlentities($msg->msg), $colors);
    }
}