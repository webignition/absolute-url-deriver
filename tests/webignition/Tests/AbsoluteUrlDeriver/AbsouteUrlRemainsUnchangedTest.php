<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class AbsouteUrlRemainsUnchangedTest extends BaseTest {   
    
    public function testAbsoluteUrlWithNoPath() {      
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'http://www.example.com/',
            'http://www.example.com/'
         );
        
        $this->assertEquals('http://www.example.com/', (string)$deriver->getAbsoluteUrl());
    }   
    
    public function testAbsoluteUrlWithPath() {      
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'http://www.example.com/pathOne',
            'http://www.example.com/'
        );
        
        $this->assertEquals('http://www.example.com/pathOne', (string)$deriver->getAbsoluteUrl());
    }    
}