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
#documentation h3 {
  margin-bottom:2px;
  margin-top:15px;
}
#documentation h4 {
  color: #76A83A;
  font-size: 1em;
  font-weight:bold;
  margin-top:10px;
  margin-bottom: 5px;
}
#documentation small {
  font-size:0.7em;
  padding-top:5px;
}
</style>
<h1><?php echo __('Documentation'); ?></h1>
<div id="documentation">
<h2>The basics of how the picflic gallery works</h2>
<p>Both <a href="https://picasaweb.google.com/home" target="_blank">picasa</a> and <a href="http://www.flickr.com" target="_blank">Flickr</a> provide an RSS feed of your galleries there. The RSS feed is a simple structured text/xml file, which includes all the information about your photos in picasa or Flickr. The picflic gallery calls/downloads these RSS feeds and processes them. Because calling the RSS feeds from picasa or Flickr can take some time, picflic offers caching support. This means that the RSS feed is not downloaded each time you want to display a gallery. Instead it calls the feed every 2 hours or once a week (according to your cache settings and how often you think you will upload new photos) and saves the RSS feed on your server in the plugin cache folder. So the next time the plugin is called, it looks to see if a local RSS feed in the cache folder exists and uses that instead of downloading it again from picasa or Flickr. This speeds up the gallery considerably. I strongly recommend using the cache feature. If you add new photos to your picasa or Flickr gallery you should delete the cache (on the picflic settings page), so that the newest version of the RSS Feed is downloaded.</p>

<h2>Overview</h2>
<ul>
<li><a href="#usage">How to use, after installation</a></li>
<li><a href="#picasa">Setup for picasa web albums</a></li>
<li><a href="#flickr">Setup for Flickr photosets</a></li>
<li><a href="#jscss">Adding the jquery lightbox and style sheets</a></li>
<li><a href="#modrewrite">Rewrite rules for Apache and lighttpd</a></li>
</ul>

<a name="usage"></a>
<h2>How to use, after installation</h2>
<p>After you have uploaded the plugin to your plugin folder, you must make sure that the "cache" folder is writeable. chmod it to 777 with your ftp client. If you don't do this, the plugin will not be able to cache/save the external RSS-feeds.</p>
<p>After the plugin has been installed successfully, you will first need to enter correct user information on the picflic plugin settings page, which will allow you to access your picasa and/or Flickr gallery. If you only use picasa you can of course ignore the Flickr settings and visa versa. If you have galleries on both picasa and Flickr you can show them on one page or however and where ever you wish. The user details for picasa albums are relatively easy and short. The Flickr gallery user details require you to authenticate this plugin first. In general the setup is a bit more time consuming. Please follow the instructions below carefully.</p>

<a name="picasa"></a>
<h3>Setup for picasa web albums</h3>
<h4>Step 1: Find out your picasa album username</h4>
<p>Log in to your picasa web albums account at <a href="https://picasaweb.google.com" target="_blank">https://picasaweb.google.com</a>. Go to the settings page of your picasa account. On this page you will see a setting for <strong>"Your gallery URL"</strong>. If you haven't changed the URL, it will be something like "https://picasaweb.google.com/23423492837412". The number would be your picasa username. However, I would recommend that you change the URL, e.g. use your username for the gallery URL. This will allow you to call your gallery via a URL like "https://picasaweb.google.com/mygalleryname". In that case "mygalleryname" would then be your picasa username.</p>

<h4>Step 2: Find out your picasa auth key</h4>
<p>On the same page as above you will find a further setting called <strong>"Your unlisted gallery URL"</strong>. You will find a URL there ending with "?authkey=". Everything after the equal sign is the auth key. Copy this and enter it into your picflic gallery settings. The auth key will allow you to display protected albums on your wolfcms website. However, please note! It only supports the protection setting "Limited, anyone with the link". This means that that album is not shown publically, but can be accessed by anyone if they know the link to the album. Setting the protection of an album any higher than that, e.g. to "Limited or Only you" will not allow the plugin to gain access to the album, even if you have entered the auth key. This is normal behaviour for Google picasa web albums. If all your albums are public, then you can leave the auth key field blank.</p>

<h4>Step 3: Displaying a particular album on a page</h4>
<p>First you need to know the name of your album. Look this up in your picasa web album. Then you need to include the function <strong>"pic_gallery"</strong> in the page where you want to display the album. The "pic_gallery" function has three variables that you can pass to it, the last two would overwrite the standard settings: 1) The album name, 2) the number of images to display per page and 3) show the title of the image below the thumbnail (true of false).</p>
<p>The simplest form of calling a particular picasa web album is:</p>
<pre>&lt;?php pic_gallery ('Christmas Party'); ?&gt;</pre>
<p>If you want to display this album with album specific settings that override that standard picflic settings, like showing only 6 images per page and show the title of the image below the thumbnail, then you can call it as show below. But note, if the album only has 3 images, only 3 can be shown.</p>
<pre>&lt;?php pic_gallery ('Christmas Party','6',true); ?&gt;</pre>
<p>&nbsp;</p>

<a name="flickr"></a>
<h3>Setup for Flickr photosets</h3>
<h4>Step 1: Find out your Flickr username</h4>
<p>Log into your Flickr account. On the right hand side at the very top of the page you will see <strong>"Signed in as"</strong>. The name there is your Flickr username.</p>

<h4>Step 2: Apply for a Flickr Api-Key and Api-Secret</h4>
<p>Unlike picasa Flickr allows you to fully protect your albums, but still allows you to integrate them on your website. This means you can fully restrict access to your photos to anyone on Flickr and still show them on your website. Flickr will give you an Api-Key for each application you want to use with Flickr. So we need to get a new Api-Key for this plugin. There are many ways to Rome, so this is just one way to do this. In your account, go the "App Garden" and create an Application, or go there directly via <a href="http://www.flickr.com/services/apps/create/apply" target="blank">http://www.flickr.com/services/apps/create/apply</a>. Select <strong>"APPLY FOR A NON-COMMERCIAL KEY"</strong>. A new page asking for some details about the application will follow. Enter any name you like for the application name, I recommend "picflic". Enter a short description of the application, I recommend "picflic wolfcms plugin". Check the two checkboxes at the bottom and click on submit. If all goes well you will be forwarded to a new page which will show you your new Api-Key and Api-Secret. Note these down, go onto step 3 and follow the instructions further.</p>

<h4>Step 3: Setup the "auth flow", get your Api-Token and User ID</h4>
<p>Go back to your wolfcms admin area and go the picflic settings page. Enter the Api-Key and Api-Secret as well as your Flickr username as noted previously. Now click on the link called <strong>"Get token / User ID"</strong>. Here, further instructions are given and you are shown what your <strong>callback URL</strong> is. Follow the instructions there and if all goes well, you should be shown your Flickr Api-Token and User ID, after you have authorized that the app can have read access to your Flickr account. Note these down and enter them into the relevant fields in your picflic settings page. If when clicking on "Get your Token now" no token or User ID are shown, then check that you have entered the correct callback URL on the Flickr auth flow settings page of your Flickr picflic application.</p>

<h4>Step 4: Displaying a particular photoset on a page</h4>
<p>Important to know here is, that the photos you want to display must belong to a <strong>"photoset"</strong>. photosets are similar to albums in picasa. When you have added some photos to a set in Flickr, note down the name of the set. Then you need to include the function <strong>"flic_gallery"</strong> in the page where you want to display the photoset. The "flic_gallery" function has three variables that you can pass to it. The last two would overwrite the standard settings: 1) The album name, 2) the number of images to display per page and 3) show the title of the image below the thumbnail (true of false):</p>
<pre>&lt;?php flic_gallery ('Christmas Party'); ?&gt;</pre>
<p>If you want to display this photoset with photoset specific settings that override that standard picflic settings, like showing only 6 images per page and show the title of the image below the thumbnail, then you can call it as show below. But note, if the album only has 3 images, only 3 can be shown.</p>
<pre>&lt;?php flic_gallery ('Christmas Party','6',true); ?&gt;</pre>

<a name="jscss"></a>
<h2>Adding the jquery lightbox and style sheets</h2>
<p>The gallery uses the <a href="http://fancybox.net" target="_blank">fancybox</a> lightbox. It requires jquery library. If you already have the <strong>"Fancy Image Gallery plugin"</strong> installed, then you don't need add the fancybox js and css again. In any case you will need to add the picflic styles for the paging to your main css.</p>
<h3>Add jquery and fancybox scripts to your layout</h3>
<p>In the plugin folder called <strong>"copy to your themes folder"</strong> copy the files in the <strong>"js"</strong> folder to <strong>your</strong> themes folder. If your themes folder is not called wolf, adjust the URLs below accordingly. You can add the js files to the head section of your layout, but for faster loading, I recommend adding the jquery and fancybox js at the bottom of your layout (just before the ending &lt;/body&gt; tag), as follows:</p>
<pre>
&lt;script type="text/javascript" src="/wolfcms/public/themes/wolf/js/jquery.min.js"&gt;&lt;/script&gt;
&lt;script type="text/javascript" src="/wolfcms/public/themes/wolf/js/jquery.fancybox-1.3.4.pack.js"&gt;&lt;/script&gt;
&lt;script type="text/javascript" src="/wolfcms/public/themes/wolf/js/fancybox.js"&gt;&lt;/script&gt;
</pre>
<p>If you want to, you can add the code in fancybox.js to the end of jquery.fancybox-1.3.4.pack.js. This way you can save one call to an external .js file, e.g. you can exclude the call to fancybox.js. If you want to change the look and feel of the fancybox lightbox, go to <a href="http://fancybox.net" target="_blank">http://fancybox.net</a>. You will find all documentation there.</p>

<h3>Add fancybox css to your layout</h3>
<p>In the plugin folder called <strong>"copy to your themes folder"</strong> copy the files in the <strong>"images"</strong> folder to <strong>your</strong> themes folder. If your themes folder is not called wolf, adjust the URLs below accordingly. Now include the fancybox css in the header of your layout, somewhere between the &lt;head&gt; and &lt;/head&gt; tags, as follows:</p>
<pre>
&lt;link href="/wolfcms/public/themes/wolf/images/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css"&gt;
</pre>

<h3>Add specific picflic styles to your main style sheet</h3>
<p>I recommend that you add the following styles to your main style sheet. You can of course modify them to your liking. By the way the .photo style is compatible with the "<a href="http://www.wolfcms.org/repository/7" target="_blank">Fancy Image Gallery Plugin</a>", so if you have installed that plugin already, you don't need to add that style again, as you will have some of them already in your style sheet.</p>

<pre>
/* picflic gallery styles */
.picflic ul {
  margin:0 !important;
}

.picflic li {
  float:left;
  list-style-type:none !important;
  margin:0 0 6px 0;
  padding:0 !important;
  /* uncomment and adjust for uncropped images */
  /* height:140px; */
}

.picflic_text {
  padding: 0 0 0 4px;
  font-size:0.9em;
  display:block;
  clear:left;
}

.picflic_clear {
  clear:both;
}

/* picflic Paging */
.picflic_paginator ul {
  margin:0 !important;
}

.picflic_paginator {
  font-size:0.8em;
  overflow:auto;
  padding:4px;
}
	
.pageselected {
  font-size:1.1em;
  font-weight:bold;
  color:#ddd;
  background:#555;
  border:1px solid #666 !important;
  padding:1px 3px 1px 2px !important;
  margin:0 2px 0 2px !important;
}

.picflic_paginator li, .pageselected {
  font-size:1.1em;
  line-height: 1.4em;
  border:1px solid #a6a6a6;
  padding:1px 6px 0px 5px !important;
  margin:0 7px 0 0!important;
  list-style-type:none !important;
  float:left;
  display:block;
}
  
a:hover.page, a:focus.page {
  color:#444;
  background:#f0f0f0;
}

.photo { 
  padding: 5px; 
  margin: 5px; 
  border: 1px solid #999; 
  display: block; 
  float: left; 
}

.photo:hover, .photo:focus { 
  border-color:#ccc; 
}
</pre>

<a name="modrewrite"></a>
<h2>Rewrite rules for Apache and lighttpd</h2>
<p>The picflic gallery plugin will detect if you have mod_rewrite enabled and change the pagination links accordingly. Below, I have listed  suitable mod_rewrite syntax for both Apache and lighttpd. If you modify the syntax below to your own taste, you will need to change the pagination function, etc. in the index.php of the plugin accordingly. Only, do so if you know what you're doing.</p>
<h3>For Apache web server</h3>
<p>Change your .htaccess accordingly</p>
<pre>
#add this before the RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)/album/(.*)/([0-9]+)$ index.php?WOLFPAGE=$1&album=$2&p=$3 [L,QSA]
</pre>
<h3>For lighttpd web server</h3>
<p>Change your 10-rewrite.conf accordingly</p>
<pre>
url.rewrite-once = (
  "^(.*)/album/(.*)/([0-9]+)$" => "/index.php?WOLFPAGE=$1&album=$2&p=$3"
)
</pre>

<hr />
<small>Version 1.0.0, 16.09.2011, Tina Keil &lt;seven@geovoyagers.de&gt;</small>
</div>

