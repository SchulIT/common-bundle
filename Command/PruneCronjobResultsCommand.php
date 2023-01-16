<?php

namespace SchulIT\CommonBundle\Command;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Shapecode\Bundle\CronBundle\Entity\CronJobResult;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'shapecode:cron:clean-up', description: 'Cleans the logs for each cron job.')]
class PruneCronjobResultsCommand extends Command {
    private EntityManagerInterface $em;

    private string $threshold = '-7 days';

    public function __construct(EntityManagerInterface $em, string $name = null) {
        parent::__construct($name);

        $this->em = $em;
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $output->writeln('Cleaning logs for all cron jobs');

        $threshold = (new DateTime())->modify($this->threshold);

        $repo = $this->em->getRepository(CronJobResult::class);
        $repo->deleteOldLogs($threshold);

        $output->writeln('Logs cleaned successfully');

        return CronJobResult::EXIT_CODE_SUCCEEDED;
    }
}