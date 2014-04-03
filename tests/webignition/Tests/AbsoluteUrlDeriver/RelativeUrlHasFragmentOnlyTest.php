<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class RelativeUrlHasFragmentOnlyTest extends BaseTest {
    
    public function testThingy() {
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            '#startcontent',
            'http://news.bbc.co.uk/1/hi/help/3681938.stm'
        );

        $this->assertEquals('http://news.bbc.co.uk/1/hi/help/3681938.stm#startcontent', (string)$deriver->getAbsoluteUrl());        
    }    
  
}