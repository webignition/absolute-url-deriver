<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class NonAbsoluteUrlIsBlankTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * A blank non-absolute URL and a source URL should return
     * the source as the absolute URL
     */
    public function testBlankNonAbsoluteUrl() {
        $nonAbsoluteUrl = '';
        $sourceUrl = 'http://example.com/path/index.html';

        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            $nonAbsoluteUrl,
            $sourceUrl
        );

        $this->assertEquals($sourceUrl, (string)$deriver->getAbsoluteUrl());        
    }    
  
}