<?php
/*
 * Wolf CMS - Content Management Simplified. <http://www.wolfcms.org>
 * Copyright (C) 2008-2010 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Wolf CMS. Wolf CMS is licensed under the GNU GPLv3 license.
 * Please see license.txt for the full license text.
 */

/* Security measure */
if (!defined('IN_CMS')) { exit(); }

/**
 * This plugin allows you to integrate picasa or flikr online gallery on your site.
 *
 * @package plugins
 * @subpackage picflic_gallery
 *
 * @author Tina Keil <seven@geovoyagers.de>
 * @version 1.0.0
 
 * @since Wolf version 0.7.5
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Tina Keil, 2011+
 */
?>
<style type="text/css">
#documentation li, #documentation p {
  font-size: 0.8em;
  line-height: 1.6em;
  list-style: disc;
  margin-top:0;
}
#documentation ul, #documentation ol {
  margin-left: 25px;
}
#documentation span {
  letter-spacing: 0.3em;
}
#documentation pre {
  padding:5px;
  background:#eeffee;
  margin-bottom:10px;
}
#documentation h4 {
  color: #666;
  font-size: 1em;
  font-weight:bold;
}
#documentation small {
  font-size:0.7em;
  padding-top:5px;
}
</style>

<h1><?php echo __('Flickr Api Token'); ?></h1>
<div id="documentation">
<?php
	
	function getToken($secret, $api_key, $frob) {
		$token_sig = md5($secret . 'api_key' . $api_key . 'frob' . $frob . 'methodflickr.auth.getToken');
		$token_url = 'http://api.flickr.com/services/rest/?method=flickr.auth.getToken';
		$token_url .= '&api_key='.$api_key;
		$token_url .= '&frob='.$frob;
		$token_url .= '&api_sig='.$token_sig;
		$token_feed = getContentFromUrl($token_url);
		$rsp = simplexml_load_string($token_feed);
		return $rsp;
	}
	
	$error_msg = '';
	
	if (empty($picflic_flic_user)) {
		$error_msg .= '<li>'.__('You must first enter the <strong>Flickr Username</strong>').'</li>';
	}
	if (empty($picflic_flic_auth)) {
		$error_msg .= '<li>'.__('You must first enter the <strong>Flickr Api Key</strong>').'</li>';
	}
	if (empty($picflic_flic_secret)) {
		$error_msg .= '<li>'.__('You must first enter the <strong>Flickr Api Secret</strong>').'</li>';
	}
	
	$frobkey = (isset($_GET['frob']) ? $_GET['frob'] : '');
	$gettoken = (isset($_GET['gettoken']) ? $_GET['gettoken'] : '');
	$callback_url = get_url('plugin/picflic_gallery/flickrtoken')."?get=token";
	$gettoken_url = get_url('plugin/picflic_gallery/flickrtoken')."?gettoken=true";
	$doku_url = get_url('plugin/picflic_gallery/documentation')."#flickrauth";
	$perms = "read";

	if (empty($error_msg)) {
		if ($gettoken == true && empty($frobkey)) {	
			$signature = md5($picflic_flic_secret . 'api_key' . $picflic_flic_auth . 'perms' . $perms);
			$auth_url = 'http://www.flickr.com/services/auth/?api_key=' . $picflic_flic_auth . '&perms=' . $perms . '&api_sig=' . $signature; 
			header('Location: '.$auth_url);
			exit;
			
		} elseif (!empty($frobkey)) {
			$token_rsp = gettoken($picflic_flic_secret,$picflic_flic_auth,$frobkey);
			$auth_token = $token_rsp->auth->token;
			$nsid = $token_rsp->auth->user['nsid'];

			echo '<br /><p><strong>'.__('This is your Flickr Api Token').'</strong><br /><pre>'.$auth_token.'</pre></p>';
			echo '<br /><p><strong>'.__('This is your Flickr User ID').'</strong><br /><pre>'.$nsid.'</pre></p>';
			echo '<p>'.__('Return to the settings page and enter the token number and User ID to the Flickr Api settings.').'<br />';
			echo __('Be sure to save the settings.').'</p>';
		}
		
		if (empty($gettoken) && empty($frobkey)) {
			echo '<h4>'.__('Step 1: Set callback URL').'</h4>';
			echo '<p>'.__('Before you can get your Flickr Api Token you must set the callback URL.').'<br />'.__('Goto the following page:');
			echo ' <a href="http://www.flickr.com/services/apps/by/'.$picflic_flic_user.'" target="_blank">';
			echo "http://www.flickr.com/services/apps/by/".$picflic_flic_user."</a> ".__('(link opens new window)').'</p>';
			echo '<p>'.__('There, click on the title of your picflic App which you setup earlier. Then, on the right hand side look for "Edit the authentication flow" and click on the edit link. On that page enter the callback URL, exactly as shown below:').'<br />';
			echo '<pre>'.$callback_url.'</pre></p>';
			echo '<h4>'.__('Step 2: Get your Flickr Api Token').'</h4>';
			echo '<p>'.__('After you have saved the callback URL, click on the following link to get your Api Token:').'<br />';
			echo '<a href="'.$gettoken_url.'">'.__('Get your Token now').'</a>';
		}
		
	} else {
		echo '<h4>'.__('Sorry, you can\'t do this yet.').'</h4>';
		echo '<ul>';
		echo $error_msg;
		echo '</ul>';
		echo '<br /><p>'.__('Please read the').' <a href="'.$doku_url.'">'.__('picflic Documentation').'</a> ';
		echo __(', if you are not sure how to do this').'</p>';
	}
?>
</div>
