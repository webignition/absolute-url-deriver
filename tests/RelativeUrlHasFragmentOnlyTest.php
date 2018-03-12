<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class RelativeUrlHasFragmentOnlyTest extends BaseTest {
    
    public function testThingy() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => '#startcontent',
            'source-url' => 'http://news.bbc.co.uk/1/hi/help/3681938.stm',
            'expected-derived-url' => 'http://news.bbc.co.uk/1/hi/help/3681938.stm#startcontent'
        ));       
    }    
  
}