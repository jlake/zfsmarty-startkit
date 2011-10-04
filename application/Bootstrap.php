<?php
//App起動時実行
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initConfig()
    {
        Zend_Registry::set('config', new Zend_Config($this->getOptions()));
    }

    private function _getConfig()
    {
        try {
            $config = Zend_Registry::get('config');
        } catch(Exception $e) {
            $config = new Zend_Config($this->getOptions());
        }
        return $config;
    }

    protected function _initResource()
    {
        $resource = new Zend_Loader_Autoloader_Resource(array(
            'basePath'  => APPLICATION_PATH,
            'namespace' => '',
        ));
        $resource->addResourceTypes(array(
            'lib' => array(
                'path'      => 'libs/',
                'namespace' => 'Lib',
            ),
            'plugin' => array(
                'path'      => 'plugins/',
                'namespace' => 'Plugin',
            ),
        ));
        return $resource;
    }

    protected function _initModules()
    {
        $autoLoaders = array(
            'site' => new Zend_Application_Module_Autoloader(array(
                'basePath'  => APPLICATION_PATH.'/modules/site/',
                'namespace' => 'Site',
            )),
            'admin' => new Zend_Application_Module_Autoloader(array(
                'basePath'  => APPLICATION_PATH.'/modules/admin/',
                'namespace' => 'Admin',
            )),
            'api' => new Zend_Application_Module_Autoloader(array(
                'basePath'  => APPLICATION_PATH.'/modules/api/',
                'namespace' => 'Api',
            ))
        );
        return $autoLoaders;
    }

    protected function _initDbAdapter()
    {
        if ($this->hasPluginResource('db')) {
            $this->bootstrap('db');
            $resource = $this->getPluginResource('db');
            $db = $resource->getDbAdapter();
            $db->getProfiler()->setEnabled(true);
            Zend_Registry::set('db', $db);
        }

        if ($this->hasPluginResource('multidb')) {
            $this->bootstrap('multidb');
            $resource = $this->getPluginResource('multidb');
            $db1 = $resource->getDb('db1');
            $db1->getProfiler()->setEnabled(true);
            Zend_Registry::set('db1', $db1);
        }
    }

    protected function _initView()
    {
        $config =  $this->_getConfig('config');
        $smartyConfig = $config->smarty;
        $smarty = new Smarty();
        foreach ($smartyConfig as $option => $value) {
            if($option == 'plugin_dir') {
                $smarty->addPluginsDir($value);
            } else {
                $smarty->$option = $value;
            }
        }

        $view = new SmartyView($smarty);
        $options = $this->getOptions();
        $view->options = $options['resources']['view'];
        $view->addHelperPath(APPLICATION_PATH . '/libs/View/Helper', 'Lib_View_Helper');

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
        $viewRenderer->setView($view)
            ->setViewBasePathSpec(APPLICATION_PATH . '/modules/:module/views')
            ->setViewScriptPathSpec('scripts/:controller/:action.:suffix')
            ->setViewSuffix('html');
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        return $view;
    }

    protected function _initLogger()
    {
        $config =  $this->_getConfig('config');
        $logConfig = $config->log;
        $subFolders = array('admin', 'site', 'api');
        $logFile = date('Ymd').'.log';
        foreach($subFolders as $folder) {
            $logDir = $logConfig->path.'/'.$folder;
            if(!file_exists($logDir)) {
                mkdir($logDir, 0775, true);
            }
            $logger = new Zend_Log(new Zend_Log_Writer_Stream("$logDir/$logFile"));
            Zend_Registry::set($folder.'_logger', $logger);
        }
    }

    protected function _initCache()
    {
        $config =  $this->_getConfig('config');
        $cacheConfig = $config->filecache;
        $frontendOptions = array(
            'lifetime' => isset($cacheConfig->lifetime) ? $cacheConfig->lifetime : 7200,
            'automatic_serialization' => isset($cacheConfig->automatic_serialization) ? $cacheConfig->automatic_serialization : true
        );
        $subFolders = array('admin', 'site');
        foreach($subFolders as $folder) {
            $cacheDir = $cacheConfig->path.'/'.$folder;
            if(!file_exists($cacheDir)) {
                mkdir($cacheDir, 0775, true);
            }
            $backendOptions = array('cache_dir' => $cacheDir);
            $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
            Zend_Registry::set($folder.'_cache', $cache);
        }
    }

    /*
    protected function _initMemcache()
    {
        $config =  $this->_getConfig('config');
        $memcacheConfig = $config->memcache;
        $frontendOptions = array(
            'caching' => isset($memcacheConfig->caching) ? $memcacheConfig->caching : true,
            'lifetime' => isset($memcacheConfig->lifetime) ? $memcacheConfig->lifetime : 7200,
            'automatic_serialization' => isset($memcacheConfig->automatic_serialization) ? $memcacheConfig->automatic_serialization : true
        );
        $servers = array();
        foreach($memcacheConfig->servers as $name => $serverConfig) {
            if($serverConfig->available) {
                $servers[] = $serverConfig->toArray();
            }
        }
        $backendOptions = array(
            'servers' => $servers,
            'compression' => isset($memcacheConfig->compression) ? $memcacheConfig->compression : false
        );
        $cache = Zend_Cache::factory('Core', 'Memcached', $frontendOptions, $backendOptions);
        Zend_Registry::set('memcache', $cache);
    }
    */

    protected function _initZFDebug()
    {
        if(APPLICATION_ENV != 'development' || Lib_Util_UserAgent::isMobile()) return;

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $options = array(
            'jquery_path' => '/js/jquery-1.6.2.min.js',
            'plugins' => array(
                'Variables', 
                'File' => array('base_path' => APPLICATION_PATH),
                'Memory', 
                'Time', 
                'Registry', 
                'Exception'
            )
        );

        # Instantiate the database adapter and setup the plugin.
        # Alternatively just add the plugin like above and rely on the autodiscovery feature.
        if ($this->hasPluginResource('db')) {
            $this->bootstrap('db');
            $resource = $this->getPluginResource('db');
            $options['plugins']['Database']['adapter'][] = $resource->getDbAdapter();
        }

        if ($this->hasPluginResource('multidb')) {
            $this->bootstrap('multidb');
            $resource = $this->getPluginResource('multidb');
            $options['plugins']['Database']['adapter'][] = $resource->getDb('db1');
        }

        # Setup the cache plugin
        if ($this->hasPluginResource('cache')) {
            $this->bootstrap('cache');
            $cache = $this-getPluginResource('cache')->getDbAdapter();
            $options['plugins']['Cache']['backend'] = $cache->getBackend();
        }

        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
    }
}

