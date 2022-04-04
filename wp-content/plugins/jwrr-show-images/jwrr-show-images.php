<?php
/*
 Plugin Name: JWRR Show Images
 Plugin URI: https://github.com/wordpress-stuff/wp-content/plugins/jwrr-show-images
 Description: a plugin to show gallery of images in a page
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: MIT
*/

add_shortcode('jwrr_show_images', 'jwrr_show_images');



  function jwrr_copyright($year, $artist, $type="")
  {
    $html = <<<HEREDOC
    <div class="jwrr_copyright">&copy 2022 $artist. All copyright and reproduction rights remain with the artist.</div>
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
  
  function jwrr_get_art_by_artist($artist, $copyright)
  {
    $doc_root = $_SERVER["DOCUMENT_ROOT"];
    $path = "$doc_root/art/$artist/small/*.jpg";
    $images = glob($path);

    $html = "  <div class='gallery'>";
    
    foreach($images as $image)
    {
      $b = "/art/$artist/small/" . basename($image);
      $html .= "    <a href='/show?img=$b'><img src='$b' class='small'></a>\n";
    }
    $html .= "   <div style='clear:both'></div>\n";
    $html .= $copyright;
    $html .= "  </div>\n";
    return $html;
  }


function jwrr_show_images()
{
  $enable_style = true;
  $img = htmlspecialchars($_GET["img"]);
  $img = str_replace("small", "big", $img);
  $chunks = explode('/', $img);
  $artist1 = $chunks[2];

  $artist = preg_replace("/[^\w\d]/", ' ', $artist1);

  $buy_platform = "Zazzle";
  $buy_platform_icon = "https://catartists.org/wp-content/plugins/jwrr-social/images/zazzle.png";
  $buy_url = "https://www.zazzle.com/store/rachel_armington_art/products?cg=196759976565079751";

  $copyright = jwrr_copyright("2022", $artist);
  $buybar = jwrr_buybar($buy_platform, $buy_platform_icon, $buy_url);

  $more_art_by_artist = jwrr_get_art_by_artist($artist1, $copyright);

  $html = "

<!-- jwrr_show_images -->";

  if ($enable_style) {

    $html .= <<<HEREDOC_STYLE
  
  <style>
    body {background-color: white;}
    div.jwrr_show_images_main {max-width:1024px; margin: 0 auto;}
    div.jwrr_copyright {text-align: center; font-size: 1.5em; margin: 0.5em;}
    div.jwrr_buyitem {display: block; margin-left: auto; margin-right: auto; text-align:center;}
    div.jwrr_buyitem img {display: block; margin-left: auto; margin-right: auto;}
    div.jwrr_buyitem div {display: block; margin-left: auto; margin-right: auto;}
    div.gallery {text-align: center;}
    div.gallery img {display:inline;padding:0 0 0 0;margin: 0.5em 1em 0.5em 0.2em;border-radius:15px; height:200px; transition: transform 1s;}
    div.gallery img:hover {transform: scale(2.0);}
    h1 {text-align: center;}
  </style>
HEREDOC_STYLE;
  }
  
  $html .= <<<HEREDOC_DIV
  <div class="jwrr_show_images_main">
    $button_bar
    <h1>Artwork by $artist</h1>
    $buybar
    $copyright
    <img src='$img'>
    <hr>
    
    <h2>Here is more of my art</h2>
    
    $more_art_by_artist
    
  </div>

<!-- end jwrr_show_images -->

HEREDOC_DIV;

  return $html;
}

