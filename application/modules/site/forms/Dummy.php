<?php
/**
 * Dummy フォーム
 * @author ou
 */
class Site_Form_Dummy extends Lib_App_BaseForm
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setAction('/auth/login')
            ->setMethod('post');
            //->setTranslator(Zend_Registry::get('translator'));

        $this->addElement('hidden', 'id');
        $this->addElement('hidden', 'page');

        $this->addElement('text', 'inf1', array(
            'label'      => 'カラム1:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                //'EmailAddress',
            )
        ));

        $this->addElement('text', 'inf2', array(
            'label'      => 'カラム2:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 20))
            )
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => '保存',
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
    }
}