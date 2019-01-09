<?php

namespace webignition\AbsoluteUrlDeriver;

use Psr\Http\Message\UriInterface;
use webignition\Uri\Normalizer;
use webignition\Uri\Uri;

class AbsoluteUrlDeriver
{
    public static function derive(UriInterface $base, UriInterface $relative)
    {
        if ((string) $relative === '') {
            return $base;
        }

        if ('' !== $relative->getScheme()) {
            return Normalizer::normalize($relative);
        }

        if ('' === $relative->getAuthority()) {
            $authority = $base->getAuthority();

            if ('' === $relative->getPath()) {
                $path = $base->getPath();
                $query = '' === $relative->getQuery() ? $base->getQuery() : $relative->getQuery();
            } else {
                if ('/' === $relative->getPath()[0]) {
                    $path = $relative->getPath();
                } else {
                    if ('' !== $authority && '' === $base->getPath()) {
                        $path = '/' . $relative->getPath();
                    } else {
                        $basePathLastSlashPosition = strrpos($base->getPath(), '/');
                        if (false === $basePathLastSlashPosition) {
                            $path = $relative->getPath();
                        } else {
                            $path =
                                substr($base->getPath(), 0, $basePathLastSlashPosition + 1) .
                                $relative->getPath();
                        }
                    }
                }

                $query = $relative->getQuery();
            }
        } else {
            $authority = $relative->getAuthority();
            $path = $relative->getPath();
            $query = $relative->getQuery();
        }

        $absolute = Uri::compose($base->getScheme(), $authority, $path, $query, $relative->getFragment());

        return Normalizer::normalize($absolute, Normalizer::REMOVE_PATH_DOT_SEGMENTS);
    }
}
