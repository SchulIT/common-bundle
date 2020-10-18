<?php

namespace SchulIT\CommonBundle\Twig;

class ConfigVariable {
    private $name;

    private $version;

    private $projectUrl;

    private $logo;

    private $smallLogo;

    private $host;

    private $path;

    private $isSsl;

    private $logoLink;

    /**
     * ConfigVariable constructor.
     * @param string $name
     * @param string $host
     * @param string $path
     * @param string $isSsl
     * @param string $version
     * @param string $projectUrl
     * @param string|null $logo
     * @param string|null $smallLogo
     * @param string|null $logoLink
     */
    public function __construct($name, $host, $path, $isSsl, $version, $projectUrl, $logo = null, $smallLogo = null, $logoLink = null) {
        $this->name = $name;
        $this->host = $host;
        $this->path = $path;
        $this->isSsl = $isSsl;
        $this->version = $version;
        $this->projectUrl = $projectUrl;
        $this->logo = $logo;
        $this->smallLogo = $smallLogo;
        $this->logoLink = $logoLink;
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
     * @return string
     */
    public function getProjectUrl() {
        return $this->projectUrl;
    }

    /**
     * @return null|string
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * @return null|string
     */
    public function getSmallLogo() {
        return $this->smallLogo;
    }

    /**
     * @return string|null
     */
    public function getLogoLink(): ?string {
        return $this->logoLink;
    }
}