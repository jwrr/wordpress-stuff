<?php
/*
 Name: JWRR Random Banner
 URI: http://jwrr.com/wp/plugins/jwrr-random-banner
 Description: a plugin to display images selected randomly from uploaded banners.
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: GPL3
*/

add_shortcode('jwrr_random_banner', 'jwrr_random_banner');

function jwrr_random_banner()
{
	$banner_suffix = "-banner.jpg";
	$enable_echo = False;
	$slug = get_slug();
	if ($slug == '') $slug = 'home';
	$uploads = wp_upload_dir();
	$upload_path = $uploads['basedir'];
	$banner_path = "$upload_path/$slug$banner_suffix";
	if (!file_exists($banner_path)) {
		$slug = 'home';
		$banner_path = "$upload_path/$slug$banner_suffix";
	}	
	$html = "\n<!-- plugin: jwrr_random_banner -->\n";
	if (file_exists($banner_path)) {
		$banner_glob_array = glob("$upload_path/$slug$banner_suffix");
		$cnt = count($banner_glob_array);
		$upload_url = $uploads['baseurl'];
		$i = rand(0,$cnt-1);
		$banner_url = $upload_url . '/' . basename($banner_glob_array[$i]);
		$html .= "<div id=\"jwrr_banner\"><img src=\"$banner_url\" alt=\"banner\"></div>\n\n";
	}
	if ($enable_echo) echo $html;
	return $html;
}

if (!function_exists( __NAMESPACE__ . 'get_slug')) {
function get_slug()
{
	$p = $_SERVER['REQUEST_URI'];
	$larr = explode("/",$p);
	$slug = array_pop($larr);
	if ($slug == '' && count($larr) > 0) {
		$slug = array_pop($larr);
	}
	return $slug;
}
}


?>
<?php
/*
 Name: JWRR Search Form
 URI: https://github.com/wordpress-stuff/wp-content/plugins/jwrr-search-form
 Description: a plugin to add an search form to a page
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: MIT
*/

add_shortcode('jwrr_search_form', 'jwrr_search_form');

function jwrr_search_form($atts = array(), $content = null, $tag = '')
{
  $search_handler = "";
  $enable_style = true;

  $html = "

<!-- jwrr-search-form -->";
  if ($enable_style) {
    $html .= <<<HEREDOC1

  <style>
  input.jwrr_search_form_file {color:white;background-color:green; padding:0.5em; font-size:1.5em; border-radius:10px; width:75%;}
  input.jwrr_search_form_submit  {color:white;background-color:green; padding:0.6em; font-size:1.5em; border-radius:10px;}
  div.jwrr_search_form {margin-left:auto; margin-right: auto;text-align:center;}
  div.jwrr_search_form h2 {margin:0; padding:0;}
  div.jwrr-search-form-please-log-in {font-size: 2.0em; font-weight:bold;margin:1em;}
  </style>

HEREDOC1;
  }

  $search_string = get_post_search_string();
  $value = ($search_string == '') ? '' : "value=\"$search_string\"'";

  $html .= <<<HEREDOC2
  <div class="jwrr_search_form">
  <form action="$search_handler" method="post">
    <input class="jwrr_search_form_file" type="search" placeholder="Search" $value name="artsearch" id="artsearch">
    <input class="jwrr_search_form_submit" type="submit" value="Art Search" name="submit">
  </form>
  </div>

HEREDOC2;

  $html .= "
<!-- end jwrr-search-form -->

";

  return $html;
}

?>
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



  function jwrr_copyright($year, $artist, $type="")
  {
    $fullname = jwrr_get_fullname($artist);
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


  function jwrr_get_art_by_artist($artist, $copyright)
  {
    $doc_root = $_SERVER["DOCUMENT_ROOT"];
    $path = "$doc_root/art/$artist/small/*.jpg";
    $images = glob($path);

    $html = "  <div class='gallery'>";

    foreach($images as $image)
    {
      $i = "/art/$artist/small/" . basename($image);
      $b = str_replace("/small", "", $i);
      $b = str_replace(".jpg", "", $b);
      $html .= "    <a href='/show$b'><img src='$i' class='small'></a>\n";
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
  $img = rtrim($img,"/");
  $chunks = explode('/', $img);
  $artist_username = $chunks[2];
  
  $last_chunk = count($chunks) - 1;
  $chunks[$last_chunk] = "big/" . $chunks[$last_chunk] . '.jpg';
  $big_image_path = implode('/', $chunks);

  $buy_platform = "Zazzle";
  $buy_platform_icon = "https://catartists.org/wp-content/plugins/jwrr-social/images/zazzle.png";
  $buy_url = "https://www.zazzle.com/store/rachel_armington_art/products?cg=196759976565079751";

  $copyright = jwrr_copyright("2022", $artist_fullname);
  $buybar = jwrr_buybar($buy_platform, $buy_platform_icon, $buy_url);

  $more_art_by_artist = jwrr_get_art_by_artist($artist_username, $copyright);

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
    <h1>Artwork by $artist_fullname</h1>
    $buybar
    $copyright
    <img src="$big_image_path">
    <hr>

    <h2>Here is more of my art</h2>

    $more_art_by_artist

  </div>

<!-- end jwrr_show_images -->

HEREDOC_DIV;

  return $html;
}
?>
<?php
/*
 Name: JWRR Social
 URI: http://jwrr.com/wp/plugins/jwrr_youtube
 Description: a plugin to create follow-me buttons for social media sites.
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: GPL3
*/

add_shortcode('jwrr-social', 'jwrr_social');
function jwrr_social()
{
	$theme_social_links = array(
		'Email'    =>  'mailto:rachelarmington@gmail.com',
		'Twitter'  => 'https://twitter.com/rachelarmington',
		'Instagram' =>'https://www.instagram.com/rachelarmington6052/?hl=en',
		'YouTube'  => 'https://www.youtube.com/channel/UCQ7Eosyn2nYbNbkokKrmPcw/videos', // https://www.youtube.com/channel/UCQ7Eosyn2nYbNbkokKrmPcw',
		'Amazon'   => 'https://www.amazon.com/Rachel-Armington/e/B015GRMR8O/ref=dp_byline_cont_book_1',
		'Facebook' => 'https://www.facebook.com/pages/Rachel-M-Brown-Paintings/1396112717307475',
		'Pinterest'=> 'https://www.pinterest.com/CatPaintings/',			
		'Zazzle'   => 'https://www.zazzle.com/rachel_armington_art',
	);

	$theme_social_titles = array(
		'Email' => "Send me an email",
		'Twitter'  => "My Tweets",
		'Instagram'=> "Comment on my photos on Instagram",
		'YouTube'  => 'See my tutorials on YouTube',
		'Amazon'   => "My Books on Amazon",
		'Facebook' => "Follow me on Facebook",
		'Pinterest'=> 'Pin me',			
		'Zazzle'   => 'My gift ideas at Zazzle',
	);

	$alt_text = 'Follow me on';
	$css_id = 'jwrr_social_left';
	$enable_echo = False;

	// DO NOT MODIFY BELOW
	if (count($theme_social_links)==0) return;
	$indent = str_repeat(' ', 1);

	$plugin_url = plugin_dir_url( __FILE__ );
	$plugin_url = preg_replace("/\/$/", '', $plugin_url);
	$plugin_images_url = "$plugin_url/images";

        $upload_dir = wp_upload_dir();
	$upload_path = $upload_dir['basedir'];
	$upload_url = $upload_dir['baseurl'];

	$images_url = $plugin_images_url;
	$html = "\n$indent<!-- plugin: jwrr_social -->\n" .
                "$indent<div id='$css_id'>\n" . 
                "$indent<g:plusone></g:plusone>\n" .
                "$indent <ul>\n";

        foreach ($theme_social_links as $key => $value) {
		$title_exists = array_key_exists($key, $theme_social_titles);
		$alt = $title_exists ? $theme_social_titles[$key] : "$alt_text $key";
		$image_name = str_replace(' ','-',strtolower($key)) . '.png';
		$uploaded_image = "$upload_path/$image_name";
		// $upload_image_path = find_file($upload_path, $image_name);
		$uploaded_image_exists = file_exists($uploaded_image);
		$images_url = $uploaded_image_exists ? $upload_url : $plugin_images_url;
		$img_url = "$images_url/$image_name";
		$html .= "$indent  <li><a href='$value' title='$alt'><img src='$img_url' alt='$alt'></a></li>\n";
	}
	$html .= "$indent </ul>\n$indent</div>\n\n";
	if ($enable_echo) echo $html;
	return $html;
}

function find_file($dir, $filename)
{
	$max = 5;
	$search = "$dir/*";
	$done = False;
	$i = 0;
	while (!$done) {
		$files = glob($search);
		$all_files = array_merge($all_files, $files);
		$search = "$search/*";
		$done = (count($files) == 0) || ($i >= $max);
		$i++;
	}
	$matches  = preg_grep ("/$filename$/", $haystack);
	$filepath = count($matches) ? $matches[0] : ''; 
	return $filepath;
}
?>
<?php
/*
 Name: JWRR Upload
 URI: https://github.com/wordpress-stuff/wp-content/plugins/jwrr-upload-form
 Description: a plugin to add an upload form to a page
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: MIT
*/

add_shortcode('jwrr_upload_form', 'jwrr_upload_form');

function jwrr_upload_form($atts = array(), $content = null, $tag = '')
{

//  $upload_handler = "/art/index.php";
  $upload_handler = "/upload-handler";
  $enable_style = true;
  $please_log_in_msg = "Please Log In";
  $select_file_msg = "Select file to upload";

  $html = "

<!-- jwrr-upload-form -->";
  if ($enable_style) {
    $html .= <<<HEREDOC1

  <style>
  input.jwrr_upload_form_file {color:white;background-color:green; padding:0.5em; font-size:1.5em; border-radius:10px; width:75%;}
  input.jwrr_upload_form_submit  {color:white;background-color:green; padding:0.6em; font-size:1.5em; border-radius:10px;}
  div.jwrr_upload_form {margin-left:auto; margin-right: auto;text-align:center;}
  div.jwrr_upload_form h2 {margin:0; padding:0;}
  div.jwrr-upload-form-please-log-in {font-size: 2.0em; font-weight:bold;margin:1em;}
  </style>

HEREDOC1;
  }

  $user = _wp_get_current_user();
  $is_logged_in = $user->exists();
  if (!$is_logged_in) {
    $html .= "<div class=\"jwrr-upload-form-please-log-in\">$please_log_in_msg</div>";
  } else {
    $html .= <<<HEREDOC2
  <div class="jwrr_upload_form">
  <h2>$select_file_msg</h2>
  <form action="$upload_handler" method="post" enctype="multipart/form-data">
    <input class="jwrr_upload_form_file" type="file" name="upload_filename" id="upload_filename">
    <div style="text-align:left;margin-left:12%;font-size:1.5em;">
      <label for="title">Title</label><br>
      <input class="jwrr_upload_form_title" type="text" name="title" id="title" cols="60" style="font-size:1.3em;"><br>
      
      <label for="copyright">Choose a Copyright Notice:</label><br>
      <select class="jwrr_upload_form_select" id="copyright" name="copyright" style="font-size:1em;">
        <option value="all" selected>All rights reserved</option>
        <option value="none">None</option>
        <option value="BY-NC-ND">BY-NC-ND Needs Attribution, only for non-commercial and no derivatives</option>
        <option value="BY-ND">BY-ND Needs Attribution and no derivatives</option>
        <option value="BY-NC-SA">BY-NC-SA Needs Attribution, only for non-commercial and ShareAlike</option>
        <option value="BY-NC">BY-NC Needs Attribution and only for non-commercial</option>
        <option value="BY-SA">BY-SA Needs Attribution and ShareAlike</option>
        <option value="BY">BY Needs Attribution</option>
        <option value="CC0">CC0 Free content with no restrictions</option>
      </select><br>
  
      <input class="jwrr_upload_form_checkbox" type="checkbox" name="watermark" id="watermark" value="yes" checked="true"><label>Add Watermark</label><br>
      <input class="jwrr_upload_form_checlbox" type="checkbox" name="shred" id="shred" value="yes" checked="true"><label>Shred it like your cat would</label><br>
  
      <label for="description">Description</label><br>
      <textarea name="description" id="description" cols="80" rows="10"></textarea><br>
  
      <input class="jwrr_upload_form_submit" type="submit" value="Upload Image" name="submit">
      </div>
  </form>
  </div>

HEREDOC2;
  }

  $html .= "
<!-- end jwrr-upload-form -->

";

  return $html;
}


// ========================================================================
// ========================================================================


add_shortcode('jwrr_upload_handler', 'jwrr_upload_handler');

function jwrr_upload_handler()
{
  if (!jwrr_is_logged_in()) return "";
  if (!isset($_POST["submit"])) return "";

  print_r($_POST);

  $username = jwrr_get_username();
  
  $orig_dir = "$username/orig/";
  $success = jwrr_mkdir($orig_dir);
  
  $meta_dir = "$username/meta/";
  $success = jwrr_mkdir($meta_dir);
  
  $upload_filename = htmlspecialchars($_FILES["upload_filename"]["name"]);
  $upload_filename = str_replace(' ', '-', $upload_filename);
  $orig_filename = $orig_dir . basename($upload_filename);
  $upload_good = 1;
  $upload_filetype = strtolower(pathinfo($orig_filename,PATHINFO_EXTENSION));

  $msg = "";
  $check = getimagesize($_FILES["upload_filename"]["tmp_name"]);
  if($check !== false) {
    // echo "File is an image - " . $check["mime"] . ".";
    $upload_good = 1;
  } else {
    $msg .= "Sorry, something looks wrong with the file.'";
    $upload_good = 0;
  }

  // Check if file already exists
  if (file_exists($orig_filename)) {
    $msg .=  "Sorry, the file already exists. ";
    $upload_good = 0;
  }

  $max_file_size = 10000000;

  // Check file size
  if ($_FILES["upload_filename"]["size"] > $max_file_size) {
    $msg .= "Sorry, the file is too big. ";
    $upload_good = 0;
  }

  // Allow certain file formats
  if($upload_filetype != "jpg" ) {
    $msg .=  "Sorry, only JPG files are allowed. ";
    $upload_good = 0;
  }

  // Check if $upload_good is set to 0 by an error
  if ($upload_good == 0) {
    $msg .= "Sorry, your file was not uploaded. ";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["upload_filename"]["tmp_name"], $orig_filename)) {
//      echo "The file ". htmlspecialchars( basename( $_FILES["upload_filename"]["name"])). " has been uploaded.";

       $small_dir = "$username/small";
       $big_dir = "$username/big";
       jwrr_mkdir($small_dir);
       jwrr_mkdir($big_dir);
       // mogrify -resize x440 -quality 100 -path small *.jpg
       exec("mogrify -resize x440 -quality 75 -path $small_dir $orig_filename", $exec_output, $exec_retval);
       exec("mogrify -resize 1024x -quality 75 -path $big_dir $orig_filename", $exec_output, $exec_retval);

    }
  }
  return $img;
 }

?>
<?php
/*
 Name: JWRR YouTube
 URI: http://jwrr.com/wp/plugins/jwrr_youtube
 Description: a plugin to include a full-size youtube link.
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: GPL3
*/


/**
 * Embed youtube video into post.
 *
 * Example: [jwrr_youtube id='5uRu14RVHAU']
 *
 * @param string $youtube_id The YouTube video's id, for example: 'u14RVHAU'
 * @param bool $enable_echo Echo breadcrumbs when True.
 * @param string $css_id The CSS id for the div
 * @return string html that embeds youtube video into post.
 */
function jwrr_youtube_function($atts = array(), $content = null, $tag = '')
{

        // $id = '5uRu14RVHAU';
	// $id = $atts['id'];
	extract( shortcode_atts( array('id' => 'undefined'), $atts ) );

	$enable_echo = False;
	$css_id = "jwrr_youtube";
	$indent_level = 5;
	$indent = str_repeat(' ', $indent_level);
	$html = <<<HEREDOC

$indent<!-- plugin: jwrr_youtube -->
<div style="clear:both"></div>
$indent<div class="$css_id">
$indent <img src="/wp-content/plugins/jwrr-youtube/images/transparent16x9.gif">
$indent <iframe src="//www.youtube.com/embed/$id" allowfullscreen></iframe>
$indent</div>

HEREDOC;

	if ($enable_echo) echo $html;
	return $html;
}

add_shortcode('youtube', 'jwrr_youtube_function');

// function register_shortcodes(){
// add_shortcode('youtube', 'jwrr_youtube_function');
//}

// add_action( 'init', 'register_shortcodes');


include_once("jwrr-signup.php");




