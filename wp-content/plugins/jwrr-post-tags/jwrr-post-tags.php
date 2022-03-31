<?php
/*
 Plugin Name: JWRR Post Tags
 Plugin URI: http://jwrr.com/wp/plugins/jwrr-post-tags
 Description: a plugin to display the tags for a post.
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: GPL3
*/

add_shortcode('jwrr-post-tags', 'jwrr_post_tags');
function jwrr_post_tags()
{
	$indent = str_repeat(' ', 4);
	$html = '';
	$post_tags = get_the_tags();
	if  ($post_tags) {
		$t = count($post_tags) > 1 ? 'Tags:' : 'Tag:'; 
		$html =	"\n$indent<!-- plugin: jwrr-post-tags -->\n" . "
<style type=\"text/css\">
ul.jwrr_post_tags {margin:0.4em 0 0.4em 0.3em;padding:0 0 0 0;}
ul.jwrr_post_tags li {
display: inline;
line-height: 2.2em;
font-size: 1.1em;
list-style: none;
padding: 0.2em 1.5% 0.2em 1.5%;
margin: 0.3em 0.5em 0.3em 0;
border-radius: 15px;
background-color: #f2c7cd;
white-space: nowrap;
border-style:none;
border-width:3px;
border-color:black;
box-shadow: 5px 5px 5px #aaa;
-moz-box-shadow: 5px 5px 5px #aaa;
-webkit-box-shadow: 5px 5px 5px #aaa;
}
ul.jwrr_post_tags li:hover {
    background-color: lavender;
}
 
ul.jwrr_post_tags li:first-child {background-color:lightgray;}
a.jwrr_button {
    -webkit-appearance: button;
    -moz-appearance: button;
    appearance: button;
    text-decoration: none;
    color: initial;}
</style>" .
		"$indent<ul class='jwrr_post_tags'>\n$indent <li>$t</li>\n"; 
		foreach( $post_tags as $tag ) {
			$tag_name = $tag->name;
			$tag_count = $tag->count;
			$href = "/tag/$tag_name";
			$a_text = ucwords($tag_name) . " ($tag_count)";
			$html .= "$indent <li><a href='$href'>$a_text</a></li>\n"; 
		}
		$html .= "$indent</ul>\n\n";
	}
	return $html;
}

