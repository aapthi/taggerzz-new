<?php
namespace Slave\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Slave\Model\Slave;
use Slave\Form\SlaveForm;
use Zend\Session\Container;
use Zend\Cache\StorageFactory;
use Zend\Session\SaveHandler\Cache;
use Zend\Session\SessionManager;

class SlaveController extends AbstractActionController
{
	protected $slaveTable;
    public function indexAction()
    {
		//$id = $this->getSlaveTable()->insertData();
		$user_session = new Container('user');
		$user_session->id='1';
		$user_session->username='pratap';
		$user_session->password=md5('pratap');
		echo '<pre>'; print_r($_SESSION['user']);exit;
    }
	public function getSlaveTable()
    {
        if (!$this->slaveTable) {
            $sm = $this->getServiceLocator();
            $this->slaveTable = $sm->get('Slave\Model\SlaveTable');
        }
        return $this->slaveTable;
    }
}