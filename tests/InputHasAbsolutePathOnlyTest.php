<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class InputHasAbsolutePathOnlyTest extends BaseTest {   
    
    public function testAbsolutePathIsTransformedIntoCorrectAbsoluteUrl() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => '/server.php?param1=value1',
            'source-url' => 'http://www.example.com/pathOne/pathTwo/pathThree',
            'expected-derived-url' => 'http://www.example.com/server.php?param1=value1'
        ));
    }   
}