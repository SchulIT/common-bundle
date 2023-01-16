<?php

namespace SchulIT\CommonBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use SchulIT\CommonBundle\Entity\LogEntry;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:clear-logs', description: 'Clears all logs older than seven days.')]
class ClearLogsCommand extends Command {

    private const KeepLogsForDays = 7;

    private DateHelper $dateHelper;
    private EntityManagerInterface $em;

    public function __construct(DateHelper $dateHelper, EntityManagerInterface $em, string $name = null) {
        parent::__construct($name);

        $this->dateHelper = $dateHelper;
        $this->em = $em;
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $threshold = $this->dateHelper->getToday()->modify(sprintf('-%d days', static::KeepLogsForDays));

        $style = new SymfonyStyle($input, $output);
        $style->section(sprintf('Delete log entries older than %s...', $threshold->format('c')));

        $count = $this->em->createQueryBuilder()
            ->delete(LogEntry::class, 'l')
            ->where('l.time < :threshold')
            ->setParameter('threshold', $threshold)
            ->getQuery()
            ->execute();

        $style->success(sprintf('Deleted %d entries', $count));

        return 0;
    }
}