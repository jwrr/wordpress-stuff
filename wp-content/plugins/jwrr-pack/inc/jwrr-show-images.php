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



  function jwrr_copyright($year, $fullname, $type="")
  {
    $html = <<<HEREDOC
    <div class="jwrr_copyright">&copy 2022 $fullname. All copyright and reproduction rights remain with the artist.</div>
HEREDOC;
  return $html;
  }

  function jwrr_buybar($buy_platform, $buy_platform_icon, $buy_url)
  {
    $html = <<<HEREDOC
    <div class="jwrr_buybar">
      <hr>
      <div class="jwrr_buyitem">
        <a href="$buy_url"><img src="$buy_platform_icon"></a>
        <div><a href="$buy_url">Available on $buy_platform</a></div>
      </div>
      <hr>
    </div>
HEREDOC;
  return $html;
  }


  function jwrr_get_art_by_artist($artist_name, $copyright, $msg='', $min_count=0)
  {
    $doc_root = $_SERVER["DOCUMENT_ROOT"];
    $path = "$doc_root/art/$artist_name/small/*.jpg";
    $images = glob($path);
    
    if (count($images) <= $min_count) return '';
    

    $html = "$msg
    <div class='gallery'>";

    foreach($images as $image)
    {
      $i = "/art/$artist_name/small/" . basename($image);
      $b = str_replace("/art", "", $i);
      $b = str_replace("/small", "", $b);
      $b = str_replace(".jpg", "", $b);
      $html .= "    <a href='/show$b'><img src='$i' class='small'></a>\n";
    }
    $html .= "   <div style='clear:both'></div>\n";
    $html .= $copyright;
    $html .= "  </div>\n";
    return $html;
  }

function jwrr_show_images($img='')
{
  $enable_style = true;
  $a = jwrr_parse_img_path($img);
  $artist_username = $a['username'];
  $art_title = $a['title'];
  $artist_fullname = $a['fullname'];

  $big_image_url = "/art/$artist_username/big/$art_title.jpg";
  $big_image_path = $_SERVER['DOCUMENT_ROOT'] . $big_image_url;
  $big_image_exists = file_exists($big_image_path);
  $img_html = '';
  $some_more = "some";
  if ($big_image_exists) {
//     /art/rachel/big/angel-cat-urn-kitten-wings.jpg
    $big_image_url = str_replace('/art/', '', $big_image_url);
    $big_image_url = str_replace('/big/', '/', $big_image_url);
    $big_image_url = str_replace('.jpg', '', $big_image_url);
    $img_html = '<img class="jwrr_main_image" src="/?catart=' . $big_image_url . '">';
    $some_more = "more";
  }  
  
  $buy_platform = "Zazzle";
  $buy_platform_icon = "/wp-content/plugins/jwrr-social/images/zazzle.png";
  $buy_url = "https://www.zazzle.com/store/rachel_armington_art/products?cg=196759976565079751";

  $copyright = jwrr_copyright("2022", $artist_fullname);
  $buybar = jwrr_buybar($buy_platform, $buy_platform_icon, $buy_url);

  $more_art_by_artist = jwrr_get_art_by_artist($artist_username, $copyright, "<h2>Here is $some_more of my art</h2>", 0);

  $html = "

<!-- jwrr_show_images -->";

  if ($enable_style) {

    $html .= <<<HEREDOC_STYLE

  <style>
    body {background-color: white;}
    div.jwrr_show_images_main {max-width:1024px; margin: 0 auto;}
    div.jwrr_copyright {text-align: center; font-size: 1.2em; margin: 0.5em;}
    div.jwrr_buyitem {display: block; margin-left: auto; margin-right: auto; text-align:center;}
    div.jwrr_buyitem img {display: block; margin-left: auto; margin-right: auto;}
    div.jwrr_buyitem div {display: block; margin-left: auto; margin-right: auto;}
    img.jwrr_main_image {display:block; margin-left:auto; margin-right:auto; max-width:98%;}
    div.gallery {text-align: center;}
    div.gallery img {display:inline;padding:0 0 0 0;margin: 0.5em 1em 0.5em 0.2em;border-radius:15px; height:200px; transition: transform 1s; max-width:100%;}
    div.gallery img:hover {transform: scale(1.5);}
    h1 {text-align: center;}
  </style>
HEREDOC_STYLE;
  }

  $html .= <<<HEREDOC_DIV
  <div class="jwrr_show_images_main">
    $buybar
    $img_html
    $copyright
    <hr>

    $more_art_by_artist

  </div>

<!-- end jwrr_show_images -->

HEREDOC_DIV;

  return $html;
}

