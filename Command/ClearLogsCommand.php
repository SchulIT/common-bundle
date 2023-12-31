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

    public function __construct(private readonly DateHelper $dateHelper, private readonly EntityManagerInterface $em, string $name = null) {
        parent::__construct($name);
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $threshold = $this->dateHelper->getToday()->modify(sprintf('-%d days', static::KeepLogsForDays));

        $style = new SymfonyStyle($input, $output);
        $style->section(sprintf('Lösche Log-Einträge, die älter als %s sind...', $threshold->format('c')));

        $count = $this->em->createQueryBuilder()
            ->delete(LogEntry::class, 'l')
            ->where('l.time < :threshold')
            ->setParameter('threshold', $threshold)
            ->getQuery()
            ->execute();

        $style->success(sprintf('%d Eintrag/Einträge gelöscht', $count));

        return Command::SUCCESS;
    }
}