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
<h1><?php echo __('Settings'); ?></h1>

<form action="<?php echo get_url('plugin/picflic_gallery/save'); ?>" method="post">
	<fieldset style="padding: 0.5em;">
		<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('General settings'); ?></legend>
		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="label"><label for="picflic_cache_use"><?php echo __('Use Cache'); ?>? </label></td>
				<td class="field">
					<select name="settings[picflic_cache_use]" id="picflic_cache_use">
						<option value="1" <?php if($picflic_cache_use == "1") echo 'selected = "selected";' ?>><?php echo __('Yes'); ?></option>
						<option value="0" <?php if($picflic_cache_use == "0") echo 'selected = "selected";' ?>><?php echo __('No'); ?></option>
					</select>
			</td>
			<td class="help"><?php echo __('Cache the external xml files?'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_cache_time"><?php echo __('Cache Lifetime'); ?>: </label></td>
				<td class="field">
					<select name="settings[picflic_cache_time]" id="picflic_cache_time">
						<option value="7200" <?php if($picflic_cache_time == "7200") echo 'selected = "selected";' ?>>2 <?php echo __('hours'); ?></option>
						<option value="21600" <?php if($picflic_cache_time == "21600") echo 'selected = "selected";' ?>>6 <?php echo __('hours'); ?></option>
						<option value="43200" <?php if($picflic_cache_time == "43200") echo 'selected = "selected";' ?>>12 <?php echo __('hours'); ?></option>
						<option value="86400" <?php if($picflic_cache_time == "86400") echo 'selected = "selected";' ?>>1 <?php echo __('Day'); ?></option>
						<option value="604800" <?php if($picflic_cache_time == "604800") echo 'selected = "selected";' ?>>1 <?php echo __('Week'); ?></option>
						<option value="2678400" <?php if($picflic_cache_time == "2678400") echo 'selected = "selected";' ?>>1 <?php echo __('Month'); ?></option>
					</select>
					&nbsp;<a href ="<?php echo get_url('plugin/picflic_gallery/deletecache'); ?>"><?php echo __('Delete Cache'); ?></a>
				</td>
				<td class="help"><?php echo __('How long should the cache files be retained?'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_perpage"><?php echo __('Max. images per page'); ?>: </label></td>
				<td class="field">
					<input type="text" name="settings[picflic_perpage]" id="picflic_perpage" value="<?php echo $picflic_perpage; ?>" />
				</td>
				<td class="help"><?php echo __('No. of images shown per page'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_strlen_thumb"><?php echo __('Max. title length'); ?>: </label></td>
				<td class="field">
					<input type="text" name="settings[picflic_strlen_thumb]" id="picflic_strlen_thumb" value="<?php echo $picflic_strlen_thumb; ?>" />
				</td>
				<td class="help"><?php echo __('Max. string length of title shown below thumbnails'); ?></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset style="padding: 0.5em;">
		<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Picasa settings'); ?></legend>
		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="label"><label for="picflic_pic_user"><?php echo __('Username'); ?>: </label></td>
				<td class="field">
					<input type="text" name="settings[picflic_pic_user]" id="picflic_picasa_user" maxlength="50" value="<?php echo $picflic_pic_user; ?>" />
				</td>
				<td class="help"><?php echo __('Your Picasa Username'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_pic_auth"><?php echo __('Authkey'); ?>: </label></td>
				<td class="field">
					<input type="text" name="settings[picflic_pic_auth]" id="picflic_picasa_auth" maxlength="150" value="<?php echo $picflic_pic_auth; ?>" />
				</td>
				<td class="help"><?php echo __('Required to access protected albums.'); ?></td>
			</tr>
			<tr>
			<td class="label"><label for="picflic_pic_large"><?php echo __('Large image size'); ?>: </label></td>
				<td class="field">
					<select name="settings[picflic_pic_large]" id="picflic_pic_large">
					<option value="s512" <?php if($picflic_pic_large == "s512") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 512 <?php echo __('Pixel'); ?></option>
					<option value="s567" <?php if($picflic_pic_large == "s567") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 567 <?php echo __('Pixel'); ?></option>
					<option value="s640" <?php if($picflic_pic_large == "s640") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 640 <?php echo __('Pixel'); ?></option>
					<option value="s720" <?php if($picflic_pic_large == "s720") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 720 <?php echo __('Pixel'); ?></option>
					<option value="s800" <?php if($picflic_pic_large == "s800") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 800 <?php echo __('Pixel'); ?></option>
					<option value="s912" <?php if($picflic_pic_large == "s912") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 912 <?php echo __('Pixel'); ?></option>
					<option value="s1024" <?php if($picflic_pic_large == "s1024") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 1024 <?php echo __('Pixel'); ?></option>
					<option value="s1152" <?php if($picflic_pic_large == "s1152") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 1152 <?php echo __('Pixel'); ?></option>
					<option value="s1280" <?php if($picflic_pic_large == "s1280") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 1280 <?php echo __('Pixel'); ?></option>
					<option value="s1140" <?php if($picflic_pic_large == "s1140") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 1440 <?php echo __('Pixel'); ?></option>
					<option value="s1600" <?php if($picflic_pic_large == "s1600") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 1600 <?php echo __('Pixel'); ?></option>
					</select>
				</td>
				<td class="help"><?php echo __('Size of images shown in lightbox'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_pic_thumb"><?php echo __('Thumbnail size'); ?>: </label></td>
				<td class="field">
					<select name="settings[picflic_pic_thumb]" id="picflic_pic_thumb">
					<optgroup label="<?php echo __('Cropped (square) sizes'); ?>" style="background:#eee">
					<option value="s32-c" <?php if($picflic_pic_thumb == "s32-c") echo 'selected = "selected";' ?>>32 x 32 <?php echo __('Pixel'); ?></option>
					<option value="s48-c" <?php if($picflic_pic_thumb == "s48-c") echo 'selected = "selected";' ?>>48 x 48 <?php echo __('Pixel'); ?></option>
					<option value="s64-c" <?php if($picflic_pic_thumb == "s64-c") echo 'selected = "selected";' ?>>64 x 64 <?php echo __('Pixel'); ?></option>
					<option value="s72-c" <?php if($picflic_pic_thumb == "s72-c") echo 'selected = "selected";' ?>>72 x 72 <?php echo __('Pixel'); ?></option>
					<option value="s75-c" <?php if($picflic_pic_thumb == "s75-c") echo 'selected = "selected";' ?>>75 x 75 <?php echo __('Pixel'); ?></option>
					<option value="s104-c" <?php if($picflic_pic_thumb == "s104-c") echo 'selected = "selected";' ?>>104 x 104 <?php echo __('Pixel'); ?></option>
					<option value="s144-c" <?php if($picflic_pic_thumb == "s144-c") echo 'selected = "selected";' ?>>144 x 144 <?php echo __('Pixel'); ?></option>
					<option value="s150-c" <?php if($picflic_pic_thumb == "s150-c") echo 'selected = "selected";' ?>>150 x 150 <?php echo __('Pixel'); ?></option>
					<option value="s160-c" <?php if($picflic_pic_thumb == "s160-c") echo 'selected = "selected";' ?>>160 x 160 <?php echo __('Pixel'); ?></option>
					</optgroup>
					<optgroup label="<?php echo __('Uncropped sizes'); ?>" style="background:#eee">
					<option value="s94" <?php if($picflic_pic_thumb == "s94") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 94 <?php echo __('Pixel'); ?></option>
					<option value="s100" <?php if($picflic_pic_thumb == "s100") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 100 <?php echo __('Pixel'); ?></option>
					<option value="s110" <?php if($picflic_pic_thumb == "s110") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 110 <?php echo __('Pixel'); ?></option>
					<option value="s128" <?php if($picflic_pic_thumb == "s128") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 128 <?php echo __('Pixel'); ?></option>
					<option value="s200" <?php if($picflic_pic_thumb == "s200") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 200 <?php echo __('Pixel'); ?></option>
					<option value="s220" <?php if($picflic_pic_thumb == "s220") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 220 <?php echo __('Pixel'); ?></option>
					<option value="s228" <?php if($picflic_pic_thumb == "s228") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 228 <?php echo __('Pixel'); ?></option>
					<option value="s320" <?php if($picflic_pic_thumb == "s320") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 320 <?php echo __('Pixel'); ?></option>
					</optgroup>
					</select>
				</td>
				<td class="help"><?php echo __('Size of thumbnail in pixel'); ?></td>
			</tr>
		</table>
	</fieldset>
	<fieldset style="padding: 0.5em;">
		<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Flickr settings'); ?></legend>
		<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="label"><label for="picflic_flic_user"><?php echo __('Username'); ?>: </label></td>
				<td class="field">
					<input type="text" name="settings[picflic_flic_user]" id="picflic_flickr_user" maxlength="50" value="<?php echo $picflic_flic_user; ?>" />
				</td>
			<td class="help"><?php echo __('Your Flickr Username'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_flic_auth"><?php echo __('Api Key'); ?>: </label></td>
				<td class="field">
					<input type="text" name="settings[picflic_flic_auth]" id="picflic_flickr_auth" maxlength="150" value="<?php echo $picflic_flic_auth; ?>" />
				</td>
				<td class="help"><?php echo __('Your Flickr Api Key'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_flic_secret"><?php echo __('Api Secret'); ?>: </label></td>
				<td class="field">
					<input type="text" name="settings[picflic_flic_secret]" id="picflic_flickr_secret" maxlength="150" value="<?php echo $picflic_flic_secret; ?>" />
				</td>
				<td class="help"><?php echo __('Your Flickr Api Secret'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_flic_token"><?php echo __('Api Token'); ?>: </label></td>
				<td class="field">
					<input type="text" name="settings[picflic_flic_token]" id="picflic_flickr_token" maxlength="150" value="<?php echo $picflic_flic_token; ?>" />
					&nbsp;<a href ="<?php echo get_url('plugin/picflic_gallery/flickrtoken'); ?>"><?php echo __('Get token / User ID'); ?></a>
				</td>
				<td class="help"><?php echo __('Your Flickr Api Token'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_flic_userid"><?php echo __('Api User ID'); ?>: </label></td>
				<td class="field">
				    <input type="text" name="settings[picflic_flic_userid]" id="picflic_flickr_userid" maxlength="150" value="<?php echo $picflic_flic_userid; ?>" />
				</td>
				<td class="help"><?php echo __('Your Flickr User ID'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_flic_large"><?php echo __('Large image size'); ?>: </label></td>
				<td class="field">
					<select name="settings[picflic_flic_large]" id="picflic_flic_large">
					<option value="_m" <?php if($picflic_flic_large == "_m") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 240 <?php echo __('Pixel'); ?></option>
					<option value="_-" <?php if($picflic_flic_large == "_-") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 500  <?php echo __('Pixel'); ?></option>
					<option value="_z" <?php if($picflic_flic_large == "_z") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 640 <?php echo __('Pixel'); ?></option>
					<option value="_b" <?php if($picflic_flic_large == "_b") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 1024 <?php echo __('Pixel'); ?></option>
					</select>
				</td>
				<td class="help"><?php echo __('Size of images shown in lightbox'); ?></td>
			</tr>
			<tr>
				<td class="label"><label for="picflic_flic_thumb"><?php echo __('Thumbnail size'); ?>: </label></td>
				<td class="field">
					<select name="settings[picflic_flic_thumb]" id="picflic_flic_thumb">
					<optgroup label="<?php echo __('Cropped (square) sizes'); ?>" style="background:#eee">
					<option value="_s" <?php if($picflic_flic_thumb == "_s") echo 'selected = "selected";' ?>>75 x 75 <?php echo __('Pixel'); ?></option>
					</optgroup>
					<optgroup label="<?php echo __('Uncropped sizes'); ?>" style="background:#eee">
					<option value="_t" <?php if($picflic_flic_thumb == "_t") echo 'selected = "selected";' ?>><?php echo __('longest side'); ?> 100 <?php echo __('Pixel'); ?></option>   
					</optgroup>
					</select>
				</td>
				<td class="help"><?php echo __('Size of thumbnail in pixel'); ?></td>
			</tr>
		</table>
	</fieldset>
	<p class="buttons">
		<input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
	</p>
</form>

<script type="text/javascript">
// <![CDATA[
	function setConfirmUnload(on, msg) {
		window.onbeforeunload = (on) ? unloadMessage : null;
		return true;
	}

	function unloadMessage() {
		return '<?php echo __('You have modified this page. If you navigate away from this page without first saving your data, the changes will be lost.'); ?>';
	}

	$(document).ready(function() {
		// Prevent accidentally navigating away
		$(':input').bind('change', function() { setConfirmUnload(true); });
		$('form').submit(function() { setConfirmUnload(false); return true; });
	});
// ]]>
</script>