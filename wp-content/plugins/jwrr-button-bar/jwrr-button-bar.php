<?php
/*
 Plugin Name: JWRR Button Bar
 Plugin URI: https://github.com/wordpress-stuff/wp-content/plugins/jwrr-button_bar
 Description: a plugin to add a button bar at the top of the page
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: MIT
*/

add_shortcode('jwrr-button-bar', 'jwrr_button_bar');

function jwrr_button_bar($atts = array(), $content = null, $tag = '')
{
	extract( shortcode_atts( array('id' => 'undefined'), $atts ) );
	$user = "bf74ebf3a5bc81e8babb65a92";
	$id   = "cf5b05c87c";
	$name = "b_{$user}_{$id}";
	$signup_text = "Enter your email for our free newsletter";
	$go_text = "Sign Up";
	$go_color = "#007000";
	$html = <<<HEREDOC

<!-- jwrr-button-bar -->
<style type="text/css">
.jwrr_button_bar {position:absolute; left:70px; width:100%; height:2.7em; background-color:black; color:white; max-width:100%;}
#theme_main {top:2.7em;}
.button_left {float:left; font-size:1.8em;border-radius:10px;padding:0em 1em 0.1em 1em;margin: 3px 0 3px 0.2em;background-color:$go_color;color:oldlace;text-decoration: none;}
a.button_left:link {color: oldlace; text-decoration: none;}
a.button_left:visited {color: gray; text-decoration: none;}
.button_right {float:right; font-size:1.8em;border-radius:10px;padding:0em 1em 0.1em 1em;margin: 3px 0.2em 0 3px;background-color:$go_color;color:oldlace;text-decoration: none;}
a.button_right:link {color: oldlace; text-decoration: none;}
a.button_right:visited {color: gray; text-decoration: none;}
</style>

<div class="jwrr_button_bar">
   <a class="button_left" href="/">Home</a>
   <a class="button_right" href="/join">Join</a>
   <a class="button_right" href="/signin">Sign In</a>
   <a class="button_right" href="/upload">Upload</a>
</div>
<!-- end jwrr-mailchip-bar -->

HEREDOC;
	return $html;
}

