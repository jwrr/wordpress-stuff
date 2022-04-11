<?php
/*
 Name: JWRR Functions
 URI: http://jwrr.com/wp/plugins/jwrr-pack
 Description: contains may small plugins and shortcodes
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: GPL3
*/


// ====================================================================
// FUNCTIONS FOR CATARTISTS.ORG


//  Hide the admin bar for all users except the administrator
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}


function jwrr_is_logged_in()
{
  $user = _wp_get_current_user();
  return $user->exists();
}


function jwrr_get_username()
{
  $user = _wp_get_current_user();
  return $user->user_login;  
}


function jwrr_get_userid($username='')
{
  if ($username == ''){
     $user = _wp_get_current_user();
  } else {
     $user = get_user_by('login', $username);
     if ($user == false) return 0;
  }
  return $user->ID;
}


function jwrr_get_firstname($username='')
{
  if ($username == ''){
     $user = _wp_get_current_user();
  } else {
     $user = get_user_by('login', $username);
  $first = $user->first_name;
  $last  = $user->last_name;
     if ($user == false) return "";
  }
  $first = $user->first_name;
  $last  = $user->last_name;
  return $user->first_name;
}


function jwrr_get_lastname($username='')
{
  if ($username == ''){
     $user = _wp_get_current_user();
  } else {
     $user = get_user_by('login', $username);
     if ($user == false) return "";
  }
  return $user->last_name;
}


function jwrr_get_fullname($username='')
{
  $full_name = jwrr_get_firstname($username) . ' ' . jwrr_get_lastname($username);
  return $full_name;
} 
 
 
function jwrr_mkdir($path, $permissions = 0777)
{
  return is_dir($path) || mkdir($path, $permissions, true);
}


function get_post_search_string($search_string='', $search_key = 'artsearch')
{
  if ($search_string=='' && $_POST['artsearch']) {
    $search_string = htmlspecialchars($_POST['artsearch']);
    $search_string = str_replace('/', '\/', $search_string);
  }
  return $search_string;
}


function search_and_show_images($path= "art/*/small/*jpg", $search_string="")
{
  
  $search_string = get_post_search_string();
  
  // print "search='$search_string'";
  
  $images = glob($path);
  $search_words = explode(" ", $search_string);
  foreach ($search_words as $search_word) {
    $images = preg_grep("/^.*$search_word.*/i", $images);    
  }
  
  shuffle($images);
  $images = array_slice($images, 0, 100);
  
  echo "<div id=\"theme_inner\">";
  foreach($images as $image)
  {
    $url = str_replace('.jpg', '', $image);
    $url = str_replace('/small', '', $url);
    echo "<a href='/index.php/show/$url'><img src=\"/$image\" class=\"small\" loading=\"lazy\"></a>\n";
  }
  echo '   <div style="clear:both"></div>';
  echo '   Please note that all copyright and reproduction rights remain with the artist.';
  echo '  </div>';
}
