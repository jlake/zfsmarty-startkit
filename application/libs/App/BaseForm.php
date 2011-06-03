<?php
/**
 * 共通 Zend_Form の拡張
 * @author ou
 */
class Lib_App_BaseForm extends Zend_Form
{
    protected $_standardElementDecorator = array(
        'ViewHelper',
        array('LabelError', array('escape'=>false)),
        array('HtmlTag', array('tag'=>'li'))
    );

    protected $_buttonElementDecorator = array(
        'ViewHelper'
    );

    protected $_standardGroupDecorator = array(
        'FormElements',
        array('HtmlTag', array('tag'=>'ol')),
        'Fieldset'
    );

    protected $_buttonGroupDecorator = array(
        'FormElements',
        'Fieldset'
    );

    public function __construct($options = null)
    {
        $this->_setupTranslation();

        parent::__construct($options);

        $this->setAttrib('accept-charset', 'UTF-8');
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
    }

    protected function _setupTranslation() 
    {
        if (self::getDefaultTranslator()) {
            return;
        }
        $lang = defined(LANG) ? LANG : 'jp';
        $translate = new Zend_Translate('array', APPLICATION_PATH . '/configs/lang/'.$lang.'/Zend_Validate.php', 'jp');
        Zend_Form::setDefaultTranslator($translate);
    }

}