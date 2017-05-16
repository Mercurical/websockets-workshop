<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Service\Text\Colorify;
use AppBundle\Service\Text\Decorator\Prettifier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('AppBundle:Message')->findBy([], ['timestamp' => 'DESC'], 10);

        $parsedMessages = [];

        /**
         * @var $message Message
         */
        foreach ($messages as $message) {
            $colors = Colorify::getColorByNicknameAndIP($message->getNickname(), $message->getIp());
            $msg = Prettifier::getPrettyMessage(
                $message->getNickname().'@'.$message->getIp(),
                $message->getMessage(),
                $colors
            );
            $parsedMessages[] = $msg;
        }

        return $this->render('default/index.html.twig', [
            'messages' => array_reverse($parsedMessages),
        ]);
    }

    /**
     * @Route("/example")
     */
    public function exampleAction(Request $request)
    {
        return $this->render('default/example_socket_vs_ajax.html.twig', []);
    }

    /**
     * @Route("/api")
     */
    public function apiAction(Request $request)
    {
        $number = $request->request->get("number");

        return new JsonResponse(['color' => "red", 'number' => $number]);
    }
}
