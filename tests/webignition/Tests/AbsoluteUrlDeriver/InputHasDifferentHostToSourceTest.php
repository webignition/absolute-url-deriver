<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class InputHasDifferentHostToSourceTest extends BaseTest {
    
    public function testAddSchemeHostFromSource() {
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'http://blog.limegreentangerine.co.uk',
            'http://www.limegreentangerine.co.uk/branding/'
        );

        $this->assertEquals('http://blog.limegreentangerine.co.uk', (string)$deriver->getAbsoluteUrl());        
    }   
  
}