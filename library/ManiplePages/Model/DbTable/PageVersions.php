<?php

/**
 * @method ManiplePages_Model_PageVersion createRow(array $data = array(), string $defaultSource = null)
 * @method ManiplePages_Model_PageVersion|null fetchRow(string|array|Zend_Db_Table_Select $where = null, string|array $order = null, int $offset = null)
 * @method ManiplePages_Model_PageVersion|null findRow(mixed $id)
 * @method Zend_Db_Table_Rowset_Abstract|ManiplePages_Model_PageVersion[] find(mixed $key, mixed ...$keys)
 * @method Zend_Db_Table_Rowset_Abstract|ManiplePages_Model_PageVersion[] fetchAll(string|array|Zend_Db_Table_Select $where = null, string|array $order = null, int $count = null, int $offset = null)
 */
class ManiplePages_Model_DbTable_PageVersions extends Zefram_Db_Table
{
    const className = __CLASS__;

    protected $_rowClass = ManiplePages_Model_PageVersion::className;

    protected $_name = 'page_versions';

    protected $_referenceMap = array(
        'Page' => array(
            'columns'       => 'page_id',
            'refTableClass' => ManiplePages_Model_DbTable_Pages::className,
            'refColumns'    => 'page_id',
        ),
    );
}
