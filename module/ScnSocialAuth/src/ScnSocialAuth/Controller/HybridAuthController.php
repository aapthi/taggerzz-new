<?php
namespace ScnSocialAuth\Controller;
use Hybrid_Endpoint;
use Zend\Mvc\Controller\AbstractActionController;

class HybridAuthController extends AbstractActionController
{
    public function indexAction()
    {
        ob_start();
        echo "<pre>";print_r(\Hybrid_Endpoint::process());exit;
		ob_end_flush(); 
    }
}