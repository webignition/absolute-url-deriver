<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class RelativePathOnlyTest extends BaseTest {
    
    public function testAddSchemeHostFromSource() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'server.php',
            'source-url' => 'http://www.example.com',
            'expected-derived-url' => 'http://www.example.com/server.php'
        ));
    }
    
    public function testAddSchemeHostUserFromSource() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'server.php',
            'source-url' => 'http://user:@www.example.com',
            'expected-derived-url' => 'http://user:@www.example.com/server.php'
        ));
    }   
    
    public function testAddSchemeHostPassFromSource() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'server.php',
            'source-url' => 'http://:pass@www.example.com',
            'expected-derived-url' => 'http://:pass@www.example.com/server.php'
        ));        
    }
    
    public function testSourceHasFilePath() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'example.html',
            'source-url' => 'http://example.com/index.html',
            'expected-derived-url' => 'http://example.com/example.html'
        ));        
    }    
    
}