<?php

namespace webignition\AbsoluteUrl;

/**
 * 
 * @package webignition\AbsoluteUrl
 *
 */
class Url {
    
    const PATH_PART_SEPARATOR = '/';
    
    
    /**
     *
     * @var array
     */
    private $parts = null;
    
    
    /**
     *
     * @var string
     */
    private $source;
    
    
    /**
     *
     * @var string 
     */
    private $dummyScheme = null;
    
    
    /**
     *
     * @param string $url 
     */
    public function __construct($url) {
        $this->setSource($url);
        $this->parse();        
    }
    
    
    /**
     *
     * @param string $url 
     */
    private function setSource($url) {
        if (substr($url, 0, 2) == '//') {
            $url = $this->getDummyScheme() . substr($url, 2);
        }
        
        $this->source = $url; 
    }
    
    
    /**
     *
     * @return string 
     */
    private function getDummyScheme() {
        if (is_null($this->dummyScheme)) {
            $this->dummyScheme = md5(microtime(true).$this->source)."://";
        }
        
        return $this->dummyScheme;
    }
    
    
    /**
     *
     * @return string
     */
    public function getScheme() {
        return $this->getPart('scheme');
    }
    
    /**
     *
     * @return string
     */
    public function getHost() {
        return $this->getPart('host');
    }

    /**
     *
     * @return string
     */    
    public function getUsername() {
        return $this->getPart('username');
    }
    
    /**
     *
     * @return string
     */    
    public function getPassword() {
        return $this->getPart('password');
    }
    
    /**
     *
     * @return string
     */    
    public function getPath() {
        $path = $this->getPart('path');        
        return $path;
    }
    
    
    /**
     *
     * @return string
     */
    public function getQuery() {
        return $this->getPart('query');
    }
    
    
    /**
     *
     * @return boolean 
     */
    public function hasCredentials() {
        return $this->getUsername() != '' || $this->getPassword() != '';
    }
    
    
    /**
     *
     * @return string 
     */
    public function getCredentialsString() {
        if (!$this->hasCredentials()) {
            return '';
        }
        
        return $this->getUsername().':'.$this->getPassword().'@';
    }
    
    
    /**
     *
     * @return boolean
     */
    public function hasQueryString() {
        return $this->getQuery() != '';
    }
        
    
    /**
     *
     * @return string 
     */
    public function getQueryString() {
        if (!$this->hasQueryString()) {
            return '';
        }
        
        return '?'.$this->getQuery();
    }
    
    
    
    /**
     *
     * @return string
     */    
    public function getFragment() {
        return $this->getPart('fragment');
    }
    
    /**
     *
     * @return string
     */    
    private function getPart($partName) {
        return (isset($this->parts[$partName])) ? $this->parts[$partName] : '';
    }
    
    
    private function parse() {       
        $this->parts = parse_url($this->source);            
        
        if (isset($this->parts['scheme']) && $this->parts['scheme'] == str_replace('://', '', $this->getDummyScheme())) {
            $this->parts['scheme'] = '';            
        }
    }
    
    
    /**
     *
     * @return boolean
     */
    public function pathStartsWithPathPartSeparator() {
        return substr($this->getPath(), 0, 1) == self::PATH_PART_SEPARATOR;
    }
    
    
    /**
     *
     * @return boolean
     */
    public function pathEndsWithPathPartSeparator() {
        return substr($this->getPath(), strlen($this->getPath()) - 1) == self::PATH_PART_SEPARATOR;
    }    
    
    
    /**
     *
     * @return boolean
     */
    public function hostEndsWithPathPartSeparator() {
        return substr($this->getHost(), strlen($this->getHost()) - 1) == self::PATH_PART_SEPARATOR;
    }    

}