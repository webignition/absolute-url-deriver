<?php
ini_set('display_errors', 'On');
require_once(__DIR__.'/../lib/bootstrap.php');

class RelativePathOnlyTest extends PHPUnit_Framework_TestCase {
    
    public function testAddSchemeHostFromSource() {
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'server.php',
            'http://www.example.com'
        );

        $this->assertEquals('http://www.example.com/server.php', (string)$deriver->getAbsoluteUrl());        
    }
    
    public function testAddSchemeHostUserFromSource() {
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'server.php',
            'http://user:@www.example.com'
        );

        $this->assertEquals('http://user:@www.example.com/server.php', (string)$deriver->getAbsoluteUrl());        
    }   
    
    public function testAddSchemeHostPassFromSource() {
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'server.php',
            'http://:pass@www.example.com'
        );

        $this->assertEquals('http://:pass@www.example.com/server.php', (string)$deriver->getAbsoluteUrl());        
    }     
  
}