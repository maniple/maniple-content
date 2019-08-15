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
     * @param string $type
     * @param array $options OPTIONAL
     * @return Zend_Paginator
     * @throws Zend_Paginator_Exception
     */
    public function getPagesOfType($type, array $options = array())
    {
        $select = $this->_db->select();
        $select->from(
            array('contents' => $this->_db->getTable(ManiplePages_Model_DbTable_Pages::className)),
            '*'
        );
        $select->where('content_type = ?', (string) $type);
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
     * @param string $type
     * @param int|string $contentIdOrSlug
     * @return ManiplePages_Model_Page|null
     */
    public function getContentOfType($type, $contentIdOrSlug)
    {
        /** @var ManiplePages_Model_DbTable_Pages $pagesTable */
        $pagesTable = $this->_db->getTable(ManiplePages_Model_DbTable_Pages::className);

        if (ctype_digit($contentIdOrSlug)) {
            $page = $pagesTable->fetchRow(array(
                'content_type = ?' => (string) $type,
                'content_id = ?'   => (int) $contentIdOrSlug,
            ));
        }

        if (empty($page)) {
            $page = $pagesTable->fetchRow(array(
                'content_type = ?' => (string) $type,
                'slug = ?'         => (string) $contentIdOrSlug,
            ));
        }

        return $page;
    }
}
