<?php
/*
 Plugin Name: JWRR Random Banner
 Plugin URI: http://jwrr.com/wp/plugins/jwrr-random-banner
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
