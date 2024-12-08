<?php

namespace SchulIT\CommonBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use SchulIT\CommonBundle\Controller\Model\LogCounter;
use SchulIT\CommonBundle\Entity\LogEntry;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController {
    const ITEMS_PER_PAGE = 25;

    public function __construct(private readonly EntityManagerInterface $em, private readonly LoggerInterface $logger) { }

    #[Route('/admin/logs', name: 'admin_logs')]
    public function index(Request $request): Response {
        $page = $request->query->get('page', 1);
        $channel = $request->query->get('channel', null);
        $level = $request->query->get('level', null);
        $username = $request->query->get('username', null);
        $requestId = $request->query->get('request', null);

        if(!is_numeric($page) || $page < 1) {
            $page = 1;
        }

        if($level !== null && !is_numeric($level)) {
            $level = 200;
        }

        $channels = $this->getChannels();
        $channel = $request->query->get('channel', null);

        if(!in_array($channel, $channels)) {
            $channel = null;
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->em
            ->createQueryBuilder()
            ->select('l')
            ->from(LogEntry::class, 'l')
            ->orderBy('l.time', 'desc')
            ->setFirstResult(($page - 1) * static::ITEMS_PER_PAGE)
            ->setMaxResults(static::ITEMS_PER_PAGE);

        if($level !== null) {
            $queryBuilder
                ->andWhere('l.level = :level')
                ->setParameter('level', $level);
        }

        if(!empty($channel)) {
            $queryBuilder
                ->andWhere('l.channel = :channel')
                ->setParameter('channel', $channel);
        }

        if(!empty($requestId)) {
            if($this->em->getConfiguration()->getCustomStringFunction('JSON_VALUE') !== null) {
                $queryBuilder->andWhere("JSON_VALUE(l.details, '$.request_id') = :request")
                    ->setParameter('request', $requestId);
            } else {
                $this->logger->notice('Der SQL-Befehl JSON_VALUE ist nicht verfügbar in Doctrine. Bitte die Bibliothek installieren (https://github.com/ScientaNL/DoctrineJsonFunctions) und einrichten');
            }
        }

        if(!empty($username)) {
            if($this->em->getConfiguration()->getCustomStringFunction('JSON_VALUE') !== null) {
                $queryBuilder->andWhere("JSON_VALUE(l.details, '$.username') = :username")
                    ->setParameter('username', $username);
            } else {
                $this->logger->notice('Der SQL-Befehl JSON_VALUE ist nicht verfügbar in Doctrine. Bitte die Bibliothek installieren (https://github.com/ScientaNL/DoctrineJsonFunctions) und einrichten');
            }
        }

        $paginator = new Paginator($queryBuilder->getQuery());
        $count = $paginator->count();
        $pages = 0;

        if($count > 0) {
            $pages = ceil((float)$count / static::ITEMS_PER_PAGE);
        }

        $counters = $this->getCounterForLevels($channel, $username, $requestId);

        return $this->render('@Common/logs/index.html.twig', [
            'items' => $paginator,
            'page' => $page,
            'pages' => $pages,
            'level' => $level,
            'channel' => $channel,
            'channels' => $channels,
            'counters' => $counters,
            'username' => $username,
            'request_id' => $requestId
        ]);
    }

    private function getCounterForLevels($channel = null, ?string $username = null, ?string $requestId = null): array {
        $levels = [ ];

        foreach(Level::cases() as $level) {
            $levels[$level->value] = new LogCounter($level->value, $level->name);
        }

        /** @var QueryBuilder $qb */
        $qb = $this->em
            ->createQueryBuilder()
            ->select(['l.level', 'COUNT(l.id)'])
            ->from(LogEntry::class, 'l')
            ->groupBy('l.level');

        if($channel !== null) {
            $qb
                ->where('l.channel = :channel')
                ->setParameter('channel', $channel);
        }

        if(!empty($username) && $this->em->getConfiguration()->getCustomStringFunction('JSON_VALUE') !== null) {
            $qb->andWhere("JSON_VALUE(l.details, '$.username') = :username")
                ->setParameter('username', $username);
        }

        if(!empty($requestId) && $this->em->getConfiguration()->getCustomStringFunction('JSON_VALUE') !== null) {
            $qb->andWhere("JSON_VALUE(l.details, '$.request_id') = :request")
                ->setParameter('request', $requestId);
        }

        $results = $qb->getQuery()->getArrayResult();

        foreach($results as $row) {
            if(isset($levels[$row['level']])) {
                $levels[$row['level']]->counter = intval($row[1]);
            }
        }

        return $levels;
    }

    private function getChannels(): array {
        $channels = [ ];

        /** @var QueryBuilder $qb */
        $qb = $this->em
            ->createQueryBuilder()
            ->select('l.channel')
            ->from(LogEntry::class, 'l')
            ->groupBy('l.channel')
            ->orderBy('l.channel', 'asc');

        $results = $qb->getQuery()->getArrayResult();

        foreach($results as $row) {
            $channels[] = $row['channel'];
        }

        return $channels;
    }

    #[Route('/admin/logs/clear', name: 'admin_logs_clear')]
    public function clear(Request $request): Response {
        $form = $this->createForm(ConfirmType::class, null, [
            'message' => 'logs.clear.confirm',
            'header' => 'logs.clear.header'
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->truncateLogsTable();

            $this->addFlash('success', 'logs.clear.success');
            return $this->redirectToRoute('admin_logs');
        }

        return $this->render('@Common/logs/clear.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function truncateLogsTable(): void {
        $connection = $this->em->getConnection();
        $platform = $connection->getDatabasePlatform();
        $metadata = $this->em->getClassMetadata(LogEntry::class);

        $query = $platform->getTruncateTableSql($metadata->getTableName());
        $connection->executeUpdate($query);
    }
}