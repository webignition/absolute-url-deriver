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

    public function derive(UriInterface $base, UriInterface $relative)
    {
        if ((string) $relative === '') {
            return clone $base;
        }

        if ((string) $relative === (string) $base) {
            return clone $base;
        }

        $absolute = clone $relative;

        $isAbsolute = !empty($absolute->getScheme()) && !empty($absolute->getHost());

        if (!$isAbsolute) {
            $isProtocolRelative = empty($absolute->getScheme()) && !empty($absolute->getHost());

            if ($isProtocolRelative) {
                $absolute = $this->deriveScheme($base, $absolute);
            } else {
                $absolute = $this->derivePath($base, $absolute);
                $absolute = $this->deriveHost($base, $absolute);
                $absolute = $this->derivePort($base, $absolute);
                $absolute = $this->deriveScheme($base, $absolute);
                $absolute = $this->deriveUserInfo($base, $absolute);
            }
        }

        return Normalizer::normalize($absolute);
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

    private function deriveHost(UriInterface $base, UriInterface $relative): UriInterface
    {
        if (empty($relative->getHost())) {
            if (!empty($base->getHost())) {
                $relative = $relative->withHost($base->getHost());
            }
        }

        return $relative;
    }

    private function derivePort(UriInterface $base, UriInterface $relative): UriInterface
    {
        if (empty($relative->getPort())) {
            if (!empty($base->getPort())) {
                $scheme = $base->getScheme() ?? null;
                $port = $base->getPort();

                // Apply port only if not https:443
                if ($port != self::PORT_HTTPS || $scheme != self::SCHEME_HTTPS) {
                    $relative = $relative->withPort($port);
                }
            }
        }

        return $relative;
    }

    private function deriveScheme(UriInterface $base, UriInterface $relative): UriInterface
    {
        if (empty($relative->getScheme())) {
            if (!empty($base->getScheme())) {
                return $relative->withScheme($base->getScheme());
            }
        }

        return $relative;
    }

    private function derivePath(UriInterface $base, UriInterface $relative): UriInterface
    {
        $relativeUrlPath = new Path($relative->getPath());
        $baseUrlPath = new Path($base->getPath());

        if ($relativeUrlPath->isRelative()) {
            if (!empty($base->getPath())) {
                $rawPathDirectory = $baseUrlPath->hasFilename()
                    ? $baseUrlPath->getDirectory()
                    : $base->getPath();

                $pathDirectory = new Path($rawPathDirectory);
                $derivedPath = $pathDirectory;

                if (!$pathDirectory->hasTrailingSlash()) {
                    $derivedPath .= '/../';
                }

                $derivedPath .= $relative->getPath();

                $relative = $relative->withPath($derivedPath);
            }
        }

        if (empty($relative->getPath())) {
            if (!empty($base->getPath())) {
                $relative = $relative->withPath($base->getPath());
            }
        }

        return $relative;
    }

    private function deriveUserInfo(UriInterface $base, UriInterface $relative): UriInterface
    {
        if (empty($relative->getUserInfo()) && !empty($base->getUserInfo())) {
            $relative = $relative->withUserInfo($base->getUserInfo());
        }

        return $relative;
    }
}
