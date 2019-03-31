<?php

namespace SchoolIT\CommonBundle\Twig;

use Moment\Moment;
use Moment\MomentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;

class MomentPhpExtension extends AbstractExtension {
    private static $localeMapper = [
        'de' => 'de_DE',
        'en' => 'en_GB'
    ];

    private  static $fallbackLocale = 'en_GB';

    private $translator;
    private $logger;

    public function __construct(TranslatorInterface $translator, LoggerInterface $logger = null) {
        $this->translator = $translator;
        $this->logger = $logger ?? new NullLogger();
    }

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('moment', [ $this, 'moment' ])
        ];
    }

    public function moment(\DateTime $date) {
        try {
            $this->configureMomentPhp();

            $moment = new Moment('@' . $date->format('U'));
            return $moment->calendar();
        } catch (MomentException $exception) {
            $this->logger
                ->notice(sprintf('Cannot create Moment from %s', $date), [
                    'date' => $date
                ]);
            return (string)$date;
        }
    }

    private function configureMomentPhp() {
        $locale = $this->translator->getLocale();
        $momentLocale = static::$fallbackLocale;

        if(in_array($locale, array_values(static::$localeMapper))) {
            $momentLocale = $locale;
        } else if(array_key_exists($locale, static::$localeMapper)) {
            $momentLocale = static::$localeMapper[$locale];
        }

        Moment::setLocale($momentLocale);
    }
}