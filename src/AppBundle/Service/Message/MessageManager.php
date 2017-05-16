<?php

namespace AppBundle\Service\Message;

use AppBundle\Entity\Message;
use Doctrine\ORM\EntityManager;

class MessageManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * MessageManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function saveMessage(string $nickname, string $msg, string $ip)
    {
        $message = new Message();
        $message->setIp($ip);
        $message->setMessage($msg);
        $message->setNickname($nickname);
        $message->setTimestamp(new \DateTime());

        $this->em->persist($message);
        $this->em->flush();
    }
}