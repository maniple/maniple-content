<?php

/**
 * @property ManiplePages_Model_Page $Page
 * @method ManiplePages_Model_DbTable_PageVersions getTable()
 */
class ManiplePages_Model_PageVersion extends Zefram_Db_Table_Row
{
    const className = __CLASS__;

    protected $_tableClass = ManiplePages_Model_DbTable_PageVersions::className;

    /**
     * @return int
     */
    public function getId()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return (int) $this->page_version_id;
    }
}