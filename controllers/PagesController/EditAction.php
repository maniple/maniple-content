<?php

class ManiplePages_PagesController_EditAction
    extends Maniple_Controller_Action_StandaloneForm
{
    protected $_actionControllerClass = ManiplePages_PagesController::className;

    /**
     * @Inject('user.sessionManager')
     * @var ManipleUser_Service_Security
     */
    protected $_securityContext;

    /**
     * @Inject
     * @var ManiplePages_Repository_PageRepository
     */
    protected $_pageRepository;

    /**
     * @Inject
     * @var Zefram_Db
     */
    protected $_db;

    /**
     * @var ManiplePages_Model_Page
     */
    protected $_page;

    protected function _prepare()
    {
        if (!$this->_securityContext->isAuthenticated()) {
            throw new Maniple_Controller_Exception_AuthenticationRequired($this->_request);
        }
        if (!$this->_securityContext->isAllowed('manage_pages')) {
            throw new Maniple_Controller_Exception_NotAllowed();
        }

        $pageId = (int) $this->getScalarParam('page_id');
        $page = $this->_pageRepository->getPageOfType('page', $pageId);

        if (!$page) {
            throw new Maniple_Controller_Exception_NotFound('Page not found');
        }

        $this->_page = $page;
        $this->_form = new ManiplePages_Form_Page(array(
            'dbAdapter' => $this->_db,
            'page' => $page,
        ));
    }

    protected function _defaults()
    {
        return array(
            'title' => $this->_page->getTitle(),
            'body'  => ($rawBody = $this->_page->getRawBody()) !== null ? $rawBody : $this->_page->getBody(),
            'slug'  => $this->_page->getSlug(),
        );
    }

    protected function _process()
    {
        $this->_db->beginTransaction();

        try {
            /** @var ManiplePages_Model_DbTable_PageVersions $pageVersionsTable */
            $pageVersionsTable = $this->_db->getTable(ManiplePages_Model_DbTable_PageVersions::className);
            $pageVersion = $pageVersionsTable->createRow(array(
                'user_id'     => $this->_securityContext->getUser()->getId(),
                'saved_at'    => time(),
                'markup_type' => 'html',
                'title'       => $this->_form->getValue('title'),
                'body'        => $this->_form->getValue('body'),
            ));
            $pageVersion->Page = $this->_page;
            $pageVersion->save();

            $this->_page->setFromArray(array(
                'slug'       => $this->_form->getValue('slug'),
                'updated_at' => time(),
            ));
            $this->_page->LatestVersion = $pageVersion;
            $this->_page->PublishedVersion = $pageVersion;
            $this->_page->save();

            $this->_db->commit();

        } catch (Exception $e) {
            $this->_db->rollBack();
            throw $e;
        }

        return $this->view->url('maniple-pages.pages.index');
    }
}
