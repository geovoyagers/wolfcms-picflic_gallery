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
 * This plugin allows you to integrate picasa or flikr albums into your site.
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
 
/**
 * Root location where search plugin lives.
 */
 
define('PICFLIC_ROOT', PLUGINS_ROOT.DS.'picflic_gallery');
define('PICFLIC_URL', URL_PUBLIC.'wolf/plugins/picflic_gallery');
define('CACHE_PATH', PLUGINS_ROOT.DS.'picflic_gallery/cache');

Plugin::setInfos(array(
	'id'			=> 'picflic_gallery',
	'title'			=> __('Picflic Gallery'),
	'description'	=> __('Lets you integrate picasa albums or flickr photosets'),
	'version'		=> '1.0.0',
	'license'		=> 'GPL',
	'author'		=> 'Tina Keil',
	'website'		=> 'http://www.geovoyagers.de/',
	'update_url'	=> 'http://www.tk-doku.de/wolf/plugin-versions.xml',
	'require_wolf_version' => '0.6.0'
));

// Add the plugin's tab and controller
Plugin::addController('picflic_gallery', __('Picflic Gallery'));

/**
 * Get respective Feed from Flickr API.
 */
function flic_api_call($method,$key,$value) {
	$picflic_flic_auth = Plugin::getSetting('picflic_flic_auth', 'picflic_gallery');
	$picflic_flic_secret = Plugin::getSetting('picflic_flic_secret', 'picflic_gallery');
	$picflic_flic_token = Plugin::getSetting('picflic_flic_token', 'picflic_gallery');

	$signature = md5($picflic_flic_secret.
			"api_key".$picflic_flic_auth.
			"auth_token".$picflic_flic_token.
			"method".$method.
			"$key".$value
	);

	$api_url = "http://api.flickr.com/services/rest/?api_sig=".$signature;
	$api_url .= "&api_key=".$picflic_flic_auth;
	$api_url .= "&auth_token=".$picflic_flic_token;
	$api_url .= "&method=".$method;
	$api_url .= "&$key=".$value;

	$getfeed = cache_call($api_url,'FLIC_');
	$rsp = simplexml_load_string($getfeed);
	return $rsp;
}

/**
 * Save feed to cache dir, if cache is turned on.
 */
function cache_call($feed_url, $prepend='PICFLIC_') {
	global $picflic_cache_error;
	$picflic_cache_use = (int) Plugin::getSetting('picflic_cache_use', 'picflic_gallery'); // 0 = false, 1 = true
	$picflic_cache_time = (int) Plugin::getSetting('picflic_cache_time', 'picflic_gallery'); // Cache lifetime in m/s
	
	//set some variables for picasa cache
	$cache = CACHE_PATH.'/'.$prepend.md5($feed_url);
	$temp_dir = sys_get_temp_dir();

	if ($picflic_cache_use == 1) {	
		if (!defined('CHECK_TIMEOUT')) define('CHECK_TIMEOUT', 25);
		if(file_exists($cache) && filemtime($cache) > (time()-$picflic_cache_time) && filesize($cache) > 0) {
			//cached - load the file from local directory.
			$cacheurl = PICFLIC_URL.'/cache/'.$prepend.md5($feed_url);
			$rsp = getContentFromUrl($cacheurl);
		} else {
			//not cached - grab a live version of the data 
			$rsp = @getContentFromUrl($feed_url);
			if (strlen($rsp) > 0) {
				$temp_name = tempnam($temp_dir,$prepend);
				if (file_exists($temp_name) && is_writable(CACHE_PATH)) {
					file_put_contents($temp_name, $rsp);
					rename($temp_name, $cache);
					@chmod($cache,0644);
				} else {
					$picflic_cache_error = true;
				}
			}
		}
	} else {
		$rsp = @getContentFromUrl($feed_url);	
	}
	return $rsp;
}

/**
 * Display album from Picasa.
 */
function pic_gallery ($album_name, $perpage='', $show_title=false) {
	global $picflic_cache_error;
	
	//Get picasa settings
	$picflic_pic_user = Plugin::getSetting('picflic_pic_user', 'picflic_gallery');
	$picflic_pic_auth= Plugin::getSetting('picflic_pic_auth', 'picflic_gallery');
	$picflic_pic_large = Plugin::getSetting('picflic_pic_large', 'picflic_gallery'); //size of large images
	$picflic_pic_thumb = Plugin::getSetting('picflic_pic_thumb', 'picflic_gallery'); //size of thumbails
	$picflic_strlen_thumb = (int) Plugin::getSetting('picflic_strlen_thumb', 'picflic_gallery'); //Max. length of title below thumbs
	$picflic_text = $row_height = '';
	
	//Get general settings
	if (!empty($perpage)) {
		$picflic_perpage = (int) $perpage;
	} else {
		$picflic_perpage = (int) Plugin::getSetting('picflic_perpage', 'picflic_gallery'); // num. of images per page
	}
	$picflic_pic_getpage = (isset($_GET['p']) ? intval($_GET['p']) : 1); 
	$picflic_pic_getalbum = (isset($_GET['album']) ? htmlspecialchars(urldecode($_GET['album'])) : '');		
	$albumname = str_replace(' ', '', $album_name);
	//picasa does not like umlauts, so get rid of them
	$umlaute = array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/");
	$replace = array("a","o","u","A","O","U","ss");
	$albumname_clean = preg_replace($umlaute, $replace, utf8_decode($albumname));
	$external_feed = "http://picasaweb.google.com/data/feed/api/user/".$picflic_pic_user."/album/".$albumname_clean.'?authkey='.$picflic_pic_auth;
	$feedURL = cache_call($external_feed,'PIC_');
	
	if ($picflic_cache_error == true) {
		echo '<p><strong>'.__('Could\'nt save the feed to the cache. Make sure the cache folder is writeable!').'</strong></p>';
	}

	$sxml = @simplexml_load_string($feedURL);
	    
	if ($sxml) {
		//get album name and number of photos
		$counts = $sxml->children('http://a9.com/-/spec/opensearchrss/1.0/');
		$total = $counts->totalResults; 
		$totalpages = ceil($total / $picflic_perpage);
		$picflic_pic_count = 0;
		
		if (strtolower($album_name) == strtolower($picflic_pic_getalbum)) {
			$start = ($picflic_pic_getpage - 1) * $picflic_perpage;
			$stop = $start + $picflic_perpage - 1;
		} else { 
			//if the user is not currently paging through this album, show first page of this album
			$start = 0;
			$stop = $picflic_perpage - 1;
		}

		echo '<div class="picflic">'."\n";
		echo '<ul>'."\n";

		foreach ($sxml->entry as $entry) {
			$picflic_text = '';
			if ($picflic_pic_count >= $start && $picflic_pic_count <= $stop) {
				$title = $entry->summary; // picasa has no real title (title = filename), so descripiton is used for title
				$media = $entry->children('http://search.yahoo.com/mrss/');
				$picurl = $media->group->thumbnail[0]->attributes()->{'url'};

				//s72 is the standard thumbnail size for array [0] from picasa
				$large_url = str_replace('s72', $picflic_pic_large, $picurl);
				$thumb_url = str_replace('s72', $picflic_pic_thumb, $picurl);

				//find out thumbnail sizes for height and width attributes in image tag
				if (substr($picflic_pic_thumb,-1) != 'c') {

					if (function_exists('getimagesize')) {
						$thumb_size = getimagesize($thumb_url);
						$thumb_dims = $thumb_size[3]; //height + width
						$dims = $thumb_size[0] + 60;
					} else {
						$dims = substr($picflic_pic_thumb,1) + 60;
						$thumb_dims = '';
					}
					$row_height = ' style="height:'.$dims.'px"';
				} else {
					//cropped, so height is same as width and according to setting
					$dims = substr($picflic_pic_thumb,1,-2);
					$thumb_dims = 'width="'.$dims .'" height="'.$dims .'"';
				}

				if (trim($title)!='' && $show_title == true) {
					if (strlen($title) > $picflic_strlen_thumb && trim($title)!='') {
						$short_title = mb_substr($title,0,$picflic_strlen_thumb,'UTF-8');
						$picflic_text = '<span class="picflic_text">'.trim($short_title).'&hellip;</span>';
					} else {
						$picflic_text = '<span class="picflic_text">'.$title.'</span>';
					}
				} elseif (trim($title) == '' && $show_title == true) {
					$picflic_text = '<span class="picflic_text">&nbsp;</span>';
				}
				
				if (trim($title) == '') {
					$title = __("Image").' '.($picflic_pic_count + 1);
				}

				echo '<li'.$row_height.'><a href="'.$large_url.'" class="photo" rel="my-gallery" title="'.$title.'"><img src="'.$thumb_url.'" alt="'.$title.'" '.$thumb_dims.' /></a>'.$picflic_text.'</li>'."\n";
			}
			$picflic_pic_count++;
		}

		echo '</ul>'."\n";
		echo '</div>'."\n";

		if ($totalpages > 1) {
			echo '<div class="picflic_clear"></div>'."\n";
			paginate_picflic($totalpages, $picflic_pic_getpage, $album_name, $picflic_pic_getalbum);
		}
	} else {
		echo '<p><strong>'.__('There was a problem calling the feed. Check the album name and permissions or try again later.').'</strong></p>';
	}
}

/**
 * Display photoset from Flickr.
 */
function flic_gallery ($album_name, $perpage='', $show_title=false) {
	global $picflic_cache_error;
	
	//Get settings	
	$picflic_flic_userid = Plugin::getSetting('picflic_flic_userid', 'picflic_gallery'); 
	$picflic_flic_large = Plugin::getSetting('picflic_flic_large', 'picflic_gallery'); //size of large images
	$picflic_flic_thumb = Plugin::getSetting('picflic_flic_thumb', 'picflic_gallery'); //size of thumbails
	$picflic_strlen_thumb = (int) Plugin::getSetting('picflic_strlen_thumb', 'picflic_gallery'); //Max. length of title below thumbs
	$picflic_text = $row_height = '';
	
	//Get general settings
	if (!empty($perpage)) {
		$picflic_perpage = (int) $perpage;
	} else {
		$picflic_perpage = (int) Plugin::getSetting('picflic_perpage', 'picflic_gallery'); // num. of images per page
	}
	$picflic_flic_getpage = (isset($_GET['p']) ? intval($_GET['p']) : 1);
	$picflic_flic_getalbum = (isset($_GET['album']) ? htmlspecialchars(urldecode($_GET['album'])) : '');
	
	//get a list of all photosets and find out the photoset_id of the album to show
	$sxml_getlist = flic_api_call('flickr.photosets.getList','user_id',$picflic_flic_userid);
	
	if ($picflic_cache_error == true) {
		echo '<p><strong>'.__('Could\'nt save the feed to the cache. Make sure the cache folder is writeable!').'</strong></p>';
	}

	if ($sxml_getlist) {
		foreach ($sxml_getlist->photosets->photoset as $albuminfo) {
			if ($albuminfo->title == $album_name) {
				$album_id = $albuminfo['id'];
				//$album_desc = $albuminfo->description;
			}
		}
	}
	
	if (!empty($album_id)) {
		$sxml_photoset = flic_api_call('flickr.photosets.getPhotos','photoset_id',$album_id);
		
		if ($sxml_photoset) {
			$total = $sxml_photoset->photoset['total'];
			$totalpages = ceil($total / $picflic_perpage);
			$picflic_flic_count = 0;

			if (strtolower($album_name) == strtolower($picflic_flic_getalbum)) {
				$start = ($picflic_flic_getpage - 1) * $picflic_perpage;
				$stop = $start + $picflic_perpage - 1;
			} else {
				//if the user is not currently paging through this album, show first page of this album
				$start = 0;
				$stop = $picflic_perpage - 1;;
			}

			echo '<div class="picflic">'."\n";
			echo '<ul>'."\n";

			foreach ($sxml_photoset->photoset->photo as $entry) {
				if ($picflic_flic_count >= $start && $picflic_flic_count <= $stop) {
					$title = $entry['title'];
					
					$picurl = 'http://farm'.$entry['farm'].
						'.static.flickr.com/'.$entry['server'].
						'/'.$entry['id'].
						'_'.$entry['secret'].
						$picflic_flic_thumb.
						'.jpg';

					$large_url = str_replace($picflic_flic_thumb, $picflic_flic_large, $picurl);
					$thumb_url = $picurl;

					if ($picflic_flic_thumb != '_s') {
						$row_height = '130';
						if (function_exists('getimagesize')) {
							$thumb_size = getimagesize($thumb_url);
							$thumb_dims = $thumb_size[3];
						} else {
							$thumb_dims = '';
						}
					} else {
						//cropped, so height is same as width and according to setting
						$thumb_dims = 'width="75" height="75"';
					}

					if (trim($title)!='' && $show_title == true) {
						if (strlen($title) > $picflic_strlen_thumb && trim($title)!='') {
							$short_title = mb_substr($title,0,$picflic_strlen_thumb,'UTF-8');
							$picflic_text = '<span class="picflic_text">'.trim($short_title).'&hellip;</span>';
						} else {
							$picflic_text = '<span class="picflic_text">'.$title.'</span>';
						}
					} elseif (trim($title) == '' && $show_title == true) {
						$picflic_text = '<span class="picflic_text">&nbsp;</span>';
					}

					if (trim($title) == '') {
						$title = __("Image").' '.($picflic_flic_count + 1);
					}

					echo '<li><a href="'.$large_url.'" class="photo" rel="my-gallery" title="'.$title.'"><img src="'.$thumb_url.'" alt="'.$title.'" '.$thumb_dims.' /></a>'.$picflic_text.'</li>'."\n";

				}
				$picflic_flic_count++;	
			}
			echo '</ul>'."\n";
			echo '</div>'."\n";

			if ($totalpages > 1) {
			echo '<div class="picflic_clear"></div>'."\n";
				paginate_picflic($totalpages, $picflic_flic_getpage, $album_name, $picflic_flic_getalbum);
			}
		}
	} else {
		echo '<p><strong>'.__('There was a problem calling the feed. Check the album name and permissions or try again later.').'</strong></p>';
	}
}

/**
 * Paging.
 */
function paginate_picflic($totalpages, $cur_page, $album_name, $current_album) {
	echo '<div class="picflic_paginator">'."\n";
	echo '<ul>'."\n";
	for($page_num=1; $page_num<=$totalpages; $page_num++) {
		if ($page_num == $cur_page && strtolower($album_name) == strtolower($current_album)) {
			echo '<li class="pageselected">'.$page_num.'</li>'."\n";
		} else {		
			if (USE_MOD_REWRITE == false) {
				echo '<li><a href="'.BASE_URL.CURRENT_URI.URL_SUFFIX.'?p='.$page_num.'&album='.urlencode($album_name).'">'.$page_num.'</a></li>'."\n";
			} else {
				echo '<li><a href="'.BASE_URL.CURRENT_URI.'/album/'.urlencode($album_name).'/'.$page_num.'">'.$page_num.'</a></li>'."\n";
			}
		}
	}
	echo '</ul>'."\n";
	echo '</div>'."\n";
}