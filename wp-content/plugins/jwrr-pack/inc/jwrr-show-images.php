<?php
/*
 Name: JWRR Show Images
 URI: https://github.com/wordpress-stuff/wp-content/plugins/jwrr-show-images
 Description: a plugin to show gallery of images in a page
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: MIT
*/

add_shortcode('jwrr_show_images', 'jwrr_show_images');


function jwrr_show_images($img='')
{
  $enable_style = false;
  $a = jwrr_parse_img_path($img);
  $artist_fullname_with_dash = $a['username'];
  $art_title = $a['title'];
  $artist_fullname_with_space = $a['fullname'];
  $logged_in_artist_fullname_with_dash = jwrr_get_fullname('', '-');
  $request_uri = ($_SERVER['REQUEST_URI']);
  $is_owner = ($logged_in_artist_fullname_with_dash == $artist_fullname_with_dash);
  $art_delete = $is_owner ? $a['delete'] : '';
  
  $big_image_url = "/art/$artist_fullname_with_dash/big/$art_title.jpg";
  $small_image_url = "/art/$artist_fullname_with_dash/small/$art_title.jpg";

  $big_image_path = $_SERVER['DOCUMENT_ROOT'] . $big_image_url;
  $small_image_path = $_SERVER['DOCUMENT_ROOT'] . $small_image_url;
  $big_image_exists = file_exists($big_image_path);
  $img_html = '';
  $some_more = "some";
  $html = '';
  if ($big_image_exists) {
    if ($art_delete == 'delete') {
      unlink($big_image_path);
      unlink($small_image_path);
      $html .= "<h2>Page '$art_title' deleted</h2>";
    } else if ($is_owner) {
      touch($big_image_path);
      touch($small_image_path);
    }
  } else {
    $artists_latest_artwork = jwrr_get_newest_artwork($artist_fullname_with_dash);
    $big_image_url = "/art/$artist_fullname_with_dash/big/$artists_latest_artwork";
    $big_image_path = $_SERVER['DOCUMENT_ROOT'] . $big_image_url;
    $big_image_exists = file_exists($big_image_path) && (str_ends_with($big_image_path, '.jpg'));
  }
  
  if ($big_image_exists) {
    $big_image_url = str_replace('/art/', '', $big_image_url);
    $big_image_url = str_replace('/big/', '/', $big_image_url);
    $big_image_url = str_replace('.jpg', '', $big_image_url);
    $img_html = '<img class="css-main-image" src="/?catart=' . $big_image_url . '">';
    $some_more = "more";
  }  
  
  $buy_platform = "Zazzle";
  // ln -s wp-content/themes/catartists1 catartists1
  $buy_platform_icon = "/catartists1/images/zazzle.png";
  $buy_url = "https://www.zazzle.com/store/rachel_armington_art/products?cg=196759976565079751";

  $copyright = jwrr_copyright("2022", $artist_fullname_with_space);
  $buybar = jwrr_buybar($buy_platform, $buy_platform_icon, $buy_url);

  $big_image_html = ($art_delete=='delete') ? '' : "$buybar $img_html $copyright";

  $more_art_by_artist_html = jwrr_get_art_by_artist($artist_fullname_with_dash, $copyright, "<h2>Here is $some_more of my art</h2>", 0);

  $html .= "

<!-- show_images -->";

  if ($enable_style) {

    $html .= <<<HEREDOC_STYLE

  <style>
    body {background-color: white;}
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
  </style>
HEREDOC_STYLE;
  }

  $html .= <<<HEREDOC_DIV
  <div class="css-show-images-main">
    $big_image_html
    <hr>

    $more_art_by_artist_html

  </div>

<!-- end show_images -->

HEREDOC_DIV;

  return $html;
}

