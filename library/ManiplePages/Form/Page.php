<?php

class ManiplePages_Form_Page extends Zefram_Form2
{
    /**
     * @var ManiplePages_Model_Page
     */
    protected $_page;

    public function __construct(array $options = array())
    {
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
                ),
            ),
            'body' => array(
                'type' => 'richText',
                'options' => array(
                    'required' => false,
                    'label' => 'Body',
                ),
            ),
            'slug' => array(
                'type' => 'text',
                'options' => array(
                    'required' => true,
                    'label' => 'Slug',
                    'validators' => array(
                        array('Regex', true, array(
                            'pattern' => '/^[a-z][-a-z0-9]*$/',
                            'messages' => array(
                                Zend_Validate_Regex::NOT_MATCH => 'Identyfikator może zawierać wyłącznie małe litery (bez akcentów), cyfry oraz myślniki',
                            )
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
    public function setPage($page)
    {
        $this->_page = $page;
        return $this;
    }
}
