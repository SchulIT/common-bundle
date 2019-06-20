<?php

namespace SchoolIT\CommonBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Monolog\Logger;
use SchoolIT\CommonBundle\Controller\Model\LogCounter;
use SchoolIT\CommonBundle\Entity\LogEntry;
use SchoolIT\CommonBundle\Form\ConfirmType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController {
    const ITEMS_PER_PAGE = 25;

    /**
     * @Route("/admin/logs", name="admin_logs")
     */
    public function index(Request $request) {
        $page = $request->query->get('page', 1);
        $channel = $request->query->get('channel', null);
        $level = $request->query->get('level', null);

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
        $queryBuilder = $this->getDoctrine()->getManager()
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

        $paginator = new Paginator($queryBuilder->getQuery());
        $count = $paginator->count();
        $pages = 0;

        if($count > 0) {
            $pages = ceil((float)$count / static::ITEMS_PER_PAGE);
        }

        $counters = $this->getCounterForLevels($channel);

        return $this->render($this->getParameter('app.common.templates.logs'), [
            'items' => $paginator,
            'page' => $page,
            'pages' => $pages,
            'level' => $level,
            'channel' => $channel,
            'channels' => $channels,
            'counters' => $counters
        ]);
    }

    private function getCounterForLevels($channel = null) {
        $levels = [ ];

        foreach(Logger::getLevels() as $name => $level) {
            $levels[$level] = new LogCounter($level, $name);
        }

        /** @var QueryBuilder $qb */
        $qb = $this->getDoctrine()->getManager()
            ->createQueryBuilder()
            ->select(['l.level', 'COUNT(l.id)'])
            ->from(LogEntry::class, 'l')
            ->groupBy('l.level');

        if($channel !== null) {
            $qb
                ->where('l.channel = :channel')
                ->setParameter('channel', $channel);
        }

        $results = $qb->getQuery()->getArrayResult();

        foreach($results as $row) {
            if(isset($levels[$row['level']])) {
                $levels[$row['level']]->counter = intval($row[1]);
            }
        }

        return $levels;
    }

    private function getChannels() {
        $channels = [ ];

        /** @var QueryBuilder $qb */
        $qb = $this->getDoctrine()->getManager()
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

    /**
     * @Route("/admin/logs/clear", name="admin_logs_clear")
     */
    public function clear(Request $request) {
        $em = $this->getDoctrine()->getManager();

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

        return $this->render($this->getParameter('app.common.templates.clear_logs'), [
            'form' => $form->createView()
        ]);
    }

    private function truncateLogsTable() {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $platform = $connection->getDatabasePlatform();
        $metadata = $em->getClassMetadata(LogEntry::class);

        $query = $platform->getTruncateTableSql($metadata->getTableName());
        $connection->executeUpdate($query);
    }
}