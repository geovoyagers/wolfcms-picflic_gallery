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

$PDO = Record::getConnection();

//remove plugin settings from db
Plugin::deleteAllSettings('picflic_gallery');

exit();