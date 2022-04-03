<?php
/*
 Plugin Name: JWRR Upload Form
 Plugin URI: https://github.com/wordpress-stuff/wp-content/plugins/jwrr-upload-form
 Description: a plugin to add an upload form to a page
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: MIT
*/

add_shortcode('jwrr_upload_form', 'jwrr_upload_form');

function jwrr_upload_form($atts = array(), $content = null, $tag = '')
{

  $upload_handler = "/art/index.php";
  $enable_style = true;
  $please_log_in_msg = "Please Log In";
  $select_file_msg = "Select file to upload";

  $html = "

<!-- jwrr-upload-form -->";
  if ($enable_style) {
    $html .= <<<HEREDOC1

  <style type="text/css">
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
    <input class="jwrr_upload_form_file" type="file" name="fileToUpload" id="fileToUpload">
    <input class="jwrr_upload_form_submit" type="submit" value="Upload Image" name="submit">
  </form>
  </div>

HEREDOC2;
  }

  $html .= "
<!-- end jwrr-upload-form -->

";

  return $html;
}


