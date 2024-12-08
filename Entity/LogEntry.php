<?php

namespace SchulIT\CommonBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'log')]
class LogEntry {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $channel;

    #[ORM\Column(type: 'integer')]
    private int $level;

    #[ORM\Column(type: 'text')]
    private string $message;

    #[ORM\Column(type: 'datetime')]
    private DateTime $time;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $details = null;

    public function getId(): int {
        return $this->id;
    }

    public function getChannel(): string {
        return $this->channel;
    }

    public function setChannel(string $channel): LogEntry {
        $this->channel = $channel;
        return $this;
    }

    public function getLevel(): int {
        return $this->level;
    }

    public function setLevel(int $level): LogEntry {
        $this->level = $level;
        return $this;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message): LogEntry {
        $this->message = $message;
        return $this;
    }

    public function getTime(): DateTime {
        return $this->time;
    }

    public function setTime(DateTime $time): LogEntry {
        $this->time = $time;
        return $this;
    }

    public function getDetails(): ?array {
        return $this->details;
    }

    public function setDetails(?array $details): LogEntry {
        $this->details = $details;
        return $this;
    }
}