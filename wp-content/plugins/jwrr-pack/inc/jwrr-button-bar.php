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
.jwrr_button {font-size:1.3em;border-radius:10px;padding:0.5em 0.2em 0.5em 0.2em;margin: 3px 0.15em 0 3px;background-color:green;color:white;text-decoration: none; width: 3.4em; overflow: hidden; white-space: nowrap; }
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

// <a class="jwrr_button right" href="/index.php/signup">$firstname</a>

  $buttons = "";
  if ($is_logged_in) {
    $username =  ucwords($user->user_login);
    $firstname = ucwords(jwrr_get_firstname());
    $fullname_with_dash = jwrr_get_fullname('', '-');
    $buttons .= <<<HEREDOC_LOGGED_IN
   <a class="jwrr_button right" href="/index.php/show/$fullname_with_dash">$firstname</a>
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




function jwrr_change_page_form($delete_button='')
{
  $username_required = 'Must be lowercase letters or hyphen and at least 4 letters. For example: cat-artists';
  $username_readonly = '';
  $password_required = 'Required';
  $submit_value = "Register";
  if (jwrr_is_logged_in()) {
    $username_readonly = 'readonly="readonly"';
    $username_required = 'Locked';
    $password_required = 'Leave blank if you do not want to change your password';
    $submit_value      = 'Update Title';

    $userdata    = jwrr_get_userdata();
    if (empty($link))  $link   = $userdata->link;
    if (empty($title)) $title  = $userdata->title;
  }

  $link_placeholder = (empty($link) || $website=='') ? 'placeholder="zazzle.com/catartists"' : '';
  $title = (empty($title) || $title=='') ? 'placeholder="abc-def-ghi"' : '';
  $html = '';
  $html .=  '
  <style>
  div {
      margin-bottom:2px;
  }

  input{
      margin-bottom:4px;
  }

  input.jwrr_upload_form_file {color:white;background-color:green; padding:0.5em; font-size:1.5em; border-radius:10px; width:75%;}
  div.jwrr_upload_form h2 {margin:0; padding:0;}
  div.jwrr-upload-form-please-log-in {font-size: 2.0em; font-weight:bold;margin:1em;}

    div.user-name-wrap {margin:1em;}
    div.user-pass-wrap {margin:1em;}
    div.jwrr-oneliner {padding: 1em 0 0 1em;}
    div.jwrr-oneliner label {display:block; margin-left:0.5em;}
    div.jwrr-oneliner input[type=submit] {display:block; margin-left:0em; background-color:green; color:white;}
    div.jwrr-oneliner input {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:68%;}
    div.jwrr-oneliner textarea {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:68%;}
    div.jwrr-oneliner select {display:block; margin-left:0em; font-size:1.1em;border-color:gray;border-radius:10px;padding:0.3em;}
    div.jwrr-checkboxes {display:block; margin-left:1.5em; font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;}
    div.jwrr-checkbox {display:inline; padding-right:3em;}
    div.jwrr-checkbox input {width:2em; height: 2em;}
    div.jwrr-error {color:red; font-size: 2em;margin-left:0.5em;}
    div.jwrr-success {color:green; font-size: 2em;margin-left:0.5em;}
  </style>
';

    $a = jwrr_parse_img_path();
    $title = ucwords( strtolower(preg_replace('/[^a-z0-9]+/iu', ' ', $a['title'] )) );

    $uri = $_SERVER['REQUEST_URI'];
    if (jwrr_count_images() == 0) return "<h2>Welcome to Cat Artists! Click the 'Upload' button to send us your cats!</h2>";
    $html .=  <<<HEREDOC
    <form action="$uri" method="post">

    <div class="jwrr-oneliner">
    <label for="title">Title</label>
    <input type="text" name="title" value="$title">
    </div>

<!--
    <div class="jwrr-oneliner">
    <label for="link">Merchant Link</label>
    <input type="text" name="link" $link_placeholder value="$link">
    </div>
-->
<div>
    $delete_button
    <div class="jwrr-oneliner" style = "float:left;padding:0.15em; width:46.5em; max-width:50%;">
    <input style="padding:0.6em;" type="submit" name="submit" value="$submit_value"/>
    </div>
</div>
<div style="clear:both;" ></div>
    </form>
HEREDOC;
    return $html;
}





function jwrr_buybar($buy_platform, $buy_platform_icon, $buy_url)
{
  $logged_in_artist_fullname_with_dash = jwrr_get_fullname('', '-');
  $request_uri = ($_SERVER['REQUEST_URI']);
  $is_owner = str_contains($request_uri, "/$logged_in_artist_fullname_with_dash/");
  $delete_button = '';
  if ($is_owner && !str_contains($request_uri, '/delete/')) {
    $delete_uri = $request_uri . 'delete';
    $delete_button = "<a class=\"jwrr_button left\" style=\"background-color: red; width:5em; padding:1em; margin:auto 1em auto 1em;\" href=\"$delete_uri\">Delete Page</a>\n";
    $change_form = jwrr_change_page_form($delete_button);
    $buy_button = '';
  } else {
    $change_form = '';
    $buy_button = <<<BUYBUTTON
    <div class="jwrr_buyitem">
      <a href="$buy_url"><img src="$buy_platform_icon"></a>
      <div><a href="$buy_url">Available on $buy_platform</a></div>
    </div>
BUYBUTTON;
  }
  $html = <<<HEREDOC
  <div class="jwrr_buybar">
    <hr>
    </div>
    $change_form
    $buy_button
    <hr>
  </div>
HEREDOC;
return $html;
}


