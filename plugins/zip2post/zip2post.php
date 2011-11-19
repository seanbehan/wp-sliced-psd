<?php

/*
Plugin Name: Zip2Post
Plugin URI: http:/seanbehan.com/
Description: Upload a slice/zip file exported from Dreamweaver/Photoshop and save it as a WP post.
Version: 0.1.0
Author: Sean Behan
Author URI: http://seanbehan.com 
*/

/** 
* References 
* http://codex.wordpress.org/Function_Reference/wp_insert_post
* http://codex.wordpress.org/Adding_Administration_Menus
* http://www.phpconcept.net/pclzip/
*/

define("ZIP2POSTDIR", "zip2post");
define("TIMENOW", time()); 

// for unpacking uploaded photoshop psd/slice package
if(!class_exists("PclZip")){
  include_once("pclzip.class.php");
}

// add admin page to handle the zip upload process
add_action('admin_menu', 'zip2postInit');
function zip2postInit() {
  add_posts_page('Upload splice', 'Upload splice', 'administrator', 'zip2post', 'zip2post');
}

// main function 
function zip2post(){ 
  if(isset($_POST['zip2post_nonce'])){
    if($_POST['zip2post_nonce'] != wp_create_nonce(__FILE__))
      die("<div class='wrap'><h2>Illegal Access Detected!</h2></div>");

    $filename = TIMENOW.'-'.strtolower($_FILES['zip2post_upload']['name']);
    $directory = TIMENOW.'-'.strtolower(basename($_FILES['zip2post_upload']['name'],".zip"));
    $destination = zip2postDir().'/'.$filename; 

    if( !move_uploaded_file($_FILES['zip2post_upload']['tmp_name'], $destination ))
      die("<div class='wrap'><h2>Failed to upload file!</h2></div>");

    if(!file_exists($destination))
      die("<div class='wrap'><h2>Could not locate uploaded file!</h2></div>");

    $extract_to_dir = zip2postDir().'/'.basename($destination, ".zip"); /* need full path on filesystem */
    $zip = new PclZip( $destination );

    if($zip->extract( $extract_to_dir )==0)
      die("<div class='wrap'><h2>Error extracting file!</h2></div>");
    
    unlink($destination); /* remove the .zip */
    
    $html = "";
    if(is_dir($extract_to_dir.'/Sliced')){
      if($handle = opendir($extract_to_dir.'/Sliced')){ /* will it always be in the 'Sliced' dir?  */
        while(($file = readdir( $handle )) != false){
          $pathinfo = pathinfo($file); 
          if($pathinfo['extension']=="html"){
            $html = file_get_contents($extract_to_dir.'/Sliced/'.$file); /* ever more than one .html file? */
          }
        }
        closedir($handle);
      }
      
      /* make img src attr absolute */
      $html = str_replace('src="',
        'src="'.zip2postUrl().'/'.$directory.'/Sliced/', $html);

      /* wordpress text editor wraps new lines which can break valid html */
      $html = str_replace("\n", " ", $html);
      $html = str_replace(">", ">\n", $html);
      
      /* save html as post */
      $my_post = array();
      $my_post['post_title'] = 'New Marketing Event Created '.date("D F d, Y");
      $my_post['post_content'] = trim($html);
      $my_post['post_status'] = 'draft';
      $my_post['post_author'] = 1; // the admin? 
      $post_id =  wp_insert_post( $my_post );
      $link = get_bloginfo('url').'/wp-admin/post.php?post='.$post_id.'&action=edit'; // next edit and publish w/ editor
    }    
  } ?>
  
  <?php if(isset($link)): ?><div class='wrap'>
    <h1><a href="<?php echo $link; ?>">UPLOAD SUCCESSFUL! CLICK HERE &rarr;</a></h1>
  </div><?php endif; ?>

  <?php if(!isset($_POST['zip2post_nonce'])): ?>
    <div class='wrap'><h2>Upload a zip from DreamWeaver/Photoshop</h2>
      <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <input type="file" name="zip2post_upload">
        <input type="hidden" name="zip2post_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>">
        <p><input type="submit" value="Continue &rarr;"></p>
      </form>
    </div>
    <?php endif; ?>
<?php 

} // ends function

// Get or make the zip2post directory
function zip2postDir(){
  $wp_upload_dir = wp_upload_dir(); //Array
  $upload_basedir = $wp_upload_dir['basedir'];  
  if(!file_exists($upload_basedir.'/'.ZIP2POSTDIR)){
    if(!mkdir($upload_basedir.'/'.ZIP2POSTDIR)){
      die("Could not write to uploads directory!");
    }
  }
return $upload_basedir.'/'.ZIP2POSTDIR;
}

function zip2postUrl(){
  $wp_upload_dir = wp_upload_dir(); // Array
  $upload_base_url = $wp_upload_dir['baseurl']; 
  return $upload_base_url.'/'.ZIP2POSTDIR;
}

