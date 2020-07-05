<?php

namespace SchulIT\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 */
class LogEntry {
    /**
     * @ORM\GeneratedValue()
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $channel;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $level;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $time;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getChannel() {
        return $this->channel;
    }

    /**
     * @param string $channel
     * @return LogEntry
     */
    public function setChannel($channel) {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * @param int $level
     * @return LogEntry
     */
    public function setLevel($level) {
        $this->level = $level;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param string $message
     * @return LogEntry
     */
    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     * @return LogEntry
     */
    public function setTime(\DateTime $time) {
        $this->time = $time;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * @param string $details
     * @return LogEntry
     */
    public function setDetails($details) {
        $this->details = $details;
        return $this;
    }
}