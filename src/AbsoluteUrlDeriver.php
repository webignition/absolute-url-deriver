<?php

namespace webignition\AbsoluteUrlDeriver;

use webignition\NormalisedUrl\NormalisedUrl;
use webignition\NormalisedUrl\Path\Path;
use webignition\Url\Url;

class AbsoluteUrlDeriver
{
    const PORT_HTTPS = 443;
    const SCHEME_HTTPS = 'https';

    /**
     * @var NormalisedUrl
     */
    private $nonAbsoluteUrl = null;

    /**
     * @var NormalisedUrl
     */
    private $sourceUrl = null;

    /**
     * @var NormalisedUrl
     */
    private $absoluteUrl = null;

    /**
     * @param string $nonAbsoluteUrl
     * @param string $sourceUrl
     */
    public function __construct($nonAbsoluteUrl = null, $sourceUrl = null)
    {
        if (!is_null($nonAbsoluteUrl) && !is_null($sourceUrl)) {
            $this->init($nonAbsoluteUrl, $sourceUrl);
        }
    }

    /**
     * @param string $nonAbsoluteUrl
     * @param string $sourceUrl
     */
    public function init($nonAbsoluteUrl, $sourceUrl)
    {
        $this->nonAbsoluteUrl = (trim($nonAbsoluteUrl) == '')
                ? new Url($sourceUrl)
                : new Url($nonAbsoluteUrl);

        $this->sourceUrl = new NormalisedUrl($sourceUrl);
        $this->absoluteUrl = null;

        $this->deriveAbsoluteUrl();
    }

    /**
     * @return Url
     */
    public function getAbsoluteUrl()
    {
        return $this->absoluteUrl;
    }

    private function deriveAbsoluteUrl()
    {
        $this->absoluteUrl = clone $this->nonAbsoluteUrl;

        if (!$this->absoluteUrl->isAbsolute()) {
            if ($this->absoluteUrl->isProtocolRelative()) {
                $this->deriveScheme();
            } else {
                $this->derivePath();
                $this->deriveHost();
                $this->derivePort();
                $this->deriveScheme();

                $this->deriveUser();
                $this->derivePass();
            }
        }
    }

    private function deriveHost()
    {
        if (!$this->absoluteUrl->hasHost()) {
            if ($this->sourceUrl->hasHost()) {
                $this->absoluteUrl->setHost($this->sourceUrl->getHost());
            }
        }
    }

    private function derivePort()
    {
        if (!$this->absoluteUrl->hasPort()) {
            if ($this->sourceUrl->hasPort()) {
                $scheme = $this->sourceUrl->hasScheme()
                    ? $this->sourceUrl->getScheme()
                    : null;

                $port = $this->sourceUrl->getPort();

                // Apply port only if not https:443
                if ($port != self::PORT_HTTPS || $scheme != self::SCHEME_HTTPS) {
                    $this->absoluteUrl->setPort($port);
                }
            }
        }
    }

    private function deriveScheme()
    {
        if (!$this->absoluteUrl->hasScheme()) {
            if ($this->sourceUrl->hasScheme()) {
                $this->absoluteUrl->setScheme($this->sourceUrl->getScheme());
            }
        }
    }

    private function derivePath()
    {
        if ($this->absoluteUrl->hasPath() && $this->absoluteUrl->getPath()->isRelative()) {
            if ($this->sourceUrl->hasPath()) {
                /* @var $pathDirectory Path */
                $rawPathDirectory = $this->sourceUrl->getPath()->hasFilename()
                    ? dirname($this->sourceUrl->getPath()) . '/'
                    : (string)$this->sourceUrl->getPath();

                $pathDirectory = new Path($rawPathDirectory);
                $derivedPath = $pathDirectory;

                if (!$pathDirectory->hasTrailingSlash()) {
                    $derivedPath .= '/../';
                }

                $derivedPath .= $this->absoluteUrl->getPath();
                $normalisedDerivedPath = new Path((string)$derivedPath);
                $this->absoluteUrl->setPath($normalisedDerivedPath);
            }
        }

        if (!$this->absoluteUrl->hasPath()) {
            if ($this->sourceUrl->hasPath()) {
                $this->absoluteUrl->setPath($this->sourceUrl->getPath());
            }
        }
    }

    private function deriveUser()
    {
        if (!$this->absoluteUrl->hasUser() && $this->sourceUrl->hasUser()) {
            $this->absoluteUrl->setUser($this->sourceUrl->getUser());
        }
    }

    private function derivePass()
    {
        if (!$this->absoluteUrl->hasPass() && $this->sourceUrl->hasPass()) {
            $this->absoluteUrl->setPass($this->sourceUrl->getPass());
        }
    }
}
