<?php

namespace SchulIT\CommonBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Shapecode\Bundle\CronBundle\Entity\CronJob;
use Shapecode\Bundle\CronBundle\Entity\CronJobResult;
use Shapecode\Bundle\CronBundle\Manager\CronJobManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class CronjobController extends AbstractController {

    /**
     * @Route("/cron", methods={"GET"})
     */
    public function run(KernelInterface $kernel): Response {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new StringInput('shapecode:cron:run');

        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true
        );
        $application->run($input, $output);

        $content = $output->fetch();
        $converter = new AnsiToHtmlConverter();

        return $this->render('@Common/cron/output.html.twig', [
            'output' => $converter->convert($content)
        ]);
    }

    /**
     * @Route("/admin/cron", name="admin_cronjobs")
     */
    public function index(EntityManagerInterface $manager): Response {
        $jobs = $manager->getRepository(CronJob::class)
            ->findAll();

        /** @var CronJobResult[] $results */
        $results = [ ];

        foreach($jobs as $job) {
            $results[$job->getCommand()] = $job->getResults()->last();
        }

        return $this->render('@Common/cron/index.html.twig', [
            'jobs' => $jobs,
            'results' => $results
        ]);
    }

    /**
     * @Route("/admin/cron/{id}/run", name="run_cronjob")
     */
    public function runJob(CronJob $job, CronJobManager $manager, KernelInterface $kernel): Response {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new StringInput(sprintf('shapecode:cron:process %d', $job->getId()));

        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true
        );
        $application->run($input, $output);

        $content = $output->fetch();
        $converter = new AnsiToHtmlConverter();

        $result = $job->getResults()->last();

        return $this->render('@Common/cron/run.html.twig', [
            'output' => $converter->convert($content),
            'result' => $result,
            'job' => $job
        ]);
    }

    /**
     * @Route("/admin/cron/{id}/reset", name="reset_cronjob")
     */
    public function resetJob(CronJob $job, EntityManagerInterface $manager): \Symfony\Component\HttpFoundation\RedirectResponse {
        while($job->getRunningInstances() > 0) {
            $job->decreaseRunningInstances();
        }
        $manager->persist($job);
        $manager->flush();

        $this->addFlash('success', 'cron.reset.success');

        return $this->redirectToRoute('admin_cronjobs');
    }

    /**
     * @Route("/admin/cron/{id}", name="show_cronjob")
     */
    public function showJob(CronJob $job, EntityManagerInterface $manager): Response {
        $results = $manager->getRepository(CronJobResult::class)
            ->createQueryBuilder('r')
            ->leftJoin('r.cronJob', 'c')
            ->where('c.id = :job')
            ->orderBy('r.createdAt', 'desc')
            ->setParameter('job', $job->getId())
            ->setMaxResults(25)
            ->getQuery()
            ->getResult();

        return $this->render('@Common/cron/show.html.twig', [
            'job' => $job,
            'results' => $results
        ]);
    }

    /**
     * @Route("/admin/cron/{job}/result/{id}", name="show_cronresult")
     */
    public function showResult(CronJobResult $result): Response {
        return $this->render('@Common/cron/result.html.twig', [
            'result' => $result
        ]);
    }
}