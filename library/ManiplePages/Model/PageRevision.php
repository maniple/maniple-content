<?php

/**
 * @property ManiplePages_Model_Page $Page
 * @method ManiplePages_Model_DbTable_PageRevisions getTable()
 */
class ManiplePages_Model_PageRevision extends Zefram_Db_Table_Row
{
    const className = __CLASS__;

    protected $_tableClass = ManiplePages_Model_DbTable_PageRevisions::className;

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->page_revision_id;
    }

    /**
     * @return string
     */
    public function getMarkupType()
    {
        return $this->markup_type;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
