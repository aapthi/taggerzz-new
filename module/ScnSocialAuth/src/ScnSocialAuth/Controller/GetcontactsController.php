<?php
namespace ScnSocialAuth\Controller;

use Hybrid_Auth;
use Zend\Mvc\Controller\AbstractActionController;
class GetcontactsController extends AbstractActionController
{
	public function friendscontactsAction()
    {
		$uri = $this->getRequest()->getUri();
		$base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
		//echo $base;exit;
		$config=array(
		"base_url" => 'ZendSkeletonApplication-master/module/ScnSocialAuth/src/ScnSocialAuth/HybridAuth/', 

		"providers" => array ( 
			// openid providers
			"OpenID" => array (
				"enabled" => true
			),

			"Yahoo" => array ( 
				"enabled" => true,
				//onetock.com  "keys"    => array ( "key" => " C7P6x6bIkY1q_ZPuUalHGSaOGn20ASY.wk06", "secret" => "5652bcafff07018e5fd94c8b3e962188" ),
				 "keys"    => array ( "key" => "dj0yJmk9czZmUDlMcERKVEhqJmQ9WVdrOVNXUXdRa3RoTm5FbWNHbzlNVGcxTlRnek9UazJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD0zNw--", "secret" => "a2a5f479ea64c0f1435bfa05228af3bb35e322dd" ),
			),

			"AOL"  => array ( 
				"enabled" => true 
			),

			"Google" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "815795346530.apps.googleusercontent.com", "secret" => "_zjy0VrU5JMzUWnakM8XueXF" ), 
			),

			"Facebook" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "690375760988433", "secret" => "48c429e85a80ababd230ce780b3f4693" ), 
			),

			"Twitter" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "8Yj6jQ1nRKDGpqsPn5udw", "secret" => "fYULROf0RTw3Vzx8STXLcF1RfBIf80UN9bFyIWVp8" ) 
			),

			// windows live
			"Live" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "000000004C0FA72B", "secret" => "7HGdqbSzRSDpIL2wElzt80bOICeD951E" ) 
			),

			"MySpace" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			"LinkedIn" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			"Foursquare" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ) 
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => false,

		"debug_file" => "",
	);
        $hybridauth = new Hybrid_Auth( $config );
		$adapter = $hybridauth->authenticate( "Google" );
		$user_contacts = $adapter->getUserContacts();
		echo "<pre>";print_r($user_contacts);exit;
		 echo "<table>";
		 foreach($user_contacts as $key=>$contacts)
		 {
			if($key%4==0)
			{
				echo "<tr>";
			}
			
			echo "<td><a href='".$contacts->profileURL."'><img src='".$contacts->photoURL."'/></a></td><td>".$contacts->displayName."</td>";
			if($key%4==3 || ($key+1)==count($user_contacts))
			{
				echo "</tr>";
			}
		 }
		 echo "</table>";exit;
		 //echo "<pre>";print_r($user_contacts);exit;
        
    }
}
