<?php

namespace SchoolIT\CommonBundle\Helper;

class DateHelper {

    private $today;

    const DateJsonFormat = 'Y-m-d\TH:i:s';

    public function __construct(\DateTime $today = null) {
        $this->setToday($today);
    }

    public function setToday(\DateTime $today = null) {
        $this->today = $today;
    }

    public function formatDateTimeForJson(\DateTime $dateTime = null) {
        if($dateTime === null) {
            return null;
        }

        return $dateTime->format(static::DateJsonFormat);
    }

    /**
     * @return \DateTime
     */
    public function getNow() {
        $today = $this->getToday();

        $now = new \DateTime('now');
        $today->setTime($now->format('H'), $now->format('i'), $now->format('s'));

        return $today;
    }

    /**
     * @return \DateTime
     */
    public function getToday() {
        if($this->today === null) {
            return new \DateTime('today');
        }

        return clone $this->today;
    }

    /**
     * Gets the start date for the timeline
     *
     * @return \DateTime
     */
    public function getStartDate() {
        $now = $this->getNow();
        $endOfDay = $this->getToday()->modify('+18 hours');

        $currentDay = $this->getToday();

        if($now > $endOfDay) {
            $currentDay->modify('+1 day');
        }

        return $currentDay;
    }

    /**
     * @param int $number
     * @param null|\DateTime First day in list
     * @return \DateTime[]
     */
    public function getListOfNextDays($number, $first = null) {
        if($number <= 0) {
            throw new \InvalidArgumentException('$number must be greater than 0');
        }

        if($first === null) {
            $current = $this->getToday();
        } else {
            $current = clone $first; // ensure the first day cannot be modified
        }

        $list = [ ];

        for($i = 0; $i < $number; $i++) {
            $list[] = $current;

            $current = clone $current;
            $current->modify('+1 days');
        }

        return $list;
    }

    /**
     * Checks if a given date is beween a given start and end date
     *
     * @param \DateTime $dateTime
     * @param \DateTime $start
     * @param \DateTime $end
     * @return bool
     */
    public function isBetween(\DateTime $dateTime, \DateTime $start, \DateTime $end) {
        return $dateTime >= $start && $dateTime <= $end;
    }
}
