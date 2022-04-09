<?php
add_theme_support( 'post-thumbnails' );
// add_image_size( 'excerpt-thumbnail', 198, 142, true );

function my_excerpt()
{
   return "<p class='theme_post_excerpt'>" . get_the_excerpt() . "</p>";
}

add_shortcode('excerpt', 'my_excerpt');

function copy_images()
{
    $cwd = getcwd();
    $dest = "images";
    $images = glob("wp-content/uploads/*-*x*.jpg");
    foreach($images as $image) {
       if (!file_exists($image)) {
       }
    }
    //echo "in copy_images pwd='$cwd'\n";
}
copy_images();

function my_cdn_upload_url() {
    return '/images';
    // return 'http://mk124.yourcdn.com/yoursite/wp-content/uploads';
}
// add_filter( 'pre_option_upload_url_path', 'my_cdn_upload_url' );

function get_uri()
{
   return $_SERVER['REQUEST_URI'];
}

if (!function_exists( __NAMESPACE__ . 'get_slug')) {
function get_slugx()
{
 $p = get_uri();
 $larr = explode("/",$p);
 $slug = array_pop($larr);
 if ($slug == '' && count($larr) > 0) {
  $slug = array_pop($larr);
 }
 return $slug;
}
}


function get_h1()
{
 $indent = str_repeat(' ', 2);
 $slug = get_slug();
 $theme_h1 = "";
 if (is_home()) {
    $theme_h1 = get_bloginfo( 'name' );
 } else if (is_tag()) {
    $h1 = ucwords(preg_replace("/-/", ' ', $slug));
    $theme_h1 = "#" . $h1;
 } else if (is_category()) {
    $theme_h1 = category_description( get_category_by_slug($slug)->term_id );
    $theme_h1 = preg_replace("/<.*?p>/","",$theme_h1);
    $theme_h1 = preg_replace("/\n/","",$theme_h1);
 }
 if ($theme_h1 == "") {
    $html = $indent . "<p>&nbsp;</p>\n";
 } else {
    $html = $indent . '<h1 id="theme_h1">' . $theme_h1 . "</h1>\n";
 }
 echo $html;
}


function get_copyright()
{
 $indent = str_repeat(' ', 2);
 $msg = $GLOBALS['theme_copyright_msg'];
 echo $indent . "<div id=\"theme_copyright\">$msg</div>\n";
}


function get_the_posts()
{
 $indent = str_repeat(' ', 2);
 echo $indent . "<div id=\"theme_content\">\n";
 if ( have_posts() ) : while ( have_posts() ) : the_post();
  get_template_part( 'content', get_post_format() );
 endwhile; endif; 
 get_copyright();
 echo $indent . "</div>\n";
}


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
    $b = basename($image);
    echo "<a href='/show?img=/$image'><img src=\"/$image\" class=\"small\" loading=\"lazy\"></a>\n";
  }
  echo '   <div style="clear:both"></div>';
  echo '   Please note that all copyright and reproduction rights remain with the artist.';
  echo '  </div>';
}


