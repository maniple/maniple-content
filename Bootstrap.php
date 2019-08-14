<?php

class ManiplePages_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    public function getModuleDependencies()
    {
        return array();
    }

    public function getResourcesConfig()
    {
        return require __DIR__ . '/configs/resources.config.php';
    }

    public function getRoutesConfig()
    {
        return require __DIR__ . '/configs/routes.config.php';
    }

    public function getTranslationsConfig()
    {
        return array(
            'scan'    => Zend_Translate::LOCALE_DIRECTORY,
            'content' => __DIR__ . '/languages',
        );
    }

    public function getViewConfig()
    {
        return array(
            'scriptPaths' => __DIR__ . '/views/scripts',
            'helperPaths' => array(
                'ManiplePages_View_Helper_' => __DIR__ . '/library/ManiplePages/View/Helper/',
            ),
        );
    }

    /**
     * Register autoloader paths
     */
    protected function _initAutoloader()
    {
        Zend_Loader_AutoloaderFactory::factory(array(
            'Zend_Loader_StandardAutoloader' => array(
                'prefixes' => array(
                    'ManiplePages_' => dirname(__FILE__) . '/library/ManiplePages/',
                ),
            ),
        ));
    }

    /**
     * Setup view path spec
     */
    protected function _initViewRenderer()
    {
        /** @var Zefram_Controller_Action_Helper_ViewRenderer $viewRenderer */
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setViewScriptPathSpec(':module/:controller/:action.:suffix', 'maniple-pages');
        $viewRenderer->setViewSuffix('twig', 'maniple-pages');
    }
}
