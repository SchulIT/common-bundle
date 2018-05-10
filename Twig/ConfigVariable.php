<?php

namespace SchoolIT\CommonBundle\Twig;

class ConfigVariable {
    private $name;

    private $version;

    private $logo;

    private $host;

    private $path;

    private $isSsl;

    /**
     * ConfigVariable constructor.
     * @param string $name
     * @param string $host
     * @param string $path
     * @param string $isSsl
     * @param string $version
     * @param string|null $logo
     */
    public function __construct($name, $host, $path, $isSsl, $version, $logo = null) {
        $this->name = $name;
        $this->host = $host;
        $this->path = $path;
        $this->isSsl = $isSsl;
        $this->version = $version;
        $this->logo = $logo;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function isSsl() {
        return $this->isSsl;
    }

    /**
     * @return string
     */
    public function getBasePath() {
        $scheme = $this->isSsl() ? 'https' : 'http';

        return sprintf('%s://%s%s', $scheme, $this->getHost(), $this->getPath());
    }

    /**
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @return null|string
     */
    public function getLogo() {
        return $this->logo;
    }
}