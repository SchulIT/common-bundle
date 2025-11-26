<?php

namespace SchulIT\CommonBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use SchulIT\CommonBundle\Entity\LogEntry;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:clear-logs', description: 'Clears all logs older than seven days.')]
class ClearLogsCommand {

    private const int KeepLogsForDays = 7;

    public function __construct(
        private readonly DateHelper $dateHelper,
        private readonly EntityManagerInterface $em) { }

    public function __invoke(SymfonyStyle $style): int {
        $threshold = $this->dateHelper->getToday()->modify(sprintf('-%d days', static::KeepLogsForDays));
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