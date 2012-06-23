<?php
namespace webignition\AbsoluteUrlDeriver;

// Enable maximum error reporting for unit tests
if (substr($_SERVER['SCRIPT_NAME'], (-1 * strlen('phpunit'))) === 'phpunit') {
    error_reporting(-1);
}

function autoload( $rootDir ) {
    spl_autoload_register(function( $className ) use ( $rootDir ) {        
        $file = sprintf(
            '%s/%s.php',
            $rootDir,
            str_replace( '\\', '/', $className )
        );        
        
        if ( file_exists($file) ) {
            require $file;
        }
    });
}

autoload( __DIR__ . '/../src');
autoload( __DIR__ . '/../tests');
autoload( __DIR__ . '/../vendor/webignition/url/src');