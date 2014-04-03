<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class NonAbsoluteUrlIsBlankTest extends BaseTest {
    
    /**
     * A blank non-absolute URL and a source URL should return
     * the source as the absolute URL
     */
    public function testBlankNonAbsoluteUrl() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => '',
            'source-url' => 'http://example.com/path/index.html',
            'expected-derived-url' => 'http://example.com/path/index.html'
        ));      
    }    
  
}