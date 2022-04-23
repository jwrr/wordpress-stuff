<?php
function get_page_title() {
  $slug = get_slug();
  $title = '';
  if (is_home()) {
    $title = "<title>" . get_bloginfo( 'name' ) . "</title>\n";
  } else if (is_tag()) {
    $title = "<title>" . ucfirst($slug) . " by Rachel Armington</title>\n";
  } else if (is_category()) {
    $title = category_description( get_category_by_slug($slug)->term_id );
    $title = preg_replace("/<.*?p>/","",$title);
    $title = preg_replace("/\n/","",$title);
    $title = "<title>" . $title  . "</title>\n";
  } else {
    $title = '';
    if (!empty($post->ID)) {
      $title = "<title>" . get_the_title($post->ID) . "</title>\n";
    }
  }
  return $title;
}


function get_meta_description()
{
  $slug = get_slug();
  $the_excerpt = '';
  if (is_home()) {
    $the_excerpt = "<meta name=\"description\" content=\"" . get_bloginfo( 'description' ) . "\">\n";
  } else if (is_tag()) {
    $the_excerpt = "<meta name=\"description\" content=\"" .  "Here are my posts for the tag #" . $slug . "\">\n";
  } else if (is_category()) {
    $the_excerpt = category_description( get_category_by_slug($slug)->term_id );
    $the_excerpt = preg_replace("/<.*?p>/","",$the_excerpt);
    $the_excerpt = preg_replace("/\n/","",$the_excerpt);
    $the_excerpt = "<meta name=\"description\" content=\"Here are my posts in the category '" . $the_excerpt  . "'\">\n";
  } else {
    $the_post = empty($post->ID) ? null : get_post($post->ID); 
    $the_post_excerpt = empty($the_post->post_excerpt) ? '' : $the_post->post_excerpt;
    $the_excerpt = "<meta name=\"description\" content=\"" . $the_post_excerpt . "\">\n";
  }

   return $the_excerpt; 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width">
<?php if (is_home()) { ?>
<meta name="google-site-verification" content="V9Biutd_23Geeq4Vc82o0XlBTf5O7dtEg2ZhqVb28LM" />
<?php } ?>
<?php echo get_page_title();
//<link rel="stylesheet" href="<?php bloginfo('template_directory'); /style.css"
?>
<link rel="stylesheet" href="/catartists1/style.css" />
<?php // wp_head(); ?>
</head>
<body>
