<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class InputWithProtocolRelativeUrlAndNoPathDoesNotTakeAbsoluteUrlPathTest extends \PHPUnit_Framework_TestCase {   
    
    public function testProtocolRelativeNonAbsoluteUrlWithoutPathDoesNotTakeAbsoluteUrlPath() {        
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            '//example.com',
            'http://blog.example.com/foo/'
        );
        
        $this->assertEquals('http://example.com', (string)$deriver->getAbsoluteUrl());
    }   
}