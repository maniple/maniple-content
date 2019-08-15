<?php

class ManiplePages_PagesController_CreateAction
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
     * @var Zefram_Db
     */
    protected $_db;

    protected function _prepare()
    {
        if (!$this->_securityContext->isAuthenticated()) {
            throw new Maniple_Controller_Exception_AuthenticationRequired($this->_request);
        }
        if (!$this->_securityContext->isAllowed('manage_pages')) {
            throw new Maniple_Controller_Exception_NotAllowed();
        }

        $this->_form = new ManiplePages_Form_Page(array(
            'dbAdapter' => $this->_db,
        ));
    }

    protected function _process()
    {
        $this->_db->beginTransaction();

        try {
            /** @var ManiplePages_Model_DbTable_Pages $pagesTable */
            $pagesTable = $this->_db->getTable(ManiplePages_Model_DbTable_Pages::className);
            $page = $pagesTable->createRow();
            $page->created_at = time();
            $page->updated_at = time();
            $page->page_type = 'page';
            $page->slug = $this->_form->getValue('slug');
            $page->save();

            /** @var ManiplePages_Model_DbTable_PageVersions $pageVersion */
            $pageVersionsTable = $this->_db->getTable(ManiplePages_Model_DbTable_PageVersions::className);
            $pageVersion = $pageVersionsTable->createRow();
            $pageVersion->page_id = $page->getId();
            $pageVersion->user_id = $this->_securityContext->getUser()->getId();
            $pageVersion->saved_at = time();
            $pageVersion->markup_type = 'html';
            $pageVersion->title = $this->_form->getValue('title');
            $pageVersion->body = $this->_form->getValue('body');
            $pageVersion->save();

            $page->PublishedVersion = $pageVersion;
            $page->LatestVersion = $pageVersion;
            $page->save();

            $this->_db->commit();

        } catch (Exception $e) {
            $this->_db->rollBack();
            throw $e;
        }

        $this->_helper->flashMessenger->addSuccessMessage(
            $this->view->translate('Page has been successfully created')
        );

        return $this->view->url('maniple-pages.pages.index');
    }
}
