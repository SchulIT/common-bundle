<?php

namespace SchoolIT\CommonBundle\Controller;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractCronjobController extends AbstractController {

    protected function runCommand(array $input) {
        try {
            $kernel = $this->get('kernel');
            $app = new Application($kernel);
            $app->setAutoExit(false);

            $input = new ArrayInput($input);

            $output = new BufferedOutput();
            $app->run($input, $output);

            $content = $output->fetch();
            $converter = new AnsiToHtmlConverter();
            $html = strip_tags($converter->convert($content));

            return new JsonResponse([
                'success' => true,
                'output' => $html
            ]);
        } catch(\Exception $e) {
            return new JsonResponse([
                'success' => false
            ], 500);
        }
    }
}