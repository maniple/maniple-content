<?php

class ManiplePages_Menu_MenuBuilder implements Maniple_Menu_MenuBuilderInterface
{
    const className = __CLASS__;

    /**
     * @Inject
     * @var ManipleUser_Service_Security
     */
    protected $_securityContext;

    /**
     * @Inject('FrontController')
     * @var Zend_Controller_Front
     */
    protected $_frontController;

    /**
     * @param Maniple_Menu_Menu $menu
     * @return void
     */
    public function buildMenu(Maniple_Menu_Menu $menu)
    {
        switch ($menu->getName()) {
            case 'maniple.primary':
                return $this->_buildPrimaryMenu($menu);

            case 'maniple.secondary':
                return $this->_buildSecondaryMenu($menu);
        }
    }

    /**
     * @param Maniple_Menu_Menu $menu
     * @return void
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function _buildPrimaryMenu(Maniple_Menu_Menu $menu)
    {
        if (!$this->_securityContext->isAllowed('manage_pages')) {
            return;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $menu->addPage(array(
            'type'  => 'mvc',
            'label' => 'Pages',
            'route' => 'maniple-pages.pages.index',
        ));

        /** @noinspection PhpUnhandledExceptionInspection */
        $menu->addPage(array(
            'type'  => 'mvc',
            'label' => 'New page',
            'route' => 'maniple-pages.pages.create',
        ));
    }

    protected function _buildSecondaryMenu(Maniple_Menu_Menu $menu)
    {
        if (!$this->_securityContext->isAllowed('manage_pages')) {
            return;
        }

        $page = $menu->findOneBy('id', 'maniple.secondary.new');
        $page->addPage(array(
            'label' => 'Page',
            'route' => 'maniple-pages.pages.create',
        ));

        /** @var ManiplePages_Controller_Plugin_PageResolver|null $pageResolver */
        $pageResolver = $this->_frontController->getPlugin(ManiplePages_Controller_Plugin_PageResolver::className);
        if (!$pageResolver) {
            return;
        }

        if (($page = $pageResolver->getResolvedPage()) !== null) {
            // TODO canEditPage
            /** @noinspection PhpUnhandledExceptionInspection */
            $menu->addPage(array(
                'type'   => 'mvc',
                'label'  => 'Edit',
                'route'  => 'maniple-pages.pages.edit',
                'params' => array(
                    'page_id' => $page->getId(),
                ),
            ));
        }
    }
}
