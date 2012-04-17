<?php
ini_set('display_errors', 'On');
require_once($_SERVER['DOCUMENT_ROOT'].'/../../lib/bootstrap.php');

$urls = array(
    array(
        'test' => 'http://www.example.com/',
        'source' => 'http://www.example.com/',
        'expected-absolute-url' => 'http://www.example.com/'
    ),array(
        'test' => 'pathOne',
        'source' => 'http://www.example.com/',
        'expected-absolute-url' => 'http://www.example.com/pathOne'
    ),array(
        'test' => 'pathTwo',
        'source' => 'http://www.example.com',
        'expected-absolute-url' => 'http://www.example.com/pathTwo'
    ),array(
        'test' => '/pathThree',
        'source' => 'http://www.example.com/',
        'expected-absolute-url' => 'http://www.example.com/pathThree'
    ),array(
        'test' => '/pathFour',
        'source' => 'http://www.example.com',
        'expected-absolute-url' => 'http://www.example.com/pathFour'
    ),array(
        'test' => '/server.php?param1=value1',
        'source' => 'http://www.example.com/pathOne/pathTwo/pathThree',
        'expected-absolute-url' => 'http://www.example.com/server.php?param1=value1'
    ),array(
        'test' => 'server.php?param2=value2',
        'source' => 'http://www.example.com/pathOne/pathTwo/pathThree',
        'expected-absolute-url' => 'http://www.example.com/pathOne/pathTwo/pathThree/server.php?param2=value2'
    )
);

foreach ($urls as $testUrlSet) {
    $url = new \webignition\AbsoluteUrlDeriver\AbsoluteUrl($testUrlSet['test'], $testUrlSet['source']);
    
    echo 'Finding absolute URL from test URL of '.$testUrlSet['test'].' and source URL of '.$testUrlSet['source']."\n";    
    echo 'Expected: '.$testUrlSet['expected-absolute-url']."\n";
    echo 'Actual: '.$url->getUrl()."\n";
    
    echo ($testUrlSet['expected-absolute-url'] == $url->getUrl()) ? 'Ok' : 'Fail';
    
    echo "\n\n";
}