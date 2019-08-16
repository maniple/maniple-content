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
        $latestVersion = $this->_page->LatestVersion;

        return array(
            'title' => $latestVersion->getTitle(),
            'body'  => ($rawBody = $latestVersion->getRawBody()) !== null ? $rawBody : $latestVersion->getBody(),
            'slug'  => $this->_page->getSlug(),
        );
    }

    /**
     * @param string[] $fields
     * @return bool
     */
    protected function _isModified(array $fields)
    {
        $defaults = $this->_defaults();

        $isModified = false;
        foreach ($fields as $field) {
            $isModified = $isModified || ($defaults[$field] !== $this->_form->getValue($field));
        }

        return $isModified;
    }

    protected function _process()
    {
        $this->_db->beginTransaction();
        try {
            $page = $this->_page;

            $isModified = $this->_isModified(array('title', 'body'));
            if ($isModified) {
                $pageVersion = $page->LatestVersion->getTable()->createRow(array(
                    'user_id'     => $this->_securityContext->getUser()->getId(),
                    'saved_at'    => time(),
                    'markup_type' => 'html',
                    'title'       => $this->_form->getValue('title'),
                    'body'        => $this->_form->getValue('body'),
                ));
                $pageVersion->Page = $page;
                $pageVersion->save();

                $page->LatestVersion = $pageVersion;
                $page->PublishedVersion = $pageVersion;
            }

            $page->setFromArray(array(
                'slug' => $this->_form->getValue('slug'),
            ));

            if ($page->isModified()) {
                $page->setFromArray(array(
                    'updated_at' => time(),
                ));
                $page->save();
            }

            $this->_db->commit();

        } catch (Exception $e) {
            $this->_db->rollBack();
            throw $e;
        }

        return $this->view->url('maniple-pages.pages.index');
    }
}
