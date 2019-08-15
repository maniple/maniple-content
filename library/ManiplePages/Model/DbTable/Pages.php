<?php

/**
 * @method ManiplePages_Model_Page createRow(array $data = array(), string $defaultSource = null)
 * @method ManiplePages_Model_Page|null fetchRow(mixed $where = null, string|array $order = null, int $offset = null)
 * @method ManiplePages_Model_Page|null findRow(mixed $id)
 * @method Zend_Db_Table_Rowset_Abstract|ManiplePages_Model_Page[] find(mixed $key, mixed ...$keys)
 * @method Zend_Db_Table_Rowset_Abstract|ManiplePages_Model_Page[] fetchAll(mixed $where = null, string|array $order = null, int $count = null, int $offset = null)
 */
class ManiplePages_Model_DbTable_Pages extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ManiplePages_Model_Page::className;

    protected $_name = 'pages';

    protected $_referenceMap = array(
        'User' => array(
            'columns'       => 'user_id',
            'refTableClass' => ManipleUser_Model_DbTable_Users::className,
            'refColumns'    => 'user_id',
        ),
        'LatestVersion' => array(
            'columns'       => 'latest_version_id',
            'refTableClass' => ManiplePages_Model_DbTable_PageVersions::className,
            'refColumns'    => 'page_version_id',
        ),
        'PublishedVersion' => array(
            'columns'       => 'published_version_id',
            'refTableClass' => ManiplePages_Model_DbTable_PageVersions::className,
            'refColumns'    => 'page_version_id',
        ),
    );
}
