<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class AbsouteUrlRemainsUnchangedTest extends BaseTest {
    
    public function testAbsoluteUrlWithNoPath() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'http://www.example.com/',
            'source-url' => 'http://www.example.com/',
            'expected-derived-url' => 'http://www.example.com/'
        ));
    }   
    
    public function testAbsoluteUrlWithPath() {      
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'http://www.example.com/pathOne',
            'source-url' => 'http://www.example.com/',
            'expected-derived-url' => 'http://www.example.com/pathOne'
        ));
    }    
}