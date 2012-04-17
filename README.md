Absolute URL deriver
====================

Represents an absolute URL, derives absolute URL from relative and source URLs.

Useful when:

- extracting full HREF URLs for links in a HTML document
- determining absolute new URL for a 301 redirect where the HTTP server returned a relative Location value

Usage
-----

### The "Hello World" example

    $data = array(
        'test' => 'server.php?param2=value2',
        'source' => 'http://www.example.com/pathOne/pathTwo/pathThree'
    );


    $url = new \webignition\AbsoluteUrl\AbsoluteUrl($testUrlSet['test'], $testUrlSet['source']);
    echo $url->getUrl();
    // => http://www.example.com/pathOne/pathTwo/pathThree/server.php?param2=value2


Further Examples
----------------

See [/tests/www/index.php][1] for further examples of how a relative URL and source URL translate into a absolute URL.

[1]: https://github.com/webignition/absolute-url/blob/master/tests/www/index.php
