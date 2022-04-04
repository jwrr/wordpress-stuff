<?php
/*
 Plugin Name: JWRR Button Bar
 Plugin URI: https://github.com/wordpress-stuff/wp-content/plugins/jwrr_button_bar
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
<style type="text/css">
#theme_main {position:static;}
.jwrr_button_bar {position:static; width:100%; height:2.7em; background-color:black; color:white; max-width:100%; text-align: center;}
.jwrr_button_bar span {marginn:0; padding:0; font-size:1.6em; }
.jwrr_button_left {float:left; font-size:1.8em;border-radius:10px;padding:0em 1em 0.1em 1em;margin: 3px 0 3px 0.2em;background-color:green;color:white;text-decoration: none;}
a.jwrr_button_left:link {color: white; text-decoration: none;}
a.jwrr_button_left:visited {color: white; text-decoration: none;}
.jwrr_button_right {float:right; font-size:1.8em;border-radius:10px;padding:0em 1em 0.1em 1em;margin: 3px 0.2em 0 3px;background-color:green;color:white;text-decoration: none;}
a.jwrr_button_right:link {color: white; text-decoration: none;}
a.jwrr_button_right:visited {color: white; text-decoration: none;}
</style>
';
  }

  $user = _wp_get_current_user();
  $is_logged_in = $user->exists();
  
  $buttons = "";
  if ($is_logged_in) {
    $username =  ucwords($user->user_login);
    $buttons .= <<<HEREDOC_LOGGED_IN
   <a class="jwrr_button_right" href="/upload">$username</a>
   <a class="jwrr_button_right" href="/wp-login.php?action=logout">Sign Out</a>
   <a class="jwrr_button_right" href="/upload">Upload</a>
HEREDOC_LOGGED_IN;

  } else {
    $buttons .= <<<HEREDOC_LOGGED_OUT
   <a class="jwrr_button_right" href="/signin">Sign In</a>
   <a class="jwrr_button_right" href="/join">Join</a>
HEREDOC_LOGGED_OUT;
    
  }

  
  $html = <<<HEREDOC

<!-- jwrr_button_bar -->
$style

<div class="jwrr_button_bar">
   <a class="jwrr_button_left" href="/">Home</a>
   $buttons
   <span>CATARTISTS.ORG</span>
</div>
<!-- end jwrr_button_bar -->

HEREDOC;
	return $html;
}


