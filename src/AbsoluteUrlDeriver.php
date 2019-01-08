<?php

namespace webignition\AbsoluteUrlDeriver;

use Psr\Http\Message\UriInterface;
use webignition\Uri\Normalizer;
use webignition\Uri\Path;
use webignition\Uri\Uri;

class AbsoluteUrlDeriver
{
    const PORT_HTTPS = 443;
    const SCHEME_HTTPS = 'https';

    /**
     * @var UriInterface
     */
    private $nonAbsoluteUrl = null;

    /**
     * @var UriInterface
     */
    private $sourceUrl = null;

    /**
     * @var UriInterface
     */
    private $absoluteUrl = null;

    public function __construct(?string $nonAbsoluteUrl = null, ?string $sourceUrl = null)
    {
        if (!is_null($nonAbsoluteUrl) && !is_null($sourceUrl)) {
            $this->init($nonAbsoluteUrl, $sourceUrl);
        }
    }

    public function init(string $nonAbsoluteUrl, string $sourceUrl)
    {
        $this->sourceUrl = new Uri($sourceUrl);
        $this->nonAbsoluteUrl = (trim($nonAbsoluteUrl) == '')
                ? $this->sourceUrl
                : new Uri($nonAbsoluteUrl);

        if ($this->nonAbsoluteUrl === $this->sourceUrl) {
            $this->absoluteUrl = clone $this->sourceUrl;
        } elseif ((string) $this->nonAbsoluteUrl === (string) $this->sourceUrl) {
            $this->absoluteUrl = clone $this->sourceUrl;
        } else {
            $this->absoluteUrl = null;

            $this->deriveAbsoluteUrl();

            $this->absoluteUrl = Normalizer::normalize($this->absoluteUrl);
        }
    }

    public function getAbsoluteUrl(): ?UriInterface
    {
        return $this->absoluteUrl;
    }

    private function deriveAbsoluteUrl()
    {
        $this->absoluteUrl = clone $this->nonAbsoluteUrl;

        $isAbsolute = !empty($this->absoluteUrl->getScheme()) && !empty($this->absoluteUrl->getHost());

        if (!$isAbsolute) {
            $isProtocolRelative = empty($this->absoluteUrl->getScheme()) && !empty($this->absoluteUrl->getHost());

            if ($isProtocolRelative) {
                $this->deriveScheme();
            } else {
                $this->derivePath();
                $this->deriveHost();
                $this->derivePort();
                $this->deriveScheme();

                $this->deriveUserInfo();
            }
        }
    }

    private function deriveHost()
    {
        if (empty($this->absoluteUrl->getHost())) {
            if (!empty($this->sourceUrl->getHost())) {
                $this->absoluteUrl = $this->absoluteUrl->withHost($this->sourceUrl->getHost());
            }
        }
    }

    private function derivePort()
    {
        if (empty($this->absoluteUrl->getPort())) {
            if (!empty($this->sourceUrl->getPort())) {
                $scheme = $this->sourceUrl->getScheme() ?? null;
                $port = $this->sourceUrl->getPort();

                // Apply port only if not https:443
                if ($port != self::PORT_HTTPS || $scheme != self::SCHEME_HTTPS) {
                    $this->absoluteUrl = $this->absoluteUrl->withPort($port);
                }
            }
        }
    }

    private function deriveScheme()
    {
        if (empty($this->absoluteUrl->getScheme())) {
            if (!empty($this->sourceUrl->getScheme())) {
                $this->absoluteUrl = $this->absoluteUrl->withScheme($this->sourceUrl->getScheme());
            }
        }
    }

    private function derivePath()
    {
        $absoluteUrlPath = new Path($this->absoluteUrl->getPath());
        $sourceUrlPath = new Path($this->sourceUrl->getPath());

        if ($absoluteUrlPath->isRelative()) {
            if (!empty($this->sourceUrl->getPath())) {
                $rawPathDirectory = $sourceUrlPath->hasFilename()
                    ? $sourceUrlPath->getDirectory()
                    : $this->sourceUrl->getPath();

                $pathDirectory = new Path($rawPathDirectory);
                $derivedPath = $pathDirectory;

                if (!$pathDirectory->hasTrailingSlash()) {
                    $derivedPath .= '/../';
                }

                $derivedPath .= $this->absoluteUrl->getPath();

                $this->absoluteUrl = $this->absoluteUrl->withPath($derivedPath);
            }
        }

        if (empty($this->absoluteUrl->getPath())) {
            if (!empty($this->sourceUrl->getPath())) {
                $this->absoluteUrl = $this->absoluteUrl->withPath($this->sourceUrl->getPath());
            }
        }
    }

    private function deriveUserInfo()
    {
        if (empty($this->absoluteUrl->getUserInfo()) && !empty($this->sourceUrl->getUserInfo())) {
            $this->absoluteUrl = $this->absoluteUrl->withUserInfo($this->sourceUrl->getUserInfo());
        }
    }
}
