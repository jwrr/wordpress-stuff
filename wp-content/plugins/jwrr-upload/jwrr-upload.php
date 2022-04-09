<?php
/*
 Plugin Name: JWRR Upload
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

//  $upload_handler = "/art/index.php";
  $upload_handler = "/upload-handler";
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

       $small_dir = "rachel/small";
       $big_dir = "rachel/big";
       // mogrify -resize x440 -quality 100 -path small *.jpg
       exec("mogrify -resize x440 -quality 75 -path $small_dir $orig_filename", $exec_output, $exec_retval);
       exec("mogrify -resize 1024x -quality 75 -path $big_dir $orig_filename", $exec_output, $exec_retval);

    }

    $img = str_replace("orig", "big", $orig_filename);
    $chunks = explode('/', $img);
    $artist1 = $chunks[0];
  }
  return $img;
 }


