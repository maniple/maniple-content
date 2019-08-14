<?php

class ManiplePages_Repository_PageRepository
{
    /**
     * @Inject
     * @var Zefram_Db
     */
    protected $_db;

    /**
     * @Inject('user.sessionManager')
     * @var Maniple_Security_ContextInterface
     */
    protected $_securityContext;

    /**
     * @param array $options OPTIONAL
     * @return Zend_Paginator
     * @throws Zend_Paginator_Exception
     */
    public function getPages(array $options = array())
    {
        $select = $this->_db->select();
        $select->from(
            array('pages' => $this->_db->getTable(ManiplePages_Model_DbTable_Pages::className)),
            '*'
        );
        $select->where('page_type = ?', 'page');
        $select->where('deleted_at IS NULL');
        $select->order('updated_at DESC');

        $page = isset($options['page']) ? (int) $options['page'] : 1;

        $pageSize = isset($options['pageSize']) ? (int) $options['pageSize'] : 25;
        $pageSize = min($pageSize, 100);

        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($pageSize);

        return $paginator;
    }

    /**
     * @param int $pageId
     * @return ManiplePages_Model_Page|null
     */
    public function getPage($pageId)
    {
        /** @var ManiplePages_Model_DbTable_Pages $pagesTable */
        $pagesTable = $this->_db->getTable(ManiplePages_Model_DbTable_Pages::className);
        $page = $pagesTable->fetchRow(array(
            'page_id = ?' => (int) $pageId,
            'page_type = ?' => 'page',
        ));

        return $page;
    }
}
