<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {   
    
    /**
     *
     * @var \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver
     */
    protected $deriver;
    
    public function setUp() {
        $this->deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver();
    }  
    
    
    protected function assertDerivedUrl($testData = array()) {
        $this->deriver->init($testData['non-absolute-url'], $testData['source-url']);        
        $this->assertEquals(
                $testData['expected-derived-url'],
                (string)$this->deriver->getAbsoluteUrl(),
                'Expected derived URL "' . $testData['expected-derived-url'] . '" not formed from non-absolute URL "' . $testData['non-absolute-url'] . '" and source URL "' . $testData['source-url'] . '"'
        );        
    }
}