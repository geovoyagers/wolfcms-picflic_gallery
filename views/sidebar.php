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
 
?>
<p class="button"><a href="<?php echo get_url('plugin/picflic_gallery/documentation/'); ?>"><img src="<?php echo PICFLIC_URL; ?>/images/documentation.png" align="middle" alt="page icon" /> <?php echo __('Documentation'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/picflic_gallery/settings'); ?>"><img src="<?php echo PICFLIC_URL; ?>/images/settings.png" align="middle" alt="page icon" /> <?php echo __('Settings'); ?></a></p>
<div class="box">
<h2><?php echo __('Picflic Gallery plugin');?></h2>
<p>
<?php echo __('This plugin allows you to integrate an online gallery into your site.')?>
</p>
<p>
<?php echo __('See documentation for further details and use.')?>
</p>
</div>
