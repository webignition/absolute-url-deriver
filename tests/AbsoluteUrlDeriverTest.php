<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

use Psr\Http\Message\UriInterface;
use webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver;
use webignition\Uri\Uri;

class AbsoluteUrlDeriverTest extends \PHPUnit\Framework\TestCase
{
    const RFC3986_BASE = 'http://a/b/c/d;p?q';

    /**
     * @dataProvider deriveRfc3986DataProvider
     * @dataProvider deriveDataProvider
     *
     * @param string $base
     * @param string $relative
     * @param string $expectedUrl
     */
    public function testDerive(string $base, string $relative, string $expectedUrl)
    {
        $absoluteUrl = AbsoluteUrlDeriver::derive(new Uri($base), new Uri($relative));

        $this->assertInstanceOf(UriInterface::class, $absoluteUrl);
        $this->assertEquals($expectedUrl, (string) $absoluteUrl);
    }

    public function deriveDataProvider(): array
    {
        return [
            'non-absolute url is empty' => [
                'base' => 'http://example.com/foo/bar',
                'relative' => '',
                'expectedUrl' => 'http://example.com/foo/bar',
            ],
            'absolute url; identical to source' => [
                'base' => 'http://example.com/',
                'relative' => 'http://example.com/',
                'expectedUrl' => 'http://example.com/',
            ],
            'absolute url; different host' => [
                'base' => 'http://example.com/',
                'relative' => 'http://foo.example.com/',
                'expectedUrl' => 'http://foo.example.com/',
            ],
            'protocol-relative; http' => [
                'base' => 'http://example.com/',
                'relative' => '//foo.example.com/',
                'expectedUrl' => 'http://foo.example.com/',
            ],
            'protocol-relative; https' => [
                'base' => 'https://example.com/',
                'relative' => '//foo.example.com/',
                'expectedUrl' => 'https://foo.example.com/',
            ],
            'protocol-relative; source path is ignored' => [
                'base' => 'https://example.com/bar/',
                'relative' => '//foo.example.com/',
                'expectedUrl' => 'https://foo.example.com/',
            ],
            'hash and identifier, empty relative path, empty base path' => [
                'base' => 'http://example.com/',
                'relative' => '#bar',
                'expectedUrl' => 'http://example.com/#bar',
            ],
            'hash and identifier, empty relative path, non-empty base path' => [
                'base' => 'http://example.com/foo/',
                'relative' => '#bar',
                'expectedUrl' => 'http://example.com/foo/#bar',
            ],
            'absolute path; no query string' => [
                'base' => 'http://example.com/one/two/three',
                'relative' => '/foo',
                'expectedUrl' => 'http://example.com/foo',
            ],
            'absolute path; has query string' => [
                'base' => 'http://example.com/one/two/three',
                'relative' => '/foo?key=value',
                'expectedUrl' => 'http://example.com/foo?key=value',
            ],
            'relative path; no trailing slash on source' => [
                'base' => 'http://example.com/path1/path2',
                'relative' => 'file.html',
                'expectedUrl' => 'http://example.com/path1/file.html',
            ],
            'relative path; has trailing slash on source' => [
                'base' => 'http://example.com/path1/path2/',
                'relative' => 'file.html',
                'expectedUrl' => 'http://example.com/path1/path2/file.html',
            ],
            'relative path; non-absolute url has leading double dot, source has trailing slash' => [
                'base' => 'http://example.com/path1/path2/',
                'relative' => '../file.html',
                'expectedUrl' => 'http://example.com/path1/file.html',
            ],
            'relative path; non-absolute url has leading double dot, source not has trailing slash' => [
                'base' => 'http://example.com/path1/path2',
                'relative' => '../file.html',
                'expectedUrl' => 'http://example.com/file.html',
            ],
            'relative path; non-absolute url has leading single dot, source has trailing slash' => [
                'base' => 'http://example.com/path1/path2/',
                'relative' => './file.html',
                'expectedUrl' => 'http://example.com/path1/path2/file.html',
            ],
            'relative path; non-absolute url has leading single dot, source not has trailing slash' => [
                'base' => 'http://example.com/path1/path2',
                'relative' => './file.html',
                'expectedUrl' => 'http://example.com/path1/file.html',
            ],
            'relative path; source not has path' => [
                'base' => 'http://example.com',
                'relative' => '/file.html',
                'expectedUrl' => 'http://example.com/file.html',
            ],
            'relative path; source not has path, user' => [
                'base' => 'http://user@example.com',
                'relative' => '/file.html',
                'expectedUrl' => 'http://user@example.com/file.html',
            ],
            'relative path; source not has path, user, empty pass' => [
                'base' => 'http://user:@example.com',
                'relative' => '/file.html',
                'expectedUrl' => 'http://user@example.com/file.html',
            ],
            'relative path; source not has path, user, pass' => [
                'base' => 'http://user:pass@example.com',
                'relative' => '/file.html',
                'expectedUrl' => 'http://user:pass@example.com/file.html',
            ],
            'port; non-standard http' => [
                'base' => 'http://example.com:8080',
                'relative' => '/file.html',
                'expectedUrl' => 'http://example.com:8080/file.html',
            ],
            'port; non-standard https' => [
                'base' => 'https://example.com:8443',
                'relative' => '/file.html',
                'expectedUrl' => 'https://example.com:8443/file.html',
            ],
            'port; standard http' => [
                'base' => 'http://example.com:80',
                'relative' => '/file.html',
                'expectedUrl' => 'http://example.com/file.html',
            ],
            'port; standard https' => [
                'base' => 'https://example.com:443',
                'relative' => '/file.html',
                'expectedUrl' => 'https://example.com/file.html',
            ],
            'port; source uses https port for http' => [
                'base' => 'http://example.com:443',
                'relative' => '/file.html',
                'expectedUrl' => 'http://example.com:443/file.html',
            ],
            'port; source uses http port for https' => [
                'base' => 'https://example.com:80',
                'relative' => '/file.html',
                'expectedUrl' => 'https://example.com:80/file.html',
            ],
            'path: relative[path not empty, path not starts with slash, no authority], base has no path' => [
                'base' => 'https://example.com',
                'relative' => 'path',
                'expectedUrl' => 'https://example.com/path',
            ],
            'path: relative[path only, path not empty, path not starts with slash], base [path only]' => [
                'base' => 'path1',
                'relative' => 'path2',
                'expectedUrl' => 'path2',
            ],
        ];
    }

    /**
     * resolve() test cases taken from RFC3986
     * @see https://tools.ietf.org/html/rfc3986#section-5.4.1
     *
     * @return array
     */
    public function deriveRfc3986DataProvider(): array
    {
        return [
            'rfc3986#5.2 (1)' => [
                'base' => self::RFC3986_BASE,
                'relative' => 'g:h',
                'expectedUrl' => 'g:h',
            ],
            'rfc3986#5.2 (2)' => [
                'base' => self::RFC3986_BASE,
                'relative' => 'g',
                'expectedUrl' => 'http://a/b/c/g',
            ],
            'rfc3986#5.2 (3)' => [
                'base' => self::RFC3986_BASE,
                'relative' => './g',
                'expectedUrl' => 'http://a/b/c/g',
            ],
            'rfc3986#5.2 (4)' => [
                'base' => self::RFC3986_BASE,
                'relative' => 'g/',
                'expectedUrl' => 'http://a/b/c/g/',
            ],
            'rfc3986#5.2 (5)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '/g',
                'expectedUrl' => 'http://a/g',
            ],
            'rfc3986#5.2 (6)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '//g',
                'expectedUrl' => 'http://g',
            ],
            'rfc3986#5.2 (7)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '?y',
                'expectedUrl' => 'http://a/b/c/d;p?y',
            ],
            'rfc3986#5.2 (8)' => [
                'base' => self::RFC3986_BASE,
                'relative' => 'g?y',
                'expectedUrl' => 'http://a/b/c/g?y',
            ],
            'rfc3986#5.2 (9)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '#s',
                'expectedUrl' => 'http://a/b/c/d;p?q#s',
            ],
            'rfc3986#5.2 (10)' => [
                'base' => self::RFC3986_BASE,
                'relative' => 'g#s',
                'expectedUrl' => 'http://a/b/c/g#s',
            ],
            'rfc3986#5.2 (11)' => [
                'base' => self::RFC3986_BASE,
                'relative' => 'g?y#s',
                'expectedUrl' => 'http://a/b/c/g?y#s',
            ],
            'rfc3986#5.2 (12)' => [
                'base' => self::RFC3986_BASE,
                'relative' => ';x',
                'expectedUrl' => 'http://a/b/c/;x',
            ],
            'rfc3986#5.2 (13)' => [
                'base' => self::RFC3986_BASE,
                'relative' => 'g;x',
                'expectedUrl' => 'http://a/b/c/g;x',
            ],
            'rfc3986#5.2 (14)' => [
                'base' => self::RFC3986_BASE,
                'relative' => 'g;x?y#s',
                'expectedUrl' => 'http://a/b/c/g;x?y#s',
            ],
            'rfc3986#5.2 (15)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '',
                'expectedUrl' => self::RFC3986_BASE,
            ],
            'rfc3986#5.2 (16)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '.',
                'expectedUrl' => 'http://a/b/c/',
            ],
            'rfc3986#5.2 (17)' => [
                'base' => self::RFC3986_BASE,
                'relative' => './',
                'expectedUrl' => 'http://a/b/c/',
            ],
            'rfc3986#5.2 (18)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '..',
                'expectedUrl' => 'http://a/b/',
            ],
            'rfc3986#5.2 (19)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '../',
                'expectedUrl' => 'http://a/b/',
            ],
            'rfc3986#5.2 (20)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '../g',
                'expectedUrl' => 'http://a/b/g',
            ],
            'rfc3986#5.2 (21)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '../..',
                'expectedUrl' => 'http://a/',
            ],
            'rfc3986#5.2 (22)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '../../',
                'expectedUrl' => 'http://a/',
            ],
            'rfc3986#5.2 (23)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '../../g',
                'expectedUrl' => 'http://a/g',
            ],
            'rfc3986#5.2 (24)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '../../../g',
                'expectedUrl' => 'http://a/g',
            ],
            'rfc3986#5.2 (25)' => [
                'base' => self::RFC3986_BASE,
                'relative' => '../../../../g',
                'expectedUrl' => 'http://a/g',
            ],
        ];
    }
}
