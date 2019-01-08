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
                $absolute = $absolute->withScheme($base->getScheme());
            } else {
                if (empty($absolute->getScheme())) {
                    $absolute = $absolute->withScheme($base->getScheme());
                }

                if (empty($absolute->getHost())) {
                    $absolute = $absolute->withHost($base->getHost());
                }

                if (empty($absolute->getPort())) {
                    $absolute = $absolute->withPort($base->getPort());
                }

                if (empty($relative->getPath())) {
                    $absolute = $absolute->withPath($base->getPath());
                } else {
                    $absolute = $this->derivePath($base, $absolute);
                }

                if (empty($absolute->getUserInfo())) {
                    $absolute = $absolute->withUserInfo($base->getUserInfo());
                }
            }
        }

        return Normalizer::normalize($absolute);
    }

    private function derivePath(UriInterface $base, UriInterface $relative): UriInterface
    {
        $relativeUrlPath = new Path($relative->getPath());

        if ($relativeUrlPath->isRelative()) {
            $basePath = $base->getPath();

            if (!empty($basePath)) {
                $derivedPath = $basePath;

                if ('/' !== $derivedPath[-1]) {
                    $derivedPath .= '/../';
                }

                $derivedPath .= $relative->getPath();

                $relative = $relative->withPath($derivedPath);
            }
        }

        return $relative;
    }
}
