== WHAT IT IS ==

This online gallery plugin allows you to integrate your
picasa web albums or Flickr photoset into any page of
your website. It makes use/provides the following features:

- Lightbox integration for showing of large images
- Title shown below large image or thumbnail
- Cropped / Standard thumbs (as available from picasa or Flickr)
- Integrate into any page or part
- Supports protected albums and photosets
- Caching of xml feeds
- Pagination
- No third party library needed
- Supports clean URLs (mod_rewrite)

== TO DO ==

- Improve picasa album name cleaning, for foreign characters
- Find a way to show larger Flickr thumbnails
- Enable deletion of specific cache files
- Expand language support (your help is appreciated)

== HOW TO USE IT ==

* To use the settings and documentation pages, you will first need to enable 
  the plugin!
* Important! Read the documentation on the plugin page, for explanations of 
  how certain features, authentification and settings work.
* Got to the plugins' setting page and enter your gallery user details
* Copy contents of "copy to your themes folder" to your themes folder
* Chmod the plugin folder "cache" to 777
* Update your style sheet as described in the plugin documentation
* For picasa albums use <?php pic_gallery ('Name of album'); ?>
* For Flickr photosets use <?php flic_gallery ('Name of your photoset'); ?>

== CHANGELOG ==

Version 1.0.3, 15/07/2014
- updated Flickr Authentification URI to https

Version 1.0.2, 18/08/2013
- added en-message.php

Version 1.0.1, 20/09/2011
- Fixed getimagesize problem, which slowed down everything

Version 1.0.0, 17/09/2011
- First release


== LICENSE ==

Copyright 2008-2009, Martijn van der Kleijn. <martijn.niji@gmail.com>
Plugin Author, Tina Keil, <seven@geovoyagers.de>
This plugin is licensed under the GPLv3 License.
<http://www.gnu.org/licenses/gpl.html>
