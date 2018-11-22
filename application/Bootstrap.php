<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public $user;

    public $translate;

    public function __construct($application)
    {
        parent::__construct($application);

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity())
            $this->user = $auth->getIdentity();
    }


    function _initSetTranslations()
    {

        $locale = new Zend_Locale('ar');
        $bootstrap = $this->bootstrap('view');
        $view = $bootstrap->getResource('view'); // $bootstrap->getOption('layout');

        $translate = new Zend_Translate(array(
            'adapter' => 'array',
            'content' => APPLICATION_PATH . '/languages/en.php',
            'locale' => 'en'
        ));

        $translate->addTranslation(array(
            'adapter' => 'array',
            'content' => APPLICATION_PATH . '/languages/ar.php',
            'locale' => 'ar'
        ));

        $lang = 'en';
        $session = new Zend_Session_Namespace('demo');
        if (isset($session->lang)) {
            $lang = $session->lang;
        }
        $translate->setLocale($lang);

        $view->translate = $translate;
        $view->dir = $translate->getLocale() == 'ar' ? 'rtl' : 'ltr';
        return $translate;
    }

    protected function _initResourceAutoLoad()
    {
        $parent = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH
        ));

        $database = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH . "/modules/Database",
        ));

        $contactus = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH . "/modules/contactus",
        ));

        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH . "/modules/Auth",
        ));
        return array($moduleLoader, $contactus, $database, $parent);

    }

    protected function _initDbAdapter()
    {

        $db = Zend_Db::factory('PDO_MYSQL', array(
            'host' => 'localhost',
            'username' => 'root',
            'password' => 'root',
            'dbname' => 'zend',
            'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock'
        ));

        Zend_Db_Table_Abstract::setDefaultAdapter($db);
    }

    protected function initAutoLoad()
    {

        $moduleLoader = new Zend_Application_Module_Autoloader(

            array(
                'namespace' => '',
                'basePath' => APPLICATION_PATH
            ));

//        return $moduleLoader;
//
//
//        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
//        $autoloader = new Zend_Application_Module_Autoloader(
//            array(
//                'namespace' => 'application',
//                'basePath' => dirname(__FILE__)
//            )
//        );


        return $moduleLoader;
    }

    protected function _initDoctype()
    {
        $this->initRoutes();
        $loader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'ContactUs',
            'module' => 'contactus',
            'basePath' => '/modules/contactus',
        ));
        $loader->addResourceType('form', 'forms', 'Form')
            ->addResourceType('model', 'models', 'Model')
            ->addResourceType('dbtable', 'models/DbTable', 'Model_DbTable');


        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');

        return $loader;

    }

    protected function initRoutes()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        include APPLICATION_PATH . "/configs/routes.php";
    }

}

