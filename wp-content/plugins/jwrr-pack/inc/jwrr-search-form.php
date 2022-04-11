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

