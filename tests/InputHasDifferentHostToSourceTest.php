<?php
ini_set('display_errors', 'On');
require_once(__DIR__.'/../lib/bootstrap.php');

class InputHasDifferentHostToSourceTest extends PHPUnit_Framework_TestCase {
    
    public function testAddSchemeHostFromSource() {
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'http://blog.limegreentangerine.co.uk',
            'http://www.limegreentangerine.co.uk/branding/'
        );

        $this->assertEquals('http://blog.limegreentangerine.co.uk', (string)$deriver->getAbsoluteUrl());        
    }   
  
}