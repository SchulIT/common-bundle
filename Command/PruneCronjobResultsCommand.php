<?php

namespace SchulIT\CommonBundle\Command;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Shapecode\Bundle\CronBundle\Entity\CronJobResult;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PruneCronjobResultsCommand extends Command {
    private $em;

    private $threshold = '-7 days';

    public function __construct(EntityManagerInterface $em, string $name = null) {
        parent::__construct($name);

        $this->em = $em;
    }

    public function configure() {
        $this->setName('shapecode:cron:result:prune');

        $this->setAliases([
            'shapecode:cron:logs:clean-up',
        ]);

        $this->setDescription('Cleans the logs for each cron job.');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('Cleaning logs for all cron jobs');

        $threshold = (new DateTime())->modify($this->threshold);

        $repo = $this->em->getRepository(CronJobResult::class);
        $repo->deleteOldLogs($threshold);

        $output->writeln('Logs cleaned successfully');

        return CronJobResult::EXIT_CODE_SUCCEEDED;
    }
}