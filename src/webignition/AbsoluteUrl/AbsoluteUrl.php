<?php

namespace webignition\AbsoluteUrl;

/**
 * 
 * @package webignition\AbsoluteUrl
 *
 */
class AbsoluteUrl extends \webignition\AbsoluteUrl\Url {
    
    
    /**
     *
     * @var string
     */
    private $sourceUrl = null;
    
    
    
    /**
     *
     * @param string $href 
     */
    public function __construct($url, $sourceUrl) {
        parent::__construct($url);
        $this->sourceUrl = new \webignition\AbsoluteUrl\Url($sourceUrl);
    }
    
    
    /**
     *
     * @return string
     */
    public function getUrl() {
        $url = $this->getScheme().'://'.$this->getCredentialsString().$this->getHost();
        
        if (!$this->hostEndsWithPathPartSeparator() && !$this->pathStartsWithPathPartSeparator()) {
            $url .= '/';
        }        
        
        $url .= $this->getPath().$this->getQueryString();
        
        return $url;
    }    

    
    /**
     *
     * @return string
     */    
    public function getScheme() {
        return (parent::getScheme() == '') ? $this->sourceUrl->getScheme() : parent::getScheme();
    }
    
    /**
     *
     * @return string
     */    
    public function getHost() {
        return (parent::getHost() == '') ? $this->sourceUrl->getHost() : parent::getHost();
    }
    
    /**
     *
     * @return string
     */    
    public function getUsername() {
        return (parent::getUsername() == '') ? $this->sourceUrl->getUsername() : parent::getUsername();
    }
    
    /**
     *
     * @return string
     */    
    public function getPassword() {
        return (parent::getPassword() == '') ? $this->sourceUrl->getPassword() : parent::getPassword();
    }
    
    /**
     *
     * @return string
     */    
    public function getPath() {                
        if ($this->parentPathStartsWithPathPartSeparator()) {
            return (parent::getPath() == '') ? $this->sourceUrl->getPath() : parent::getPath();
        }
  
        return ($this->sourceUrl->pathEndsWithPathPartSeparator()) ?
            $this->sourceUrl->getPath() . parent::getPath():
            $this->sourceUrl->getPath() . '/' . parent::getPath();        
    }
    
    /**
     *
     * @return string
     */    
    public function getFragment() {
        return (parent::getFragment() == '') ? $this->sourceUrl->getFragment() : parent::getFragment();
    }
    
    /**
     *
     * @return boolean
     */
    protected function parentPathStartsWithPathPartSeparator() {
        return substr(parent::getPath(), 0, 1) == self::PATH_PART_SEPARATOR;
    }
}