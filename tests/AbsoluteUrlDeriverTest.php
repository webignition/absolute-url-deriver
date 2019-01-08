<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

use Psr\Http\Message\UriInterface;
use webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver;

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
}
