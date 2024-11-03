=== Plugin Name ===
Contributors: drdot
Donate link: https://itmustbebunnies.com.au/filelist/
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Flist the File Lister.  Just a nice simple wordpress plugin that produces a list of files in a folder along with dynamically generated thumbnails. 
== Description ==

TFlist the File Lister.  Just a nice simple wordpress plugin that produces a list of files in a folder along with dynamically generated thumbnails. 

Supports multiple directories, sub-directories, size and name suppression.
Open source, free to use.
Actually works and displays thumbnails.
Generates thumbnails dynamically on first access to the folder, or when a thumbnail is required - eg new file is added.



== Bugs and Features ==
probably, it doesnt handle corrupt files in the image maker function

== Installation ==

1. Upload `flist.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

to add additional folders 
   open admin\class-flist-admin.php
   search for define("MAXFLIST",2);    (about line 81)
   Change value to the number of folders needed.
   
== To USE == 

Add the shortcode to any section on the page - change the value to match the shortcode id on the admin page
[flist id=1]

== Changelog ==

= 1.0 =
* Release


== Upgrade Notice ==


== Thanks ==
Thanks to https://wppb.me/ for the skeleton
Thanks to stackoverflow for various bits of leading and misleading information.
Thanks to https://developer.wordpress.org/ for documentation

No significant amount of coffee was used in the production of Flist
(mostly annoyance at having to write a working replacement for something i bought that did'nt work)
