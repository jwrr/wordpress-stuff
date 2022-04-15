<?php
/*
 Name: JWRR Login Form
 URI: https://github.com/wordpress-stuff/wp-content/plugins/jwrr-login-form
 Description: a plugin to add an login form to a page
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: MIT
*/

add_shortcode('jwrr_login_form', 'jwrr_login_form');

function jwrr_login_form($atts = array(), $content = null, $tag = '')
{
  $login_page = "/wp-login2.php";
  $lost_page = "/wp-login2.php?action=lostpassword";
  $redirect_page = "/";
  $enable_style = true;
  $login_button_msg = "Sign In";
  $lost_password_msg = "Lost your password?";

  


  $html = "

<!-- jwrr_login_form -->";
  if ($enable_style) {
    $html .= <<<HEREDOC1

  <style>
    div.user-name-wrap {margin:1em;}
    div.user-pass-wrap {margin:1em;}
    div.wp-usr input {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:40%;}
    div.wp-pwd input {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:40%;}
    .forgetmenot {margin:1em;}
    #wp-submit {font-size:1.5em;padding:0.5em 1em 0.5em 1em;margin-left:0.8em;border-color:gray;border-radius:10px;background-color:green;color:white;}
    h2.whoops {margin-left:05em; color:red;}
  </style>

HEREDOC1;
  }

$whoops = $_REQUEST['whoops'] ? '<h2 class="whoops">Whoooops... Try again</h2>' : ''; 

$html .= <<<HEREDOC2
$whoops
<div id="login">
  <form name="loginform" id="loginform" action="$login_page" method="post">
    <div class="user-name-wrap">
      <label for="user_login">Email Address</label>
      <div class="wp-usr">
        <input type="text" name="log" id="user_login" class="input" value="" size="30" autofocus />
      </div>
    </div>
    
    <div class="user-pass-wrap">
      <label for="user_pass">Password</label><br>
      <div class="wp-pwd">
        <input type="password" name="pwd" id="user_pass" class="input password-input" value="" size="30" />
      </div>
    </div>
    
    <p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever"  /> <label for="rememberme">Remember Me</label></p>
    <p class="submit">
      <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="$login_button_msg" />
      <input type="hidden" name="redirect_to" value="$redirect_page" />
      <input type="hidden" name="testcookie" value="1" />
    </p>
  </form>
  
  <p id="nav">
    <a href="$lost_page">$lost_password_msg</a>
  </p>
</div>  
HEREDOC2;

  $html .= "
<!-- end jwrr_login_form -->

";

  return $html;
}

