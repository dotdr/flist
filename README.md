=== Plugin Name ===<br>
Contributors: drdot<br>
<br>
Flist the File Lister.  Just a nice simple wordpress plugin that produces a list of files in a folder along with dynamically generated thumbnails. <br>
== Description ==<br>
<br>
Flist the File Lister.  Just a nice simple wordpress plugin that produces a list of files in a folder along with dynamically generated thumbnails. <br>
<br>
Supports multiple directories, sub-directories, size and name suppression.<br>
Open source, free to use.<br>
Actually works and displays thumbnails.<br>
Generates thumbnails dynamically on first access to the folder, or when a thumbnail is required - eg new file is added.<br>
<br>
<br>
<br>
== Bugs and Features ==<br>
probably, it doesnt handle corrupt files in the image maker function<br>
<br>
== Installation ==<br>
<br>
1. Upload `flist.php` to the `/wp-content/plugins/` directory<br>
1. Activate the plugin through the 'Plugins' menu in WordPress<br>
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates<br>
<br>
to add additional folders <br>
   open admin\class-flist-admin.php<br>
   search for define("MAXFLIST",2);    (about line 81)<br>
   Change value to the number of folders needed.<br>
   <br>
== To USE == <br>
<br>
Add the shortcode to any section on the page - change the value to match the shortcode id on the admin page<br>
[flist id=1]<br>
<br>
<br>
== Thanks ==<br>
Thanks to https://wppb.me/ for the skeleton<br>
Thanks to stackoverflow for various bits of leading and misleading information.<br>
Thanks to https://developer.wordpress.org/ for documentation<br>
<br>
No significant amount of coffee was used in the production of Flist<br>
(mostly annoyance at having to write a working replacement for something i bought that did'nt work)<br>

