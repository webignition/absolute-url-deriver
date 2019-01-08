<?php

namespace webignition\AbsoluteUrlDeriver;

use Psr\Http\Message\UriInterface;
use webignition\Uri\Normalizer;
use webignition\Uri\Path;

class AbsoluteUrlDeriver
{
    const PORT_HTTPS = 443;
    const SCHEME_HTTPS = 'https';


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
