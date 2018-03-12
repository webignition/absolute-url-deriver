<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class InputHasDifferentHostToSourceTest extends BaseTest {
    
    public function testAddSchemeHostFromSource() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'http://foo.example.com',
            'source-url' => 'http://www.example.com/path/',
            'expected-derived-url' => 'http://foo.example.com'
        ));       
    }   
  
}