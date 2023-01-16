<?php

namespace SchulIT\CommonBundle\Twig;

class ConfigVariable {

    public function __construct(private readonly string $name, private readonly string $url, private readonly string $version, private readonly string $projectUrl, private readonly ?string $logo = null, private readonly ?string $smallLogo = null, private readonly ?string $logoLink = null) { }

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