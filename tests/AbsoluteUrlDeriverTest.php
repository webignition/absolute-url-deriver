<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

use Psr\Http\Message\UriInterface;
use webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver;
use webignition\Uri\Uri;

class AbsoluteUrlDeriverTest extends \PHPUnit\Framework\TestCase
{
    public function testGetAbsoluteUrlEmptyInput()
    {
        $absoluteUrlDeriver = new AbsoluteUrlDeriver();

        $this->assertNull($absoluteUrlDeriver->getAbsoluteUrl());
    }

    /**
     * @dataProvider getAbsoluteUrlDataProvider
     *
     * @param string $nonAbsoluteUrl
     * @param string $sourceUrl
     * @param string $expectedAbsoluteUrl
     */
    public function testGetAbsoluteUrl($nonAbsoluteUrl, $sourceUrl, $expectedAbsoluteUrl)
    {
        $absoluteUrlDeriver = new AbsoluteUrlDeriver($nonAbsoluteUrl, $sourceUrl);

        $absoluteUrl = $absoluteUrlDeriver->getAbsoluteUrl();

        $this->assertInstanceOf(UriInterface::class, $absoluteUrl);
        $this->assertEquals($expectedAbsoluteUrl, (string)$absoluteUrl);
    }

    public function getAbsoluteUrlDataProvider(): array
    {
        return [
            'non-absolute url is empty' => [
                'nonAbsoluteUrl' => '',
                'sourceUrl' => 'http://example.com/foo/bar',
                'expectedAbsoluteUrl' => 'http://example.com/foo/bar',
            ],
            'absolute url; identical to source' => [
                'nonAbsoluteUrl' => 'http://example.com/',
                'sourceUrl' => 'http://example.com/',
                'expectedAbsoluteUrl' => 'http://example.com/',
            ],
            'absolute url; different host' => [
                'nonAbsoluteUrl' => 'http://foo.example.com/',
                'sourceUrl' => 'http://example.com/',
                'expectedAbsoluteUrl' => 'http://foo.example.com/',
            ],
            'protocol-relative; http' => [
                'nonAbsoluteUrl' => '//foo.example.com/',
                'sourceUrl' => 'http://example.com/',
                'expectedAbsoluteUrl' => 'http://foo.example.com/',
            ],
            'protocol-relative; https' => [
                'nonAbsoluteUrl' => '//foo.example.com/',
                'sourceUrl' => 'https://example.com/',
                'expectedAbsoluteUrl' => 'https://foo.example.com/',
            ],
            'protocol-relative; source path is ignored' => [
                'nonAbsoluteUrl' => '//foo.example.com/',
                'sourceUrl' => 'https://example.com/bar/',
                'expectedAbsoluteUrl' => 'https://foo.example.com/',
            ],
            'hash and identifier' => [
                'nonAbsoluteUrl' => '#bar',
                'sourceUrl' => 'http://example.com/',
                'expectedAbsoluteUrl' => 'http://example.com/#bar',
            ],
            'absolute path; no query string' => [
                'nonAbsoluteUrl' => '/foo',
                'sourceUrl' => 'http://example.com/one/two/three',
                'expectedAbsoluteUrl' => 'http://example.com/foo',
            ],
            'absolute path; has query string' => [
                'nonAbsoluteUrl' => '/foo?key=value',
                'sourceUrl' => 'http://example.com/one/two/three',
                'expectedAbsoluteUrl' => 'http://example.com/foo?key=value',
            ],
            'relative path; no trailing slash on source' => [
                'nonAbsoluteUrl' => 'file.html',
                'sourceUrl' => 'http://example.com/path1/path2',
                'expectedAbsoluteUrl' => 'http://example.com/path1/file.html',
            ],
            'relative path; has trailing slash on source' => [
                'nonAbsoluteUrl' => 'file.html',
                'sourceUrl' => 'http://example.com/path1/path2/',
                'expectedAbsoluteUrl' => 'http://example.com/path1/path2/file.html',
            ],
            'relative path; non-absolute url has leading double dot, source has trailing slash' => [
                'nonAbsoluteUrl' => '../file.html',
                'sourceUrl' => 'http://example.com/path1/path2/',
                'expectedAbsoluteUrl' => 'http://example.com/path1/file.html',
            ],
            'relative path; non-absolute url has leading double dot, source not has trailing slash' => [
                'nonAbsoluteUrl' => '../file.html',
                'sourceUrl' => 'http://example.com/path1/path2',
                'expectedAbsoluteUrl' => 'http://example.com/file.html',
            ],
            'relative path; non-absolute url has leading single dot, source has trailing slash' => [
                'nonAbsoluteUrl' => './file.html',
                'sourceUrl' => 'http://example.com/path1/path2/',
                'expectedAbsoluteUrl' => 'http://example.com/path1/path2/file.html',
            ],
            'relative path; non-absolute url has leading single dot, source not has trailing slash' => [
                'nonAbsoluteUrl' => './file.html',
                'sourceUrl' => 'http://example.com/path1/path2',
                'expectedAbsoluteUrl' => 'http://example.com/path1/file.html',
            ],
            'relative path; source not has path' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'http://example.com',
                'expectedAbsoluteUrl' => 'http://example.com/file.html',
            ],
            'relative path; source not has path, user' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'http://user@example.com',
                'expectedAbsoluteUrl' => 'http://user@example.com/file.html',
            ],
            'relative path; source not has path, user, empty pass' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'http://user:@example.com',
                'expectedAbsoluteUrl' => 'http://user@example.com/file.html',
            ],
            'relative path; source not has path, user, pass' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'http://user:pass@example.com',
                'expectedAbsoluteUrl' => 'http://user:pass@example.com/file.html',
            ],
            'port; non-standard http' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'http://example.com:8080',
                'expectedAbsoluteUrl' => 'http://example.com:8080/file.html',
            ],
            'port; non-standard https' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'https://example.com:8443',
                'expectedAbsoluteUrl' => 'https://example.com:8443/file.html',
            ],
            'port; standard http' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'http://example.com:80',
                'expectedAbsoluteUrl' => 'http://example.com/file.html',
            ],
            'port; standard https' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'https://example.com:443',
                'expectedAbsoluteUrl' => 'https://example.com/file.html',
            ],
            'port; source uses https port for http' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'http://example.com:443',
                'expectedAbsoluteUrl' => 'http://example.com:443/file.html',
            ],
            'port; source uses http port for https' => [
                'nonAbsoluteUrl' => '/file.html',
                'sourceUrl' => 'https://example.com:80',
                'expectedAbsoluteUrl' => 'https://example.com:80/file.html',
            ],
        ];
    }

    /**
     * @dataProvider deriveDataProvider
     *
     * @param string $relative
     * @param string $base
     * @param string $expectedUrl
     */
    public function testDerive(string $relative, string $base, string $expectedUrl)
    {
        $absoluteUrlDeriver = new AbsoluteUrlDeriver();

        $absoluteUrl = $absoluteUrlDeriver->derive(new Uri($base), new Uri($relative));

//        $absoluteUrlDeriver = new AbsoluteUrlDeriver($relative, $base);
//
//        $absoluteUrl = $absoluteUrlDeriver->getAbsoluteUrl();
//
        $this->assertInstanceOf(UriInterface::class, $absoluteUrl);
        $this->assertEquals($expectedUrl, (string) $absoluteUrl);
    }

    public function deriveDataProvider(): array
    {
        return [
            'non-absolute url is empty' => [
                'relative' => '',
                'base' => 'http://example.com/foo/bar',
                'expectedUrl' => 'http://example.com/foo/bar',
            ],
            'absolute url; identical to source' => [
                'relative' => 'http://example.com/',
                'base' => 'http://example.com/',
                'expectedUrl' => 'http://example.com/',
            ],
            'absolute url; different host' => [
                'relative' => 'http://foo.example.com/',
                'base' => 'http://example.com/',
                'expectedUrl' => 'http://foo.example.com/',
            ],
            'protocol-relative; http' => [
                'relative' => '//foo.example.com/',
                'base' => 'http://example.com/',
                'expectedUrl' => 'http://foo.example.com/',
            ],
            'protocol-relative; https' => [
                'relative' => '//foo.example.com/',
                'base' => 'https://example.com/',
                'expectedUrl' => 'https://foo.example.com/',
            ],
            'protocol-relative; source path is ignored' => [
                'relative' => '//foo.example.com/',
                'base' => 'https://example.com/bar/',
                'expectedUrl' => 'https://foo.example.com/',
            ],
            'hash and identifier' => [
                'relative' => '#bar',
                'base' => 'http://example.com/',
                'expectedUrl' => 'http://example.com/#bar',
            ],
            'absolute path; no query string' => [
                'relative' => '/foo',
                'base' => 'http://example.com/one/two/three',
                'expectedUrl' => 'http://example.com/foo',
            ],
            'absolute path; has query string' => [
                'relative' => '/foo?key=value',
                'base' => 'http://example.com/one/two/three',
                'expectedUrl' => 'http://example.com/foo?key=value',
            ],
            'relative path; no trailing slash on source' => [
                'relative' => 'file.html',
                'base' => 'http://example.com/path1/path2',
                'expectedUrl' => 'http://example.com/path1/file.html',
            ],
            'relative path; has trailing slash on source' => [
                'relative' => 'file.html',
                'base' => 'http://example.com/path1/path2/',
                'expectedUrl' => 'http://example.com/path1/path2/file.html',
            ],
            'relative path; non-absolute url has leading double dot, source has trailing slash' => [
                'relative' => '../file.html',
                'base' => 'http://example.com/path1/path2/',
                'expectedUrl' => 'http://example.com/path1/file.html',
            ],
            'relative path; non-absolute url has leading double dot, source not has trailing slash' => [
                'relative' => '../file.html',
                'base' => 'http://example.com/path1/path2',
                'expectedUrl' => 'http://example.com/file.html',
            ],
            'relative path; non-absolute url has leading single dot, source has trailing slash' => [
                'relative' => './file.html',
                'base' => 'http://example.com/path1/path2/',
                'expectedUrl' => 'http://example.com/path1/path2/file.html',
            ],
            'relative path; non-absolute url has leading single dot, source not has trailing slash' => [
                'relative' => './file.html',
                'base' => 'http://example.com/path1/path2',
                'expectedUrl' => 'http://example.com/path1/file.html',
            ],
            'relative path; source not has path' => [
                'relative' => '/file.html',
                'base' => 'http://example.com',
                'expectedUrl' => 'http://example.com/file.html',
            ],
            'relative path; source not has path, user' => [
                'relative' => '/file.html',
                'base' => 'http://user@example.com',
                'expectedUrl' => 'http://user@example.com/file.html',
            ],
            'relative path; source not has path, user, empty pass' => [
                'relative' => '/file.html',
                'base' => 'http://user:@example.com',
                'expectedUrl' => 'http://user@example.com/file.html',
            ],
            'relative path; source not has path, user, pass' => [
                'relative' => '/file.html',
                'base' => 'http://user:pass@example.com',
                'expectedUrl' => 'http://user:pass@example.com/file.html',
            ],
            'port; non-standard http' => [
                'relative' => '/file.html',
                'base' => 'http://example.com:8080',
                'expectedUrl' => 'http://example.com:8080/file.html',
            ],
            'port; non-standard https' => [
                'relative' => '/file.html',
                'base' => 'https://example.com:8443',
                'expectedUrl' => 'https://example.com:8443/file.html',
            ],
            'port; standard http' => [
                'relative' => '/file.html',
                'base' => 'http://example.com:80',
                'expectedUrl' => 'http://example.com/file.html',
            ],
            'port; standard https' => [
                'relative' => '/file.html',
                'base' => 'https://example.com:443',
                'expectedUrl' => 'https://example.com/file.html',
            ],
            'port; source uses https port for http' => [
                'relative' => '/file.html',
                'base' => 'http://example.com:443',
                'expectedUrl' => 'http://example.com:443/file.html',
            ],
            'port; source uses http port for https' => [
                'relative' => '/file.html',
                'base' => 'https://example.com:80',
                'expectedUrl' => 'https://example.com:80/file.html',
            ],
        ];
    }
}
