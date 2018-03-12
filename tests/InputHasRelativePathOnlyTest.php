<?php

namespace webignition\Tests\AbsoluteUrlDeriver;

class InputHasRelativePathOnlyTest extends BaseTest {   
    
    public function testRelativePathIsTransformedIntoCorrectAbsoluteUrl() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => 'server.php?param1=value1',
            'source-url' => 'http://www.example.com/pathOne/pathTwo/pathThree',
            'expected-derived-url' => 'http://www.example.com/pathOne/pathTwo/server.php?param1=value1'
        ));
    } 
    
    public function testAbsolutePathHasDotDotDirecoryAndSourceHasFileName() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => '../jquery.js',
            'source-url' => 'http://www.example.com/pathOne/index.php',
            'expected-derived-url' => 'http://www.example.com/jquery.js'
        ));
    }     
    
    public function testAbsolutePathHasDotDotDirecoryAndSourceHasDirectoryWithTrailingSlash() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => '../jquery.js',
            'source-url' => 'http://www.example.com/pathOne/',
            'expected-derived-url' => 'http://www.example.com/jquery.js'
        ));
    }       
    
    public function testAbsolutePathHasDotDotDirecoryAndSourceHasDirectoryWithoutTrailingSlash() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => '../jquery.js',
            'source-url' => 'http://www.example.com/pathOne',
            'expected-derived-url' => 'http://www.example.com/jquery.js'
        ));
    }     
    
    public function testAbsolutePathHasDotDirecoryAndSourceHasFilename() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => './jquery.js',
            'source-url' => 'http://www.example.com/pathOne/index.php',
            'expected-derived-url' => 'http://www.example.com/pathOne/jquery.js'
        ));         
    }      
    
    public function testAbsolutePathHasDotDirecoryAndSourceHasDirectoryWithTrailingSlash() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => './jquery.js',
            'source-url' => 'http://www.example.com/pathOne/',
            'expected-derived-url' => 'http://www.example.com/pathOne/jquery.js'
        ));         
    }      
    
    
    public function testAbsolutePathHasDotDirecoryAndSourceHasDirectoryWithoutTrailingSlash() {
        $this->assertDerivedUrl(array(
            'non-absolute-url' => './jquery.js',
            'source-url' => 'http://www.example.com/pathOne',
            'expected-derived-url' => 'http://www.example.com/jquery.js'
        ));
    }      
}