<?php

/**
 * Plugin which intercepts failed routing attempts and tries to redirect to
 * page view if requestUri matches any existing slug.
 */
class ManiplePages_Controller_Plugin_PageResolver extends Zend_Controller_Plugin_Abstract
{
    const REDIRECT_ROUTE = 'maniple-pages.pages.view';

    /**
     * @var bool
     */
    protected $_throwExceptions;

    /**
     * @var int
     */
    protected $_exceptionCount;

    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        if (!$request instanceof Zend_Controller_Request_Http) {
            return;
        }

        $frontController = Zend_Controller_Front::getInstance();
        $this->_throwExceptions = $frontController->throwExceptions();

        // suppress throwing routing exceptions, remember current number
        // of exceptions already thrown - it will be used for determining
        // if this plugin should throw during routeShutdown phase
        $frontController->throwExceptions(false);

        $this->_exceptionCount = count($this->_response->getException());
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        if (!$request instanceof Zend_Controller_Request_Http) {
            return;
        }

        $frontController = Zend_Controller_Front::getInstance();
        $frontController->throwExceptions($this->_throwExceptions);

        /** @var Zend_Controller_Router_Rewrite $router */
        $router = $frontController->getRouter();

        $exceptions = $this->_response->getException();
        $exceptionThrown = count($exceptions) - $this->_exceptionCount > 0;

        if ($exceptionThrown || $router->getCurrentRouteName() === 'default') {
            // route was not matched - try to check if any page slug matches
            /** @var ManiplePages_Repository_PageRepository $pageRepository */
            $pageRepository = $frontController
                ->getParam('bootstrap')
                ->getResource(ManiplePages_Repository_PageRepository::className);

            $requestUri = $request->getRequestUri();

            $slug = trim(strtok($requestUri, '?'), '/');
            $page = $pageRepository->getPageBySlug($slug);

            if ($page) {
                // during routeStartup/routeShutdown routing depends on requestUri
                // rather on request's module/controller/action params
                $routeUrl = $router->assemble(
                    array('page_id' => $page->getId()),
                    self::REDIRECT_ROUTE
                );

                $request->setRequestUri($routeUrl);
                $request->setPathInfo(null);

                // change router's currentRoute to page view
                $router->route($request);

                $request->setRequestUri($requestUri);
                $request->setPathInfo(null);

                return;
            }
        }

        if ($frontController->throwExceptions() && $exceptionThrown) {
            throw end($exceptions);
        }
    }
}
