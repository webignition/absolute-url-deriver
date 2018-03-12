<?php

namespace webignition\AbsoluteUrlDeriver;

/**
 * 
 * @package webignition\AbsoluteUrlDeriver
 *
 */
class AbsoluteUrlDeriver {
    
    /**
     *
     * @var \webignition\NormalisedUrl\NormalisedUrl 
     */
    private $nonAbsoluteUrl = null;
    
    
    /**
     *
     * @var \webignition\NormalisedUrl\NormalisedUrl 
     */
    private $sourceUrl = null;
    
    
    /**
     *
     * @var \webignition\NormalisedUrl\NormalisedUrl 
     */
    private $absoluteUrl = null;
    
    
    /**
     *
     * @param string $nonAbsoluteUrl
     * @param string $sourceUrl 
     */
    public function __construct($nonAbsoluteUrl = null, $sourceUrl = null) {
        if (!is_null($nonAbsoluteUrl) && !is_null($sourceUrl)) {
            $this->init($nonAbsoluteUrl, $sourceUrl);
        }
    }
    
    
    /**
     * 
     * @param string $nonAbsoluteUrl
     * @param string $sourceUrl
     */
    public function init($nonAbsoluteUrl, $sourceUrl) {        
        $this->nonAbsoluteUrl = (trim($nonAbsoluteUrl) == '') 
                ? new \webignition\Url\Url($sourceUrl) 
                : new \webignition\Url\Url($nonAbsoluteUrl);
        
        $this->sourceUrl = new \webignition\NormalisedUrl\NormalisedUrl($sourceUrl);  
        $this->absoluteUrl = null;
    }
    
    
    /**
     * 
     * @return \webignition\Url\Url
     */
    public function getAbsoluteUrl() {
        if (is_null($this->absoluteUrl)) {
            $this->deriveAbsoluteUrl();            
        }
        
        return $this->absoluteUrl;
    }
    
    
    private function deriveAbsoluteUrl() {        
        $this->absoluteUrl = clone $this->nonAbsoluteUrl;

        if (!$this->absoluteUrl->isAbsolute()) {        
            if ($this->absoluteUrl->isProtocolRelative()) {
                $this->deriveScheme();
            } else {
                $this->derivePath();        
                $this->deriveHost();
                $this->derivePort();
                $this->deriveScheme();

                $this->deriveUser();
                $this->derivePass();                
            }
        }
    }
    
    private function deriveHost() {
        if (!$this->absoluteUrl->hasHost()) {
            if ($this->sourceUrl->hasHost()) {
                $this->absoluteUrl->setHost($this->sourceUrl->getHost());
            }
        }        
    }

    private function derivePort() {
        if (!$this->absoluteUrl->hasPort()) {
            if ($this->sourceUrl->hasPort()) {
                $scheme = null;
                if ($this->sourceUrl->hasScheme()) {
                    $scheme = $this->sourceUrl->getScheme();
                }
                $port = $this->sourceUrl->getPort();
                // Note: the Url class seems to add the port specification even 
                // when the standard HTTPS port is used. So in order for the 
                // tests (which test "expected" behavior) to pass, don't set the 
                // port if it is using the standard HTTPS port. However, if the 
                // scheme is non-https and port 443 is specified, then add the 
                // port anyway.
                if ($port != 443 || $scheme != 'https') {
                    $this->absoluteUrl->setPort($port);
                }
            }
        }
    }    
    
    private function deriveScheme() {
        if (!$this->absoluteUrl->hasScheme()) {
            if ($this->sourceUrl->hasScheme()) {
                $this->absoluteUrl->setScheme($this->sourceUrl->getScheme());
            }
        }
    }   
    
    private function derivePath() {        
        if ($this->absoluteUrl->hasPath() && $this->absoluteUrl->getPath()->isRelative()) {            
            if ($this->sourceUrl->hasPath()) {
                /* @var $pathDirectory \webignition\NormalisedUrl\Path\Path */
                $rawPathDirectory = $this->sourceUrl->getPath()->hasFilename() ? dirname($this->sourceUrl->getPath()) . '/': (string)$this->sourceUrl->getPath();
     
                $pathDirectory = new \webignition\NormalisedUrl\Path\Path($rawPathDirectory);                                  
                $derivedPath = $pathDirectory;               
                
                if (!$pathDirectory->hasTrailingSlash()) {
                    $derivedPath .= '/../';
                }
                
                $derivedPath .= $this->absoluteUrl->getPath();                
                $normalisedDerivedPath = new \webignition\NormalisedUrl\Path\Path((string)$derivedPath);                  
                $this->absoluteUrl->setPath($normalisedDerivedPath);
            }
        }
        
        if (!$this->absoluteUrl->hasPath()) {
            if ($this->sourceUrl->hasPath()) {                
                $this->absoluteUrl->setPath($this->sourceUrl->getPath());
            }
        }       
    }
    
    private function deriveUser() {
        if (!$this->absoluteUrl->hasUser() && $this->sourceUrl->hasUser()) {
            $this->absoluteUrl->setUser($this->sourceUrl->getUser());
        }
    }
    
    
    private function derivePass() {
        if (!$this->absoluteUrl->hasPass() && $this->sourceUrl->hasPass()) {
            $this->absoluteUrl->setPass($this->sourceUrl->getPass());
        }
    }
    
    
    
//    /**
//     *
//     * @return string
//     */
//    public function getUrl() {
//        $url = $this->getScheme().'://'.$this->getCredentialsString().$this->getHost();
//        
//        if (!$this->hostEndsWithPathPartSeparator() && !$this->pathStartsWithPathPartSeparator()) {
//            $url .= '/';
//        }        
//        
//        $url .= $this->getPath().$this->getQueryString();
//        
//        return $url;
//    }    
//
//    
//    /**
//     *
//     * @return string
//     */    
//    public function getScheme() {
//        return (parent::getScheme() == '') ? $this->sourceUrl->getScheme() : parent::getScheme();
//    }
//    
//    /**
//     *
//     * @return string
//     */    
//    public function getHost() {
//        return (parent::getHost() == '') ? $this->sourceUrl->getHost() : parent::getHost();
//    }
//    
//    /**
//     *
//     * @return string
//     */    
//    public function getUsername() {
//        return (parent::getUsername() == '') ? $this->sourceUrl->getUsername() : parent::getUsername();
//    }
//    
//    /**
//     *
//     * @return string
//     */    
//    public function getPassword() {
//        return (parent::getPassword() == '') ? $this->sourceUrl->getPassword() : parent::getPassword();
//    }
//    
//    /**
//     *
//     * @return string
//     */    
//    public function getPath() {                
//        if ($this->parentPathStartsWithPathPartSeparator()) {
//            return (parent::getPath() == '') ? $this->sourceUrl->getPath() : parent::getPath();
//        }
//  
//        return ($this->sourceUrl->pathEndsWithPathPartSeparator()) ?
//            $this->sourceUrl->getPath() . parent::getPath():
//            $this->sourceUrl->getPath() . '/' . parent::getPath();        
//    }
//    
//    /**
//     *
//     * @return string
//     */    
//    public function getFragment() {
//        return (parent::getFragment() == '') ? $this->sourceUrl->getFragment() : parent::getFragment();
//    }
//    
//    /**
//     *
//     * @return boolean
//     */
//    protected function parentPathStartsWithPathPartSeparator() {
//        return substr(parent::getPath(), 0, 1) == self::PATH_PART_SEPARATOR;
//    }
}
