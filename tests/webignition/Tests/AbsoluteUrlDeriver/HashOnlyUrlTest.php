<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class HashOnlyUrlTest extends BaseTest {   
    
    public function testHashOnlyNonAbsoluteUrl() {        
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            '#',
            'http://example.com/'
        );
        
        $this->assertEquals('http://example.com/#', (string)$deriver->getAbsoluteUrl());
    }   
    
    public function testHashAndIdentityNonAbsoluteUrl() {        
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            '#foo',
            'http://example.com/'
        );
        
        $this->assertEquals('http://example.com/#foo', (string)$deriver->getAbsoluteUrl());
    }    
}