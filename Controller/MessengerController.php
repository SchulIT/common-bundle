<?php

namespace SchulIT\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransport;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Routing\Annotation\Route;

class MessengerController extends AbstractController {

    private const Limit = 100;

    public function __construct(private readonly TransportInterface $transport) { }

    #[Route('/admin/messenger', name: 'admin_messenger')]
    public function index(): Response {
        $envelopes = $this->transport->all(static::Limit);
        $count = $this->transport->getMessageCount();

        return $this->render('@Common/messenger/index.html.twig', [
            'envelopes' => $envelopes,
            'count' => $count
        ]);
    }
}