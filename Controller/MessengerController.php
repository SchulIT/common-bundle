<?php

namespace SchulIT\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class MessengerController extends AbstractController {

    #[Route('/admin/messenger', name: 'admin_messenger')]
    public function index(KernelInterface $kernel): Response {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new StringInput('messenger:stats');
        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            false
        );
        $application->run($input, $output);

        $content = $output->fetch();

        return $this->render('@Common/messenger/index.html.twig', [
            'output' => $content
        ]);
    }
}