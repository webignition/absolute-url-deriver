<?php

namespace webignition\AbsoluteUrlDeriver;

use Psr\Http\Message\UriInterface;
use webignition\Uri\Normalizer;
use webignition\Uri\Uri;

class AbsoluteUrlDeriver
{
    public function derive(UriInterface $base, UriInterface $relative)
    {
        if ((string) $relative === '') {
            return $base;
        }

        if ($relative->getScheme() != '') {
            return Normalizer::normalize($relative);
        }

        if ($relative->getAuthority() != '') {
            $targetAuthority = $relative->getAuthority();
            $targetPath = $relative->getPath();
            $targetQuery = $relative->getQuery();
        } else {
            $targetAuthority = $base->getAuthority();
            if ($relative->getPath() === '') {
                $targetPath = $base->getPath();
                $targetQuery = $relative->getQuery() != '' ? $relative->getQuery() : $base->getQuery();
            } else {
                if ($relative->getPath()[0] === '/') {
                    $targetPath = $relative->getPath();
                } else {
                    if ($targetAuthority != '' && $base->getPath() === '') {
                        $targetPath = '/' . $relative->getPath();
                    } else {
                        $lastSlashPos = strrpos($base->getPath(), '/');
                        if ($lastSlashPos === false) {
                            $targetPath = $relative->getPath();
                        } else {
                            $targetPath = substr($base->getPath(), 0, $lastSlashPos + 1) . $relative->getPath();
                        }
                    }
                }

                $targetQuery = $relative->getQuery();
            }
        }

        $absolute = Uri::compose(
            $base->getScheme(),
            $targetAuthority,
            $targetPath,
            $targetQuery,
            $relative->getFragment()
        );

        return Normalizer::normalize($absolute, Normalizer::REMOVE_PATH_DOT_SEGMENTS);
    }
}
