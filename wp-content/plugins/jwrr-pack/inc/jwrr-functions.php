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

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

//  Hide the admin bar for all users except the administrator
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}


function jwrr_clean($string)
{
  return preg_replace('/[^\w-]/u', '', $string);
}


function jwrr_clean_filename_lower($filename)
{
  $pieces = explode('.', $filename);
  $num_pieces = count($pieces);
  $filetype = '';
  if ($num_pieces > 1) {
    $filetype = $pieces[$num_pieces-1];
    $filetype = preg_replace('/[^A-Za-z]/', '', $filetype);
    array_pop($pieces);
  }
  $filename = implode('', $pieces);
  $filename = preg_replace('/[^A-Za-z0-9]/', '-', $filename);
  $filename = preg_replace('/[-]+/', '-', $filename);
  $filename = trim($filename, '-');
  if ($filetype != '') {
    $filename = "$filename.$filetype";
  }
  $filename = strtolower($filename);
  return $filename;
}

function jwrr_clean_title_lower($string)
{
  $string = preg_replace('/[^A-Za-z0-9-]/', '-', $string);
  $string = preg_replace('/[-]+/', '-', $string);
  $string = trim($string, '-');
  $string = strtolower($string);
  return $string;
}


function jwrr_clean_lower($string)
{
  return strtolower(preg_replace('/[^\w-]/u', '', $string));
}


function jwrr_get_userdata($username='')
{
  if ($username == ''){
     $userdata = _wp_get_current_user();
  } else {
     $userdata = get_user_by('login', $username);
  }
  return $userdata;
}

function jwrr_user_exists($username)
{
  $user = jwrr_get_userdata($username);
  return $user->exists();
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


function jwrr_get_usermeta($username='')
{
  if ($username == ''){
    $id = _wp_get_current_user();
  } else {
     $userdata = get_user_by('login', $username);
     $id = $userdata->ID;
  }
  $usermeta = get_user_meta($id);
  return $userdata;
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


function jwrr_get_fullname($username='', $sep=' ')
{
  $firstname = jwrr_get_firstname($username);
  $lastname =  jwrr_get_lastname($username);
  $fullname = trim("$firstname$sep$lastname");
  if ($sep == '-') {
    $fullname = strtolower($fullname);
  }
  return $fullname;
} 
 
 
function jwrr_get_email($username='')
{
  if ($username == ''){
     $user = _wp_get_current_user();
  } else {
     $user = get_user_by('login', $username);
     if ($user == false) return "";
  }
  return $user->user_email;
}


function jwrr_mkdir($path, $permissions = 0777)
{
  return is_dir($path) || mkdir($path, $permissions, true);
}


function get_post_search_string($search_string='', $search_key = 'artsearch')
{
  if ($search_string=='' && !empty($_POST['artsearch'])) {
    $search_string = htmlspecialchars($_POST['artsearch']);
    $search_string = str_replace('/', '\/', $search_string);
  }
  return $search_string;
}


function jwrr_display_images($images, $copyright='', $msg='')
{
  $html = "$msg
  <div class='css-gallery'>";

  $artwork_full_path = jwrr_hidden_art_path($artwork_path);
  foreach($images as $image)
  {

    $i = str_replace($artwork_full_path, '', $image);
    $ii = '/catartists-images' . $i;
    $b = str_replace(".jpg", "", $i);
    $b = str_replace("/art-hidden/", "/", $b);
    $b = str_replace("/small/", "/", $b);
    $html .= "    <a href=\"/show$b\"><img src=\"$ii\" class=\"small\" loading=\"lazy\"></a>\n";
  }
  $html .= "   <div style='clear:both'></div>\n";
  $html .= $copyright;
  $html .= "  </div>\n";
  return $html;
}


function jwrr_hidden_art_path($path='')
{
  if ($path == '') {
    $doc_root = $_SERVER["DOCUMENT_ROOT"];
    $path = "$doc_root/art-hidden";
  }
  return $path;
}


function search_and_show_images($artwork_glob='', $search_string="")
{
  $artwork_glob = jwrr_hidden_art_path($artwork_glob) . '/*/small/*.jpg';
  $search_string = get_post_search_string();
  
  $images = glob($artwork_glob);
  $search_words = explode(" ", $search_string);
  foreach ($search_words as $search_word) {
    $images = preg_grep("/^.*$search_word.*/i", $images);    
  }
  
  shuffle($images);
  $images = array_slice($images, 0, 100);
  $html = jwrr_display_images($images);
  echo $html;
}


function jwrr_get_art_by_artist($artist_name, $copyright, $msg='', $min_count=0)
{
  if ($copyright == '') {
    $copyright = 'Please note that all copyright and reproduction rights remain with the artist.';
  }
  $artwork_glob = jwrr_hidden_art_path($artwork_glob) . "/$artist_name/small/*.jpg";
  $images = glob($artwork_glob);
  if (count($images) <= $min_count) return '';
  usort( $images, function( $a, $b ) { return filemtime($b) - filemtime($a); } );
  $html = jwrr_display_images($images, $copyright, $msg);  
  return $html;
}


function jwrr_parse_img_path($img='')
{
  if ($img == '' && !empty($_GET["img"])) {
    $img = $_GET["img"];
  }
  if ($img == '') return [];
  
  $img = htmlspecialchars($img);
  $img = rtrim($img,"/");
  $chunks = explode('/', $img);
  $artist_username = empty($chunks[1]) ? '' : jwrr_clean_lower($chunks[1]);
  $art_title = empty($chunks[2]) ? '' : jwrr_clean($chunks[2]);
  $art_delete = (!empty($chunks[3]) && $chunks[3] == "delete") ? 'delete' : '';
  $artist_fullname = ucwords(str_replace('-', ' ', $artist_username));

  if ($artist_fullname == '') $artist_fullname = $artist_username;
  $a = ['username' => $artist_username, 'title' => $art_title , 'fullname' => $artist_fullname, 'delete' => $art_delete];
  return $a;
}


function jwrr_get_newest_artwork($artist_name)
{
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $hidden_art_path = jwrr_hidden_art_path();
  $path = "$hidden_art_path/$artist_name/small/*.jpg";
  $images = glob($path);
  if (count($images) == 0) return '';
  usort( $images, function( $a, $b ) { return filemtime($b) - filemtime($a); } );
  $newest_artwork = basename($images[0]);
  return $newest_artwork;
}


function jwrr_count_images()
{
  $artist_fullname_with_dash = jwrr_get_fullname('', '-');
  $hidden_art_path = jwrr_hidden_art_path();
  $big_image_folder = "$hidden_art_path/$artist_fullname_with_dash/big/";
  $num_images = count(glob("$big_image_folder/*jpg"));
  return $num_images;
}


function jwrr_copyright($year, $fullname, $type="")
{
  $html = <<<HEREDOC
  <div class="css-copyright">&copy 2022 $fullname. All copyright and reproduction rights remain with the artist.</div>
HEREDOC;
return $html;
}



