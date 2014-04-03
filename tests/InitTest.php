<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class InitTest extends \PHPUnit_Framework_TestCase {   
    
    public function testInitialiseViaInitInsteadOfConstructor() {      
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver();
        $deriver->init('/foo/bar.html', 'http://example.com/');
        
        $this->assertEquals('http://example.com/foo/bar.html', (string)$deriver->getAbsoluteUrl());
    }
}