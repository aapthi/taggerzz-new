<?php
namespace ScnSocialAuth\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SocialSignInButton extends AbstractHelper
{
    public function __invoke($provider, $redirect = false,$basePath)
    {
        $redirectArg = $redirect ? '?redirect=' . $redirect : '';
        //echo '<a class="btn" href="'. $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider)). $redirectArg . '">' . ucfirst($provider) . '</a>';
		//echo '<a onclick="login(\''. $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider)). $redirectArg . '\')" href="#"><img src="/images/site/' . ucfirst($provider) . '.jpg"/></a>';
		/*echo '<a onclick="openPopup(\''. $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider)). $redirectArg . '\')" href="#"><img src="' . $basePath . '/images/site/' . ucfirst($provider) . '.jpg"/></a>';*/
		echo '<a onclick="openPopup(\''. $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider)). $redirectArg . '\')" href="#"><img id="imgFBLogin" class="imgSocialImage" src="' . $basePath . '/images/socialimages/' . ucfirst($provider) . '.png"/></a>';
		

	}
}
?>
<script>

</script>