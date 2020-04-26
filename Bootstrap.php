<?php

class ManiplePages_Bootstrap extends Maniple_Application_Module_Bootstrap
    implements Maniple_Menu_MenuManagerProviderInterface
{
    public function getModuleDependencies()
    {
        return array('maniple-user');
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
            'scriptPathSpec' => ':module/:controller/:action.:suffix',
            'suffix' => 'twig',
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'prefixes' => array(
                'ManiplePages_' => dirname(__FILE__) . '/library/ManiplePages/',
            ),
        );
    }

    protected function _initFrontController()
    {
        /** @var Zend_Controller_Front $frontController */
        $frontController = $this->getApplication()->bootstrap('FrontController')->getResource('FrontController');
        $frontController->registerPlugin(new ManiplePages_Controller_Plugin_PageResolver());
    }

    public function getMenuManagerConfig()
    {
        return array(
            'builders' => array(
                ManiplePages_Menu_MenuBuilder::className,
            ),
        );
    }
}
