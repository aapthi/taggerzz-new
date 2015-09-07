<?php
// module/Album/src/Album/Form/AlbumForm.php:
namespace Business\Form;

use Zend\Form\Form;

class BusinessForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('business');
        $this->setAttribute('method', 'post');
       $this->add(array(
            'name' => 'menu_Id',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Menu_Id',
            ),
        ));
        $this->add(array(
            'name' => 'locality',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Locality',
            ),
        ));
		$this->add(array(
            'name' => 'business_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Business_name',
            ),
        ));
		$this->add(array(
            'name' => 'gender',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Gender',
            ),
        ));
		$this->add(array(
            'name' => 'homeservice',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Homeservice',
            ),
        ));
		$this->add(array(
            'name' => 'creditcard',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Creditcard',
            ),
        ));
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}