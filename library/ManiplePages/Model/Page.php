<?php

/**
 * @property ManipleUser_Model_User $User
 * @property ManiplePages_Model_PageVersion $LatestVersion
 * @property ManiplePages_Model_PageVersion $PublishedVersion
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
        return (int) $this->getSimplePrimaryKey();
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
        return isset($this->PublishedVersion) ? $this->PublishedVersion->getTitle() : null;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return isset($this->PublishedVersion) ? $this->PublishedVersion->getBody() : null;
    }

    /**
     * @return string
     */
    public function getRawBody()
    {
        return isset($this->PublishedVersion) ? $this->PublishedVersion->getRawBody() : null;
    }

    /**
     * @return string
     */
    public function getMarkupType()
    {
        return isset($this->PublishedVersion) ? $this->PublishedVersion->getMarkupType() : null;
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
    public function hasUnpublishedVersion()
    {
        return $this->published_version_id !== $this->latest_version_id;
    }
}
