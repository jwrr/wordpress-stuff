<?php
/*
 Plugin Name: JWRR Mailchimp Bar
 Plugin URI: http://jwrr.com/wp/plugins/jwrr-mailchimp_bar
 Description: a plugin to add a Mailchimp signup bar at the top of the pagrandomly select drawing winnerse
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: GPL3
*/

add_shortcode('mailchimp', 'jwrr_mailchimp_bar');

function jwrr_mailchimp_bar($atts = array(), $content = null, $tag = '')
{
	extract( shortcode_atts( array('id' => 'undefined'), $atts ) );
	$user = "bf74ebf3a5bc81e8babb65a92";
	$id   = "cf5b05c87c";
	$name = "b_{$user}_{$id}";
	$signup_text = "Enter your email for my free cat, fairy, art newletter";
	$go_text = "Go!";
	$go_color = "#007000";
	$html = <<<HEREDOC

<!-- jwrr-mailchimp-bar -->
<style type="text/css">
.jwrr_mail_chimp {position:absolute;left:70px;width:100%;height:2em;background-color:blue;color:white;max-width:954px;}
#theme_main {top:2em;}
</style>

<div class="jwrr_mail_chimp">
<form action="https://Cat-Paintings.us10.list-manage.com/subscribe/post?u=$user&amp;id=$id" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
  <div id="mc_embed_signup_scroll" style="margin-top:2px;margin-left:2%;margin-right:auto;">
   <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="$signup_text" 
          style="width:75%;max-width:400px;font-size:1em;border-radius:0 10px 10px 0;padding-left:0.5em;" required>
   <input type="submit" value="$go_text" name="subscribe" id="mc-embedded-subscribe" class="button" 
         style="font-size:1em;border-radius:10px;padding:0em 0.4em 0em 0.4em;margin: 0em 0 0 0;background-color:$go_color;color:oldlace;">
   <input type="hidden" name="$name" value="">
   <!-- <span style="float:right;font-size:1.4em;margin-right:0.5em;color:white;">&#x2717;</span> -->
  </div>
</form>
</div>
<!-- end jwrr-mailchip-bar -->

HEREDOC;
	return $html;
}

?>
