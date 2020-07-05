<?php

namespace SchulIT\CommonBundle\Controller\Model;

class Message {
    private $id;
    private $subject;
    private $date;
    private $from;
    private $to;

    public function getId(): string {
        return $this->id;
    }

    public function getSubject(): string {
        return $this->subject;
    }

    public function getDate(): \DateTimeInterface {
        return $this->date;
    }

    /**
     * @return string[]
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * @return string[]
     */
    public function getTo() {
        return $this->to;
    }

    public static function fromSwiftMessage(\Swift_Message $message) {
        $model = new static();

        $model->id = $message->getId();
        $model->subject = $message->getSubject();
        $model->date = $message->getDate();

        $headers = $message->getHeaders();

        $model->from = static::getMailboxesFromHeaders($headers, 'from');
        $model->to = static::getMailboxesFromHeaders($headers, 'to');

        return $model;
    }

    /**
     * @param array $headers
     * @return string[]
     */
    private static function getMailboxesFromHeaders(\Swift_Mime_SimpleHeaderSet $headerSet, $name) {
        $mailboxes = [ ];

        for($i = 0; $headerSet->has($name, $i); $i++) {
            $header = $headerSet->get($name, $i);
            $mailboxes = array_merge($mailboxes, static::getMailboxFromHeader($header));
        }

        return $mailboxes;
    }
    
    /**
     * @param \Swift_Mime_Headers_MailboxHeader $header
     * @return string[]
     */
    private static function getMailboxFromHeader(\Swift_Mime_Headers_MailboxHeader $header) {
        return $header->getNameAddressStrings();
    }
}