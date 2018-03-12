<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class InputWithProtocolRelativeUrlAndNoPathDoesNotTakeAbsoluteUrlPathTest extends BaseTest {   
    
    public function testProtocolRelativeNonAbsoluteUrlWithoutPathDoesNotTakeAbsoluteUrlPath() {        
        $this->assertDerivedUrl(array(
            'non-absolute-url' => '//example.com',
            'source-url' => 'http://blog.example.com/foo/',
            'expected-derived-url' => 'http://example.com'
        ));
    }   
}