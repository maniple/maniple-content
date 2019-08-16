<?php

/**
 * There may be only one <h1>, and it may appear only at the beginning
 * of the page.
 */
class ManiplePages_Validate_Heading1 extends Zend_Validate_Abstract
{
    const MULTIPLE  = 'multiple';
    const NOT_FIRST = 'notFirst';

    protected $_messageTemplates = array(
        self::MULTIPLE  => "There may be only one Heading 1 on the page",
        self::NOT_FIRST => "Heading 1 may only appear at the beginning of the page",
    );

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!preg_match_all('/<h1[\s>]/i', $value, $match)) {
            return true;
        }

        if (count($match[0]) > 1) {
            $this->_error(self::MULTIPLE);
            return false;
        }

        if (!preg_match('/^\s*<h1[\s>]/i', $value)) {
            $this->_error(self::NOT_FIRST);
            return false;
        }

        return true;
    }
}
