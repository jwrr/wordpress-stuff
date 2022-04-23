<?php

function css_style()
{
  
$css = "
<style>
  body {background-color: white;}
  #css-outer {position:relative;width:100%;max-width:1200px;margin: 0 auto 0 auto;padding:0 0 0 0;font-family:Estrangelo Edessa;color:#333333;} /* 660099;} */
  #css-main {position:static;}
  #css-content {width:100%;float:none;}
  #css-banner {margin: 0 0 1em 0;}
  #css-banner img {max-width:98%;}

  input.css-search-form-file {color:white;background-color:green; padding:0.5em; font-size:1.5em; border-radius:10px; width:75%;}
  input.css-search-form-submit  {color:white;background-color:green; padding:0.6em; font-size:1.5em; border-radius:10px;}
  div.css-search-form {margin-left:auto; margin-right: auto;text-align:center;}
  div.css-search-form h2 {margin:0; padding:0;}
  div.css-search-form-please-log-in {font-size: 2.0em; font-weight:bold;margin:1em;}
  
  div.css-show-images-main {max-width:1024px; margin: 0 auto;}
  div.css-copyright {text-align: center; font-size: 1.2em; margin: 0.5em;}
  div.css-pod-item {display: block; margin-left: auto; margin-right: auto; text-align:center;}
  div.css-pod-item img {display: block; margin-left: auto; margin-right: auto;}
  div.css-pod-item div {display: block; margin-left: auto; margin-right: auto;}
  img.css-main-image {display:block; margin-left:auto; margin-right:auto; max-width:98%;}
  div.css-gallery {text-align: center;}
  div.css-gallery img {display:inline;padding:0 0 0 0;margin: 0.5em 1em 0.5em 0.2em;border-radius:15px; height:200px; transition: transform 1s; max-width:100%;}
  div.css-gallery img:hover {transform: scale(1.5);}
  h1 {text-align: center;}

  .css-button-bar {position:static; width:100%; min-height: 3.3em; background-color:black; color:white; max-width:100%; text-align: center;}
  .css-button-bar span {margin:0; padding:0; font-size:1.6em; }
  .css-button {font-size:1.3em;border-radius:10px;padding:0.5em 0.2em 0.5em 0.2em;margin: 3px 0.15em 0 3px;background-color:green;color:white;text-decoration: none; width: 3.4em; overflow: hidden; white-space: nowrap; }
  a.css-button:link {color: white; text-decoration: none;}
  a.css-button:visited {color: white; text-decoration: none;}
  .css-right {float: right;}
  .css-left  {float: left;}
  .css-button-bar span {white-space: nowrap;}

  input.css-upload-form_file {color:white;background-color:green; padding:0.5em; font-size:1.5em; border-radius:10px; width:75%;}
  div.css-upload-form h2 {margin:0; padding:0;}
  div.css-upload-form-please-log-in {font-size: 2.0em; font-weight:bold;margin:1em;}

  div.css-user-name-wrap {margin:1em;}
  div.css-user-pass-wrap {margin:1em;}
  div.css-oneliner {padding: 1em 0 0 1em;}
  div.css-oneliner label {display:block; margin-left:0.5em;}
  div.css-oneliner input[type=submit] {display:block; margin-left:0em; background-color:green; color:white;}
  div.css-oneliner input {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:68%;}
  div.css-oneliner textarea {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:68%;}
  div.css-oneliner select {display:block; margin-left:0em; font-size:1.1em;border-color:gray;border-radius:10px;padding:0.3em;}
  div.css-checkboxes {display:block; margin-left:1.5em; font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;}
  div.css-checkbox {display:inline; padding-right:3em;}
  div.css-checkbox input {width:2em; height: 2em;}
  div.css-error {color:red; font-size: 2em;margin-left:0.5em;}
  div.css-success {color:green; font-size: 2em;margin-left:0.5em;}

</style>
";
return $css;
}

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
<?php } ?>
<?php echo get_page_title();
//<link rel="stylesheet" href="<?php bloginfo('template_directory'); /style.css"
?>
<link rel="stylesheet" href="/catartists1/style.css" />
<?php echo css_style(); ?>
<?php // wp_head(); ?>
</head>
<body>
