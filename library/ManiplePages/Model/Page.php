<?php

/**
 * @property ManipleUser_Model_User $Author
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
        /** @noinspection PhpUndefinedFieldInspection */
        return (int) $this->content_id;
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
        return isset($this->PublishedVersion) ? $this->PublishedVersion->title : null;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return isset($this->PublishedVersion) ? $this->PublishedVersion->body : null;
    }

    /**
     * @return bool
     */
    public function hasUnpublishedVersion()
    {
        return $this->published_version_id !== $this->latest_version_id;
    }
}
