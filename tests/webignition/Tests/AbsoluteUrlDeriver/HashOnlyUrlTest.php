<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class HashOnlyUrlTest extends BaseTest {   
    
    public function testHashOnlyNonAbsoluteUrl() {        
        $this->assertDerivedUrl(array(
            'non-absolute-url' => '#',
            'source-url' => 'http://example.com/',
            'expected-derived-url' => 'http://example.com/#'
        ));
    }   
    
    public function testHashAndIdentityNonAbsoluteUrl() {        
        $this->assertDerivedUrl(array(
            'non-absolute-url' => '#foo',
            'source-url' => 'http://example.com/',
            'expected-derived-url' => 'http://example.com/#foo'
        ));
    }    
}