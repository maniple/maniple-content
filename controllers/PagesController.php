<?php

/**
 * @property Zend_View_Abstract $view
 * @property Zend_Controller_Request_Http $_request
 */
class ManiplePages_PagesController extends Maniple_Controller_Action
{
    const className = __CLASS__;

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

    public function indexAction()
    {
        if (!$this->_securityContext->isAuthenticated()) {
            throw new Maniple_Controller_Exception_AuthenticationRequired($this->_request);
        }

        if (!$this->_securityContext->isAllowed('manage_pages')) {
            throw new Maniple_Controller_Exception_NotAllowed();
        }

        $pages = $this->_pageRepository->getPages(array(
            'page'     => $this->getScalarParam('page'),
            'pageSize' => $this->getScalarParam('pageSize'),
        ));

        $this->view->assign(array(
            'pages' => $pages,
        ));
    }

    public function viewAction()
    {
        $pageId = (int) $this->getScalarParam('page_id');
        $page = $this->_pageRepository->getPage($pageId);

        if (!$page) {
            throw new Maniple_Controller_Exception_NotFound($this->view->translate('Page not found'));
        }

        if (!$page->isPublished() && !$this->_securityContext->isAllowed('manage_pages')) {
            throw new Maniple_Controller_Exception_NotAllowed();
        }

        $this->view->assign(array(
            'title'   => $page->title,
            'content' => $page->content,
        ));
    }
}
