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
 
$PDO = Record::getConnection();
 
// Store settings
$settings = array('picflic_pic_user' => '',
				'picflic_pic_auth' => '',
				'picflic_pic_large' => 's640',
				'picflic_pic_thumb' => 's104-c',
				'picflic_flic_user' => '',
				'picflic_flic_auth' => '',
				'picflic_flic_secret' => '',
				'picflic_flic_token' => '',
				'picflic_flic_userid' => '',
				'picflic_flic_large' => '_z',
				'picflic_flic_thumb' => '_s',
				'picflic_strlen_thumb' => '15',
				'picflic_cache_use' => '0',
				'picflic_cache_time' => '7200',
				'picflic_perpage' => '12',
				);

Plugin::setAllSettings($settings, 'picflic_gallery');

exit();