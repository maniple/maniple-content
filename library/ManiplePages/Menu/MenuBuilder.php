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
     * @param Maniple_Menu_Menu $menu
     */
    public function buildMenu(Maniple_Menu_Menu $menu)
    {
        if ($menu->getName() === 'maniple.primary') {
            return $this->_buildPrimaryMenu($menu);
        }
    }

    protected function _buildPrimaryMenu(Maniple_Menu_Menu $menu)
    {
        if (!$this->_securityContext->isAllowed('manage_pages')) {
            return;
        }

        $menu->addPage(array(
            'label' => 'Pages',
            'route' => 'maniple-pages.pages.index',
            'type'  => 'mvc',
        ));
        $menu->addPage(array(
            'label' => 'New page',
            'route' => 'maniple-pages.pages.create',
            'type'  => 'mvc',
        ));
    }
}
