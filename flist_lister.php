<?php 
//flist_lister.php
//
// what does all the work in displaying the files in the folder
/*
* @link              https://itmustbebunnies.com.au/filelist
* @since             1.0.0
* @package           Flist
*
* @wordpress-plugin
* Plugin Name:       File List
* Plugin URI:        https://itmustbebunnies.com.au/filelist
* Description:       Flist the file lister
* Version:           1.0.0
* Author:            DrDot
* Author URI:        https://itmustbebunnies.com.au/filelist/
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       flist
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   die;
}

//get list of files and folders
if ( ! defined( 'ABSPATH' ) ) exit;             // Exit if accessed directly



function flist_query_vars( $qvars ) {     // define accessible get vars
   $qvars[] = 'folder';
   return $qvars;
}
add_filter( 'query_vars', 'flist_query_vars' );


function flist_function( $qvars ) {
      //todo - placing multiple flist on the same page is fine, but if one of the flist entries has a subfolder, the upper entry stops working when navigation happens
      
   //get flist ID and do some basic sanity checks
   for ($i=1; $i <= MAXFLIST; $i++) {
		$a = get_option("flist_plugin_option3_$i");
      if ($a == $qvars['id']) {
         $tsize = get_option("flist_plugin_option1_$i");
         $folder = trailingslashit(get_option("flist_plugin_option2_$i")) ;     //tidy up the folder incase the trailing slash was forgotten
         $hidesize = get_option("flist_plugin_option4_$i");
         $hidename = get_option("flist_plugin_option5_$i");
      }
   }
   if ($folder == "") {
      return "<br><h1 style=\"color:Tomato;\">ERROR No matching FLIST for ID " . $qvars['id'] . "</h1>";
   }
   if (!is_dir(WP_CONTENT_DIR . $folder)) {
      return "<br><h1 style=\"color:Tomato;\">ERROR No folder at " . WP_CONTENT_DIR . $folder . " for ID " . $qvars['id'] . "</h1>";
   }
   chdir(WP_CONTENT_DIR . $folder);
   // no failure on indecent thumbnail size, just check for something reasonable
   if (!is_numeric($tsize))  $tsize=100; 
   if ($tsize < 50) $tsize=100;
   $tsize .= "px";
   
   //useful arrays to skip some things 
   $imagetypes = ['png','jpg','gif','jpeg','svg',];   //array of image types that need no thumbnail
   $pdftypes = ['pdf','ps','ps2','ps3','eps','xps',]; // array of types to generate thumbnails for
   $skiptypes = ['.','..','index','',];   // files we just skip and dont even show


   //get subfolder if any from the url
   $subby = get_query_var('folder', '');
   if ($subby != '') $subby .= "/";    //add trailing / for navigation
   remove_query_arg('folder',false);

   $dPath = WP_CONTENT_DIR .  $folder . $subby;    // where the files to be listed are physically found
   $baseURL = content_url() .  $folder . $subby;      //link to the files
   if ( is_ssl() ) $baseURL = str_replace( 'http://', 'https://', $baseURL );   //fix url link

   //get an array of the files in the path
   if (!is_dir(rtrim($dPath,"/"))) return "";
   $files = scandir(untrailingslashit($dPath) , SCANDIR_SORT_ASCENDING);

   // list of files
   $return_string = "";

   // list of dir files - starts with the zoom style and the table header
   /* ( Note: if the zoom is too large, it will go outside of the viewport) */
   $pad = "10";
   $return_dir_string = "<style>
      .zoom {
        padding: $pad;
        background-color: none;
        transition: transform .2s; 
        width: $tsize;
        height: $tsize+$pad;
        margin: 0 auto;
      }
      .zoom:hover {
        transform: scale(2.0); 
      }
      </style>
  
      <table>";

   //setup for new thumbnail folder - always check and create this
   // we always copy folder and up icons to .thumbnails so everything looks like it is in the same place
   if (!is_dir($dPath . ".thumbnails"))                 mkdir($dPath . ".thumbnails");
   if (!file_exists($dPath . ".thumbnails/folder.jpg")) copy(plugin_dir_path( __FILE__ ) . "images/folder.jpg", $dPath . ".thumbnails/folder.jpg");  //copy the folder icon
   if (!file_exists($dPath . ".thumbnails/up.jpg"))     copy(plugin_dir_path( __FILE__ ) . "images/up.jpg", $dPath . ".thumbnails/up.jpg");  //copy the folder icon

   // add a up link if in a subdirectory at the top of the table
   if ($subby!='') {
      // navigate up/down the tree
      $r = explode("/",$subby . 'dummy');    //add dummy for the split
      if (count($r)>2) $s = $r[count($r)-3]; else $s = '';

      $return_dir_string .= "<tr>";
      $return_dir_string .= "<th><a href=" . esc_url(add_query_arg('folder',$s)) . ">";
      $return_dir_string .= "<img src=" . esc_url(rtrim($baseURL,"/") . "/.thumbnails/up.jpg") . " alt=\"UP\" style=img-magnifier-container > </th>";
      $return_dir_string .= "<th width=80> </th>";    // blank column
      $return_dir_string .= "<th><a href=" . esc_url(add_query_arg('folder',$s)) . "> UP </a></th>";
      $return_dir_string .= "</tr>";
   }

   // process the list of files and folders
   foreach($files as $f) {
      $fn = pathinfo($f);
      if (in_array($fn['filename'],$skiptypes)) continue;

      $imfn = "";    // image filename
      $image = "";   // source image

      if (is_dir($dPath . $f)) {

         $f = $subby . $f;
         // create visible table for directory
         $return_dir_string .= "<tr>";
         $return_dir_string .= "<th><a href=" . esc_url(add_query_arg('folder',$f)) . ">";
         $return_dir_string .= "<img src=" . esc_url(rtrim($baseURL,"/")  . "/.thumbnails/folder.jpg") . " alt=" . $imfn . " style=img-magnifier-container > </th>";
         $return_dir_string .= "<th width=80> </th>";
         $return_dir_string .= "<th><a href=" . esc_url(add_query_arg('folder',$f)) . ">" . $f . "</a></th>";
         $return_dir_string .= "</tr>";

      } else {

         //show files
         if (in_array($fn['extension'],$imagetypes)) {   // no need for thumbnails with these extensions
               $image = $baseURL . $f;
               $imfn = basename($f);
         } else {
            //check for image and generate if not exist

            if (in_array($fn['extension'],$pdftypes)) {     //these extensions need thumbnail generation
               $imfn = $fn['filename'] . ".jpg";
               $image = $baseURL . "/.thumbnails/" . $imfn;
               if (!file_exists($dPath . ".thumbnails/" . $imfn)) {  // create thumbnail if not already exist
                  flist_getimage($dPath . $f, $dPath . ".thumbnails/" . $imfn);
               }
            }
         }

         // create visible table for files
         if ($hidesize!="on") { $fsize = flist_filesize(filesize($dPath . "/" .$f),2); } else { $fsize = " ";} 
         if ($hidename!="on") { $fname = esc_url($baseURL . $f) . ">" . $f ; } else { $fname = " ";} 
         $return_string .= "<tr>";
         $return_string .= "<th><div class=\"zoom\"><a href=" .  esc_url($baseURL . $f) . ">";
         $return_string .= "<img src=" . esc_url($image) . " alt=" . $imfn . " style=img-magnifier-container ></div></th>";
         $return_string .= "<th width=80>" . $fsize . "</th>";
         $return_string .= "<th><a href=" .  $fname . "</a></th>";
         $return_string .= "</tr>";
      }
   }

   // create the final page and send it
   return $return_dir_string . $return_string . "<tr><th></th></tr></table>";

}

function flist_getimage($pdfPath, $imagePath) {

   if (!extension_loaded('imagick')) {
       return "Imagick not installed";
   }

   $imagick = new Imagick();
   // Read the first page of the PDF
   $imagick->setResolution(150, 150);
   $imagick->readImage($pdfPath . '[0]');
   $imagick->setImageFormat('jpg');          //there is almost no difference in size between jpeg and webp, but jpg looks better
   $imagick = $imagick->flattenImages();
   $imagick->writeImage($imagePath);

   // Clear the Imagick object
   $imagick->clear();
   $imagick->destroy();

}

function flist_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

?>