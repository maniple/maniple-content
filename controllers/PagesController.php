<?php

/**
 * @property Zend_View_Abstract|Zefram_View_Abstract $view
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

        $pages = $this->_pageRepository->getPagesOfType('page', array(
            'page'     => $this->getScalarParam('page'),
            'pageSize' => $this->getScalarParam('pageSize'),
        ));

        $this->view->assign(array(
            'pages'     => $pages,
            'returnUrl' => $this->_request->getRequestUri(),
        ));
    }

    public function viewAction()
    {
        $page = $this->getParam('page');

        if (!$page instanceof ManiplePages_Model_Page) {
            $pageId = (int) $this->getScalarParam('page_id');
            $page = $this->_pageRepository->getPageOfType('page', $pageId);
        }

        if (!$page) {
            throw new Maniple_Controller_Exception_NotFound($this->view->translate('Page not found'));
        }

        if (!$page->isPublished() && !$this->_securityContext->isAllowed('manage_pages')) {
            throw new Maniple_Controller_Exception_NotAllowed();
        }

        $this->view->headTitle($page->getTitle());
        $this->view->headLink()->headLink(array(
            'rel'  => 'canonical',
            'href' => $this->view->serverUrl($this->view->baseUrl($page->getSlug())),
        ));

        // If page body starts with <h1> it will be displayed as the title,
        // and title column will be used for <title> and breadcrumbs
        $pageBody = $page->getBody();
        if (preg_match('/^\s*<h1[\s>]/i', $pageBody)) {
            // extract title from <h1> tag
            $pos = stripos($pageBody, '</h1>');
            $pageTitle = substr($pageBody, 0, $pos);
            $pageTitle = substr($pageTitle, strpos($pageTitle, '>') + 1);
            $pageBody = substr($pageBody, $pos + 5);
        } else {
            $pageTitle = $page->getTitle();
        }

        $this->view->assign(array(
            'title'      => $page->getTitle(),
            'page'       => $page,
            'page_title' => $pageTitle,
            'page_body'  => $pageBody,
        ));
    }

    public function slugAction()
    {
        $input = $this->getScalarParam('input');
        $pageId = (int) $this->getScalarParam('page_id');

        $slugGenerator = $this->_pageRepository->getSlugGenerator();
        $slug = $slugGenerator->slugify($input);

        if ($pageId) {
            $page = $this->_pageRepository->getPageBySlug($slug);
            if ($page && $page->getId() === $pageId) {
                $this->_helper->json($slug);
                return;
            }
        }

        $this->_helper->json($slugGenerator->create($input));
    }
}
