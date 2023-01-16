<?php

namespace SchulIT\CommonBundle\Twig;

class ConfigVariable {

    public function __construct(private readonly string $name, private readonly string $host, private readonly string $path, private readonly bool $isSsl, private readonly string $version, private readonly string $projectUrl, private readonly ?string $logo = null, private readonly ?string $smallLogo = null, private readonly ?string $logoLink = null) { }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHost(): string {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function isSsl(): bool {
        return $this->isSsl;
    }

    /**
     * @return string
     */
    public function getBasePath(): string {
        $scheme = $this->isSsl() ? 'https' : 'http';

        return sprintf('%s://%s%s', $scheme, $this->getHost(), $this->getPath());
    }

    /**
     * @return string
     */
    public function getVersion(): string {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getProjectUrl(): string {
        return $this->projectUrl;
    }

    /**
     * @return null|string
     */
    public function getLogo(): ?string {
        return $this->logo;
    }

    /**
     * @return null|string
     */
    public function getSmallLogo(): ?string {
        return $this->smallLogo;
    }

    /**
     * @return string|null
     */
    public function getLogoLink(): ?string {
        return $this->logoLink;
    }
}