<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class NonDirectoryEndingSourceUrlTest extends BaseTest {   
    
    public function testWithSourceUrlNotEndingWithASlash() {        
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'blog/feed.rss',
            'source-url' => 'http://example.com/case-studies',
            'expected-derived-url' => 'http://example.com/blog/feed.rss'
        ));
    } 
    
    public function testWIthSourceUrlEndingWithASlash() {        
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'blog/feed.rss',
            'source-url' => 'http://example.com/case-studies/',
            'expected-derived-url' => 'http://example.com/case-studies/blog/feed.rss'
        ));
    }     
}