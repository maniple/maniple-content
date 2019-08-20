<?php

class ManiplePages_Form_Page extends Zefram_Form2
{
    /**
     * @var ManiplePages_Model_Page
     */
    protected $_page;

    public function __construct(array $options = array())
    {
        if (empty($options['dbAdapter'])) {
            throw new InvalidArgumentException('dbAdapter option is not provided');
        }

        $db = $options['dbAdapter'];
        if (!$db instanceof Zefram_Db) {
            throw new InvalidArgumentException(sprintf(
                'dbAdapter must be an instance of Zefram_Db, %s was given',
                is_object($db) ? get_class($db) : gettype($db)
            ));
        }
        unset($options['dbAdapter']);

        $options['prefixPath'] = array(
            array(
                'prefix' => 'DokoEvent_Form_',
                'path'   => __DIR__ . '/../../../../doko-event/library/DokoEvent/Form/',
            ),
        );

        $options['decorators'] = array(
            array('ViewScript', array(
                'viewScript' =>'maniple-pages/form/page.twig',
                // access form via 'form' variable, default is 'element'
                'form' => $this,
            )),
        );

        $options['elements'] = array(
            'title' => array(
                'type' => 'text',
                'options' => array(
                    'required' => true,
                    'label' => 'Title',
                    'validators' => array(
                        array('StringLength', true, array(
                            'max' => 80,
                        )),
                    ),
                ),
            ),
            'body' => array(
                'type' => 'richText',
                'options' => array(
                    'required' => false,
                    'label' => 'Page body',
                    'tinymce' => array(
                        'plugins' => array(
                            'lists',
                            'hr',
                        ),
                        'toolbar' => 'styleselect | bold italic underline strikethrough | subscript superscript | numlist bullist | link blockquote | hr | undo redo',
                        'style_formats' => array(
                            array('title' => 'Paragraph', 'block' => 'p'),
                            array('title' => 'Heading 1', 'block' => 'h1'),
                            array('title' => 'Heading 2', 'block' => 'h2'),
                            array('title' => 'Heading 3', 'block' => 'h3'),
                            array('title' => 'Heading 4', 'block' => 'h4'),
                            array('title' => 'Heading 5', 'block' => 'h5'),
                            array('title' => 'Heading 6', 'block' => 'h6'),
                            array('title' => 'Pre', 'block' => 'pre'),
                        ),
                    ),
                    'htmlpurifier' => array(
                        'HTML.Allowed' => '
                            a[href|target],
                            strong,em,s,u,
                            p,
                            br,
                            sub,sup,
                            blockquote,
                            pre,
                            h1,h2,h3,h4,h5,h6,
                            hr,
                            ul,ol,li
                        ',
                    ),
                    'validators' => array(
                        array(new ManiplePages_Validate_Heading1(), true),
                    ),
                ),
            ),
            'slug' => array(
                'type' => 'text',
                'options' => array(
                    'required' => false,
                    'label' => 'Slug',
                    'validators' => array(
                        array('Regex', true, array(
                            'pattern' => '/^[a-z][-a-z0-9]*$/',
                            'messages' => array(
                                Zend_Validate_Regex::NOT_MATCH => 'A slug must include only lowercase alphanumeric characters and dashes',
                            )
                        )),
                        array('Db_NoRecordExists', true, array(
                            'table' => $db->getTable(ManiplePages_Model_DbTable_Pages::className),
                            'field' => 'slug',
                            'messages' => array(
                                Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => 'This slug is already used',
                            ),
                        )),
                    ),
                ),
            ),
            'submit' => array(
                'type' => 'submit',
                'options' => array(
                    'label' => 'Save changes',
                ),
            ),
        );

        $options['attribs']['id'] = __CLASS__;

        parent::__construct($options);
    }

    /**
     * @return ManiplePages_Model_Page
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @param ManiplePages_Model_Page $page
     * @return ManiplePages_Form_Page
     */
    public function setPage(ManiplePages_Model_Page $page)
    {
        $this->_page = $page;

        /** @var Zend_Validate_Db_NoRecordExists $validator */
        $validator = $this->getElement('slug')->getValidator('Db_NoRecordExists');
        $validator->setExclude(array(
            'field' => 'page_id',
            'value' => $page->getId(),
        ));

        return $this;
    }
}
