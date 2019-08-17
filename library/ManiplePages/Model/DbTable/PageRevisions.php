<?php

/**
 * @method ManiplePages_Model_PageRevision createRow(array $data = array(), string $defaultSource = null)
 * @method ManiplePages_Model_PageRevision|null fetchRow(mixed $where = null, string|array $order = null, int $offset = null)
 * @method ManiplePages_Model_PageRevision|null findRow(mixed $id)
 * @method Zend_Db_Table_Rowset_Abstract|ManiplePages_Model_PageRevision[] find(mixed $key, mixed ...$keys)
 * @method Zend_Db_Table_Rowset_Abstract|ManiplePages_Model_PageRevision[] fetchAll(mixed $where = null, string|array $order = null, int $count = null, int $offset = null)
 */
class ManiplePages_Model_DbTable_PageRevisions extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ManiplePages_Model_PageRevision::className;

    protected $_name = 'page_revisions';

    protected $_referenceMap = array(
        'Page' => array(
            'columns'       => 'page_id',
            'refTableClass' => ManiplePages_Model_DbTable_Pages::className,
            'refColumns'    => 'page_id',
        ),
        'User' => array(
            'columns'       => 'user_id',
            'refTableClass' => ManipleUser_Model_DbTable_Users::className,
            'refColumns'    => 'user_id',
        ),
    );
}
