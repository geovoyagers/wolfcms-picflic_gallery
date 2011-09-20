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
 * @version 1.0.1
 * @since Wolf version 0.7.5
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Tina Keil, 2011+
 */

class PicflicGalleryController extends PluginController {

	public function __construct() {
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/picflic_gallery/views/sidebar'));
	}

	public function index() {
		$this->settings();
		$this->documentation();
	}

    public function documentation() {
		$this->display('picflic_gallery/views/documentation');
	}

	function settings() {
		$tmp = Plugin::getAllSettings('picflic_gallery');
		$settings = array('picflic_pic_user' => $tmp['picflic_pic_user'],
				'picflic_pic_auth' => $tmp['picflic_pic_auth'],
				'picflic_pic_large' => $tmp['picflic_pic_large'],
				'picflic_pic_thumb' => $tmp['picflic_pic_thumb'],
				'picflic_flic_user' => $tmp['picflic_flic_user'],
				'picflic_flic_auth' => $tmp['picflic_flic_auth'],
				'picflic_flic_secret' => $tmp['picflic_flic_secret'],
				'picflic_flic_token' => $tmp['picflic_flic_token'],
				'picflic_flic_userid' => $tmp['picflic_flic_userid'],
				'picflic_flic_large' => $tmp['picflic_flic_large'],
				'picflic_flic_thumb' => $tmp['picflic_flic_thumb'],
				'picflic_strlen_thumb' => $tmp['picflic_strlen_thumb'],
				'picflic_cache_use' => $tmp['picflic_cache_use'],
				'picflic_cache_time' => $tmp['picflic_cache_time'],
				'picflic_perpage' => $tmp['picflic_perpage']
		);

		$this->display('picflic_gallery/views/settings', $settings);
	}

	function flickrtoken() {
		$tmp = Plugin::getAllSettings('picflic_gallery');
		$settings = array('picflic_flic_user' => $tmp['picflic_flic_user'],
				'picflic_flic_auth' => $tmp['picflic_flic_auth'],
				'picflic_flic_secret' => $tmp['picflic_flic_secret'],
				'picflic_flic_token' => $tmp['picflic_flic_token'],
				'picflic_flic_userid' => $tmp['picflic_flic_userid']
		);
		
		$this->display('picflic_gallery/views/flickrtoken', $settings);	
	}

	function deletecache() {
		$cachedir = PLUGINS_ROOT.DS.'picflic_gallery/cache/'; 
		$d = dir($cachedir); 
		while($entry = $d->read()) { 
			if ($entry != '.' && $entry != '..') {
				$filepath = $cachedir.$entry;
				@chmod($filepath, 0666);
				unlink($filepath);
			}
		}
		$d->close(); 
		
		//check if folder is really empty
		$c = 0;
		if(is_dir($cachedir) ){
			$files = opendir($cachedir);
			while ($file=readdir($files)){ $c++; }
			if ($c > 2){ $ret = false; } else { $ret = true; }
		}
		if ($ret) {
			Flash::set('success', __('The cache has been deleted.'));
		}
		else {
			Flash::set('error', __('An error occured trying to delete the cache.'));
		}

		redirect(get_url('plugin/picflic_gallery/settings'));
	}
	
	function save() {
		if (isset($_POST['settings'])) {
			$settings = $_POST['settings'];
			foreach ($settings as $key => $value) {
				$settings[$key] = mysql_escape_string($value);
			}

			$ret = Plugin::setAllSettings($settings, 'picflic_gallery');

			if ($ret) {
				Flash::set('success', __('The settings have been saved.'));
			}
			else {
				Flash::set('error', __('An error occured trying to save the settings.'));
			}
		}
		else {
			Flash::set('error', __('Could not save settings, no settings found.'));
		}
		redirect(get_url('plugin/picflic_gallery/settings'));
	}
}