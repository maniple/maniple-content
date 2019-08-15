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

        $this->_form = new Zefram_Form2(array(
            'prefixPath' => array(
                array(
                    'prefix' => 'DokoEvent_Form_',
                    'path'   => __DIR__ . '/../../../doko-event/library/DokoEvent/Form/',
                ),
            ),
            'elements' => array(
                'title' => array(
                    'type' => 'text',
                    'options' => array(
                        'required' => true,
                        'label' => 'Title',
                    ),
                ),
                'body' => array(
                    'type' => 'richText',
                    'options' => array(
                        'required' => false,
                        'label' => 'Body',
                    ),
                ),
                'slug' => array(
                    'type' => 'text',
                    'options' => array(
                        'required' => true,
                        'label' => 'Slug',
                        'validators' => array(
                            array('Regex', true, array(
                                'pattern' => '/^[a-z][-a-z0-9]*$/',
                                'messages' => array(
                                    Zend_Validate_Regex::NOT_MATCH => 'Identyfikator może zawierać wyłącznie małe litery (bez akcentów), cyfry oraz myślniki',
                                )
                            )),
                        ),
                    ),
                ),
                '_submit' => array(
                    'type' => 'submit',
                    'options' => array(
                        'label' => 'Save changes',
                    ),
                ),
            )
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
            $page->content_type = 'page';
            $page->slug = $this->getValue('slug');
            $page->save();

            /** @var ManiplePages_Model_DbTable_PageVersions $pageVersion */
            $pageVersionsTable = $this->_db->getTable(ManiplePages_Model_DbTable_PageVersions::className);
            $pageVersion = $pageVersionsTable->createRow();
            $pageVersion->title = $this->getValue('title');
            $pageVersion->body = $this->getValue('body');
            $pageVersion->saved_at = time();
            $pageVersion->user_id = $this->_securityContext->getUser()->getId();
            $pageVersion->markup_type = 'html';
            $pageVersion->content_id = $page->getId();
            $pageVersion->save();

            $page->PublishedVersion = $pageVersion;
            $page->LatestVersion = $pageVersion;
            $page->save();

        } catch (Exception $e) {
            $this->_db->rollBack();
            throw $e;
        }

    }
}
