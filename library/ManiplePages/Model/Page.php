<?php

/**
 * @property ManipleUser_Model_User $User
 * @property ManiplePages_Model_PageRevision $LatestRevision
 * @property ManiplePages_Model_PageRevision $PublishedRevision
 * @method ManiplePages_Model_DbTable_Pages getTable()
 */
class ManiplePages_Model_Page extends Zefram_Db_Table_Row
{
    const className = __CLASS__;

    protected $_tableClass = ManiplePages_Model_DbTable_Pages::className;

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->page_id;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->published_at !== null;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return isset($this->PublishedRevision) ? $this->PublishedRevision->getTitle() : null;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return isset($this->PublishedRevision) ? $this->PublishedRevision->getBody() : null;
    }

    /**
     * @return string
     */
    public function getMarkupType()
    {
        return isset($this->PublishedRevision) ? $this->PublishedRevision->getMarkupType() : null;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return bool
     */
    public function hasUnpublishedRevision()
    {
        return $this->published_revision_id !== $this->latest_revision_id;
    }
}
