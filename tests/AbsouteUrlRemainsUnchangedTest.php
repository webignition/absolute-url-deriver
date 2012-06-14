<?php
ini_set('display_errors', 'On');
require_once(__DIR__.'/../lib/bootstrap.php');

class AbsouteUrlRemainsUnchangedTest extends PHPUnit_Framework_TestCase {   
    
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