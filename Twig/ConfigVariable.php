<?php

namespace SchulIT\CommonBundle\Twig;

readonly class ConfigVariable {

    public function __construct(private string $name, private string $url, private string $version, private string $projectUrl, private ?string $logo = null, private ?string $smallLogo = null, private ?string $logoLink = null) { }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getHost(): ?string {
        $parsed = parse_url($this->url);
        return $parsed['host'];
    }

    /**
     * @return string
     */
    public function getPath(): string {
        $parsed = parse_url($this->url);
        return $parsed['url'];
    }

    /**
     * @return bool
     */
    public function isSsl(): bool {
        $parsed = parse_url($this->url);
        return $parsed['scheme'] === 'https';
    }

    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }

    /**
     * @return string
     * @deprecated Use getUrl()
     */
    public function getBasePath(): string {
        return $this->url;
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