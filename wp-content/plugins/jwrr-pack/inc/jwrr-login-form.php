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
  $enable_style = false;
  $login_page = "/catartist-login2.php";
  $lost_page = "/catartist-login2.php?action=lostpassword";
  $redirect_page = "/";
  $login_button_msg = "Sign In";
  $lost_password_msg = "Lost your password?";

  $html = "

<!-- jwrr_login_form -->";
  if ($enable_style) {
    $html .= <<<HEREDOC1

  <style>
    div.css-user-name-wrap {margin:1em;}
    div.css-user-pass-wrap {margin:1em;}
    div.catartist-usr input {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:40%;}
    div.catartist-pwd input {font-size:1.5em;border-color:gray;border-radius:10px;padding:0.3em;width:40%;}
    .forgetmenot {margin:1em;}
    #catartist-submit {font-size:1.5em;padding:0.5em 1em 0.5em 1em;margin-left:0.8em;border-color:gray;border-radius:10px;background-color:green;color:white;}
    h2.whoops {margin-left:05em; color:red;}
  </style>

HEREDOC1;
  }

$whoops = empty($_REQUEST['whoops']) ? '' : '<h2 class="whoops">Whoooops... Try again</h2>'; 

echo "abcde" . $REQUEST['log'] . "5555";

$html .= <<<HEREDOC2
$whoops
<div id="login">
  <form name="loginform" id="loginform" action="$login_page" method="post">
    <div class="css-oneliner">
      <label for="user_login">Email Address</label>
        <input type="text" name="log" id="user_login" class="input" value="" size="30" autofocus />
    </div>

    <div class="css-oneliner">
      <label for="user_pass">Password</label><br>
        <input type="password" name="pwd" id="user_pass" class="input password-input" value="" size="30" />
</div>

    <p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever"  /> <label for="rememberme">Remember Me</label></p>
    <div class="css-oneliner">
      <input type="submit" name="catartist-submit" id="catartist-submit" class="button button-primary button-large" value="$login_button_msg" />
    </div>
    <input type="hidden" name="redirect_to" value="$redirect_page" />
    <input type="hidden" name="testcookie" value="1" />
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

