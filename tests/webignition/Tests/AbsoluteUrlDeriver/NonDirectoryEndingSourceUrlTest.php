<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class NonDirectoryEndingSourceUrlTest extends BaseTest {   
    
    public function testWithSourceUrlNotEndingWithASlash() {        
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'blog/feed.rss',
            'http://example.com/case-studies'
        );
        
        $this->assertEquals('http://example.com/blog/feed.rss', (string)$deriver->getAbsoluteUrl());
    } 
    
    public function testWIthSourceUrlEndingWithASlash() {        
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'blog/feed.rss',
            'http://example.com/case-studies/'
        );
        
        $this->assertEquals('http://example.com/case-studies/blog/feed.rss', (string)$deriver->getAbsoluteUrl());
    }     
}