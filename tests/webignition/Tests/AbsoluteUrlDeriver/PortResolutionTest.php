<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class PortResolutionTest extends BaseTest {
    public function testAddsNonStandardHttpPortFromSource() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'server.php',
            'source-url' => 'http://www.example.com:8080',
            'expected-derived-url' => 'http://www.example.com:8080/server.php'
        ));
    }

    public function testAddsNonStandardHttpsPortFromSource() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'server.php',
            'source-url' => 'https://www.example.com:8443',
            'expected-derived-url' => 'https://www.example.com:8443/server.php'
        ));
    }

    public function testDefaultHttpPortFromSourceIsRemoved() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'server.php',
            'source-url' => 'http://www.example.com:80',
            'expected-derived-url' => 'http://www.example.com/server.php'
        ));
    }

    public function testDefaultHttpsPortFromSourceIsRemoved() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'server.php',
            'source-url' => 'https://www.example.com:443',
            'expected-derived-url' => 'https://www.example.com/server.php'
        ));
    }

    public function testHttpOverHttpsPortFromSourceIsAdded() {
        // This is probably a very odd case, but including it for completeness.
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'server.php',
            'source-url' => 'http://www.example.com:443',
            'expected-derived-url' => 'http://www.example.com:443/server.php'
        ));
    }
}
