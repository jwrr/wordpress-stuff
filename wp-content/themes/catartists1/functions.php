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


