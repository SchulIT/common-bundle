<?php

namespace SchulIT\CommonBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Monolog\Logger;
use SchulIT\CommonBundle\Controller\Model\Message;
use SchulIT\CommonBundle\Entity\LogEntry;
use SchulIT\CommonBundle\Helper\DateHelper;
use SchulIT\CommonBundle\SwiftMailer\EmailSpoolHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController {

    private const LOGGER_CHANNEL = 'mailer';
    private const ERRORS_LOG_LEVEL = Logger::WARNING;
    private const ERRORS_PAST_DAYS = 7;
    private const MAILS_PER_PAGE = 25;

    private $spoolHelper;
    private $dateHelper;
    private $mailTemplate;

    public function __construct(EmailSpoolHelper $spoolHelper, DateHelper $dateHelper) {
        $this->spoolHelper = $spoolHelper;
        $this->dateHelper = $dateHelper;
    }

    /**
     * @Route("/admin/mails", name="admin_mails")
     */
    public function index(Request $request) {
        $numErrors = $this->getNumberOfMailLogEntriesWithNoSuccess();
        $messages = $this->spoolHelper->getSpooledMessages();

        $page = $request->get('page', null);
        $pages = ceil(count($messages) / static::MAILS_PER_PAGE);

        if($page === null || !is_numeric($page) || $page <= 0 || $page > $pages) {
            $page = 1;
        }

        $offset = ($page - 1) * static::MAILS_PER_PAGE;
        $messages = array_slice($messages, $offset, static::MAILS_PER_PAGE);
        $messages = $this->createMessageModels($messages);

        return $this->render('@Common/mail/index.html.twig', [
            'numErrors' => $numErrors,
            'numErrorsDays' => static::ERRORS_PAST_DAYS,
            'messages' => $messages,
            'page' => $page,
            'pages' => $pages,
            'channel' => static::LOGGER_CHANNEL
        ]);
    }

    private function createMessageModels(array $swiftMessages) {
        $messages = [ ];

        foreach($swiftMessages as $swiftMessage) {
            $messages[] = Message::fromSwiftMessage($swiftMessage);
        }

        return $messages;
    }

    /**
     * @return int
     */
    private function getNumberOfMailLogEntriesWithNoSuccess(): int {
        $manager = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $manager->createQueryBuilder();
        $queryBuilder->select('COUNT(l.id)')
            ->from(LogEntry::class, 'l')
            ->where('l.channel = :channel')
            ->andWhere('l.time > :time')
            ->andWhere('l.level > :level')
            ->setParameter('channel', static::LOGGER_CHANNEL)
            ->setParameter('level', static::ERRORS_LOG_LEVEL)
            ->setParameter('time', $this->dateHelper->getToday()->modify(sprintf('-%ddays', static::ERRORS_PAST_DAYS)));

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}