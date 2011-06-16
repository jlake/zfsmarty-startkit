<?php
//App起動時実行
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initConfig()
    {
        Zend_Registry::set('config', new Zend_Config($this->getOptions()));
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
        $smartyConfig = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', 'smarty');
        $smarty = new Smarty();
        foreach ($smartyConfig as $option => $value) {
            if($option == 'plugin_dir') {
                $smarty->plugins_dir[] = $value;
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
            ->setViewScriptPathSpec(APPLICATION_PATH . '/modules/:module/views/scripts/:controller/:action.:suffix')
            ->setViewSuffix('html');
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        //ページング
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'partials/default_paginator.html'
        );

        return $view;
    }

    protected function _initLogger()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH."/configs/application.ini", "log");
        $subFolders = array('admin', 'site', 'trace');
        $logFile = date('Ymd').'.log';
        foreach($subFolders as $folder) {
            $logDir = $config->path.'/'.$folder;
            if(!file_exists($logDir)) {
                mkdir($logDir, 0775, true);
            }
            $logger = new Zend_Log(new Zend_Log_Writer_Stream("$logDir/$logFile"));
            Zend_Registry::set($folder.'_logger', $logger);
        }
    }

    protected function _initCache()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH."/configs/application.ini", "cache");
        $frontend = isset($config->frontend) ? $config->frontend : 'Core';
        $backend = isset($config->backend) ? $config->backend : 'File';
        $frontendOptions = array(
            'lifetime' => isset($config->lifetime) ? $config->lifetime : 7200,
            'automatic_serialization' => isset($config->automatic_serialization) ? $config->automatic_serialization : true
        );
        $subFolders = array('admin', 'site');
        foreach($subFolders as $folder) {
            $cacheDir = $config->path.'/'.$folder;
            if(!file_exists($cacheDir)) {
                mkdir($cacheDir, 0775, true);
            }
            $backendOptions = array('cache_dir' => $cacheDir);
            $cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions);
            Zend_Registry::set($folder.'_cache', $cache);
        }
    }

    protected function _initZFDebug()
    {
        var_dump(Lib_Util_UserAgent::isMobile());
        if(APPLICATION_ENV != 'development' || Lib_Util_UserAgent::isMobile()) return;

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $options = array(
            'jquery_path' => '/js/jquery-1.6.1.min.js',
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

