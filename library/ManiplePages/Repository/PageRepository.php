<?php

class ManiplePages_Repository_PageRepository
{
    const className = __CLASS__;

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
            array('pages' => $this->_db->getTable(ManiplePages_Model_DbTable_Pages::className)),
            '*'
        );
        $select->where('page_type = ?', (string) $type);
        $select->where('deleted_at IS NULL');
        $select->order('updated_at DESC');

        $page = isset($options['page']) ? (int) $options['page'] : 1;

        $pageSize = isset($options['pageSize']) ? (int) $options['pageSize'] : 25;
        $pageSize = min($pageSize, 100);

        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($pageSize);

        $paginator->setFilter($filter = new Zefram_Filter());

        $filter->addFilter(new Zend_Filter_Callback(function (array $pages) {
            foreach ($pages as &$page) {
                $page['id'] = $page['page_id'];
            }
            unset($page);
            return $pages;
        }));

        $db = $this->_db;
        $filter->addFilter(new Zend_Filter_Callback(function (array $pages) use ($db) {
            $publishedRevisions = array();
            foreach ($pages as $page) {
                $publishedRevisionId = (int) $page['published_revision_id'];
                $publishedRevisions[$publishedRevisionId] = null;
            }

            if (count($publishedRevisions)) {
                $select = $db->select();
                $select->from(
                    $db->getTable(ManiplePages_Model_DbTable_PageRevisions::className),
                    array(
                        'page_id',
                        'page_revision_id',
                        'user_id',
                        'title',
                    )
                );
                $select->where('page_revision_id IN (?)', array_keys($publishedRevisions));

                foreach ($select->query()->fetchAll() as $pageRevision) {
                    $pageRevisionId = (int) $pageRevision['page_revision_id'];
                    $publishedRevisions[$pageRevisionId] = $pageRevision;
                }

                foreach ($pages as $i => &$page) {
                    $publishedRevisionId = (int) $page['published_revision_id'];
                    $pageRevision = $publishedRevisions[$publishedRevisionId];

                    $page['user_id'] = $pageRevision ? $pageRevision['user_id'] : null;
                    $page['title'] = $pageRevision ? $pageRevision['title'] : null;
                }
                unset($page);
            }

            return $pages;
        }));

        $filter->addFilter(new Zend_Filter_Callback(function (array $pages) use ($db) {
            $users = array();
            foreach ($pages as $page) {
                $userId = (int) $page['user_id'];
                $users[$userId] = null;
            }

            if (count($users)) {
                $select = $db->select();
                $select->from(
                    $db->getTable(ManipleUser_Model_DbTable_Users::className),
                    array(
                        'user_id',
                        'first_name',
                        'last_name',
                    )
                );

                $select->where('user_id IN (?)', array_keys($users));

                foreach ($select->query()->fetchAll() as $user) {
                    $userId = (int) $user['user_id'];
                    $users[$userId] = $user;
                }

                foreach ($pages as &$page) {
                    $userId = (int) $page['user_id'];
                    $page['user'] = $users[$userId];
                }
                unset($page);
            }

            return $pages;
        }));

        return $paginator;
    }

    /**
     * @param string $type
     * @param int|string $contentIdOrSlug
     * @return ManiplePages_Model_Page|null
     */
    public function getPageOfType($type, $contentIdOrSlug)
    {
        /** @var ManiplePages_Model_DbTable_Pages $pagesTable */
        $pagesTable = $this->_db->getTable(ManiplePages_Model_DbTable_Pages::className);

        if (is_int($contentIdOrSlug) || ctype_digit($contentIdOrSlug)) {
            $page = $pagesTable->fetchRow(array(
                'page_type = ?' => (string) $type,
                'page_id = ?'   => (int) $contentIdOrSlug,
            ));
        }

        if (empty($page)) {
            $page = $pagesTable->fetchRow(array(
                'page_type = ?' => (string) $type,
                'slug = ?'      => (string) $contentIdOrSlug,
            ));
        }

        return $page;
    }

    /**
     * @param string $slug
     * @return ManiplePages_Model_Page|null
     */
    public function getPageBySlug($slug)
    {
        /** @var ManiplePages_Model_DbTable_Pages $pagesTable */
        $pagesTable = $this->_db->getTable(ManiplePages_Model_DbTable_Pages::className);

        return $pagesTable->fetchRow(array('slug = ?' => (string) $slug));
    }

    /**
     * @return Maniple_SlugGenerator_DbTable
     */
    public function getSlugGenerator()
    {
        $slugGenerator = new Maniple_SlugGenerator_DbTable(
            $this->_db->getTable(ManiplePages_Model_DbTable_Pages::className)
        );

        return $slugGenerator;
    }
}
