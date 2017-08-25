<?php

/**
 * Caching plugin
 * 
 * @uses Zend_Controller_Plugin_Abstract
 */
class Application_Plugin_Caching extends Zend_Controller_Plugin_Abstract
{
    /**
     *  @var bool Whether or not to disable caching
     */
    public static $doNotCache = false;

    /**
     * @var Zend_Cache_Frontend
     */
    public $cache;

    /**
     * @var string Cache key
     */
    public $key;
    
    public $instance;

    /**
     * Constructor: initialize cache
     * 
     * @param  array|Zend_Config $options 
     * @return void
     * @throws Exception
     */
    public function __construct($instance)
    {
        $frontendOptions = array(
            'lifetime' => 7200, // время жизни кэша - 2 часа
            'automatic_serialization' => true,
//            'caching' => false,
        );

        $backendOptions = array(
            'cache_dir' => './tmp/' // директория, в которой размещаются файлы кэша
        );

// получение объекта Zend_Cache_Core
        $this->cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        $this->instance = $instance;
    }

    /**
     * Start caching
     *
     * Determine if we have a cache hit. If so, return the response; else,
     * start caching.
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if (!$request->isGet()) {
            self::$doNotCache = true;
            return;
        }

        $path = $request->getPathInfo();

        $this->key = md5($path);
        if (false !== ($response = $this->getCache())) {
            $response->sendResponse();
            exit;
        }
    }

    /**
     * Store cache
     * 
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        if (self::$doNotCache
            || $this->getResponse()->isRedirect()
            || (null === $this->key)
        ) {
            return;
        }

        $this->cache->save($this->getResponse(), $this->key);
    }
    
    
    
       public function getCache()
{
    if( ($response = $this->cache->load($this->key)) != false) {
        return $response;
    }
    return false;
}
}
