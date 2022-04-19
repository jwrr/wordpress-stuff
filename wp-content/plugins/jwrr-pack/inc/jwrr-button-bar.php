<?php
/*
 Name: JWRR Button Bar
 URI: https://github.com/wordpress-stuff/wp-content/plugins/jwrr_button_bar
 Description: a plugin to add a button bar at the top of the page
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: MIT
*/

add_shortcode('jwrr_button_bar', 'jwrr_button_bar');

function jwrr_button_bar($atts = array(), $content = null, $tag = '')
{

  $enable_style = true;
  $style = "";
  if ($enable_style) {
    $style .=
'
<style>
#theme_main {position:static;}
.jwrr_button_bar {position:static; width:100%; min-height: 3.3em; background-color:black; color:white; max-width:100%; text-align: center;}
.jwrr_button_bar span {margin:0; padding:0; font-size:1.6em; }
.jwrr_button {font-size:1.3em;border-radius:10px;padding:0.5em 0.5em 0.5em 0.5em;margin: 3px 0.2em 0 3px;background-color:green;color:white;text-decoration: none; min-width: 3em;}
a.jwrr_button:link {color: white; text-decoration: none;}
a.jwrr_button:visited {color: white; text-decoration: none;}
.right {float: right;}
.left  {float: left;}
.jwrr_button_bar span {white-space: nowrap;}
</style>
';
  }

  $user = _wp_get_current_user();
  $is_logged_in = $user->exists();
  
  $buttons = "";
  if ($is_logged_in) {
    $username =  ucwords($user->user_login);
    $buttons .= <<<HEREDOC_LOGGED_IN
   <a class="jwrr_button right" href="/index.php/signup">$username</a>
   <a class="jwrr_button right" href="/index.php/signin?action=logout">Sign Out</a>
   <a class="jwrr_button left" href="/index.php/upload">Upload</a>
HEREDOC_LOGGED_IN;

  } else {
    $buttons .= <<<HEREDOC_LOGGED_OUT
   <a class="jwrr_button right" href="/index.php/signin">Sign In</a>
   <a class="jwrr_button right" href="/index.php/signup">Join</a>
HEREDOC_LOGGED_OUT;
    
  }


  $a = jwrr_parse_img_path();
  
  $artist_username = empty($a['username']) ? '' : $a['username'];
  $art_title = empty($a['title']) ? '' : $a['title'];
  $artist_fullname = empty($a['fullname']) ? '' : $a['fullname'];
  $name = $artist_fullname=='' ? 'CATARTISTS.ORG' :  "Artwork by $artist_fullname";

  $html = <<<HEREDOC

<!-- jwrr_button_bar -->
$style

<div class="jwrr_button_bar">
   <a class="jwrr_button left" href="/">Home</a>
   $buttons
   <span>$name</span>
</div>
<!-- end jwrr_button_bar -->

HEREDOC;
	return $html;
}

