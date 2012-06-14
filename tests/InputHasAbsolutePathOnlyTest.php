<?php
ini_set('display_errors', 'On');
require_once(__DIR__.'/../lib/bootstrap.php');

class InputHasAbsolutePathOnlyTest extends PHPUnit_Framework_TestCase {   
    
    public function testAbsolutePathIsTransformedIntoCorrectAbsoluteUrl() {
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            '/server.php?param1=value1',
            'http://www.example.com/pathOne/pathTwo/pathThree'
        );
        
        $this->assertEquals('http://www.example.com/server.php?param1=value1', $deriver->getAbsoluteUrl());
    }   
}