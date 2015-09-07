<?php
namespace Slave\Form;
use Zend\Form\Form;
class SlaveForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('slave');
    }
}