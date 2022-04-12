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
  div.jwrr_upload_form h2 {margin:0; padding:0;}
  div.jwrr-upload-form-please-log-in {font-size: 2.0em; font-weight:bold;margin:1em;}

    div.user-name-wrap {margin:1em;}
    div.user-pass-wrap {margin:1em;}
    div.jwrr-oneliner {padding: 1em 0 0 1em; font-size:1.5em;}
    div.jwrr-oneliner label {display:block; margin-left:0.5em;}
    div.jwrr-oneliner input.jwrr-submit {display:block; margin-left:0em; background-color:green; color:white;}
    div.jwrr-oneliner input {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:68%;}
    div.jwrr-oneliner textarea {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:68%;}
    div.jwrr-oneliner select {display:block; margin-left:0em; font-size:1.1em;border-color:gray;border-radius:10px;padding:0.3em;}
    div.jwrr-checkboxes {display:block; margin-left:1.5em; font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;}
    div.jwrr-checkbox {display:inline; padding-right:3em;}
    div.jwrr-checkbox input {width:2em; height: 2em;}
    .forgetmenot {margin:1em;}
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
  <form action="$upload_handler" method="post" enctype="multipart/form-data">
      <div class=jwrr-oneliner>
        <label for "upload_filename">$select_file_msg</label>
        <input class="jwrr_upload_form_file" type="file" name="upload_filename" id="upload_filename">
      </div>
      <div class=jwrr-oneliner>
        <label for="copyright">Choose a Copyright Notice:</label>
        <select class="jwrr_upload_form_select" id="copyright" name="copyright">
          <option value="all" selected>All rights reserved</option>
          <option value="none">None</option>
          <option value="BY-NC-ND">BY-NC-ND Needs Attribution, non-commercial and no derivatives</option>
          <option value="BY-ND">BY-ND Needs Attribution and no derivatives</option>
          <option value="CC0">CC0 Free content with no restrictions</option>
        </select><br>
      </div>

      <div class=jwrr-checkboxes>
        <div class="jwrr-checkbox">
          <label>Add Watermark</label> <input type="checkbox" name="watermark" id="watermark" value="yes" checked="true">
        </div>
        <div class="jwrr-checkbox">
          <label>Shred it like your cat would</label><input type="checkbox" name="shred" id="shred" value="yes" checked="true">
        </div>
      </div>

      <div class=jwrr-oneliner>
      </div>
      <div class=jwrr-oneliner>
        <label for="title">Title (Optional)</label>
        <input class="jwrr_upload_form_title" type="text" name="title" id="title" cols="60" style="font-size:1.3em;"><br>
      </div>

      <div class=jwrr-oneliner>
        <label for="description">Description (Optional))</label>
        <textarea name="description" id="description" cols="111" rows="10"></textarea><br>
      </div>

      <div class=jwrr-oneliner>
        <input class="jwrr-submit" type="submit" value="Upload Image" name="submit">
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

