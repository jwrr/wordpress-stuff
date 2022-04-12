<?php
/*
 Name: JWRR Pack
 URI: http://jwrr.com/wp/plugins/jwrr-pack
 Description: contains may small plugins and shortcodes
 Version: 0.1
 Author: jwrr
 Author URI: http://jwrr.com
 License: GPL3
*/


function jwrr_signup_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {



    echo '
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
  </style>
 



    </style>
    ';

    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">

    <div class="jwrr-oneliner">
    <label for="email">Email (required). You\'re email is kept private</label>
    <input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '" autofocus>
    </div>

    <div class="jwrr-oneliner">
    <label for="password">Password (required)</label>
    <input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '">
    </div>

    <div class="jwrr-oneliner">
    <label for="firstname">First Name (Optional)</label>
    <input type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '">
    </div>

    <div class="jwrr-oneliner">
    <label for="website">Last Name (Optional)</label>
    <input type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '">
    </div>

    <div class="jwrr-oneliner">
    <label for="username">Username (Optional)</label>
    <input type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '">
    </div>

    <div class="jwrr-oneliner">
    <label for="website">Website (Optional)</label>
    <input type="text" name="website" placeholder="https://rachelarmington.com/" value="' . ( isset( $_POST['website']) ? $website : null ) . '">
    </div>

    <div class="jwrr-oneliner">
    <label for="social">Social such as Facebook, Instagram, Twitter... (Optional)</label>
    <input type="text" name="website" placeholder="https://facebook.com/RachelArmingtonPaints" value="' . ( isset( $_POST['website']) ? $website : null ) . '">
    </div>

    <div class="jwrr-oneliner">
    <label for="social">Another Social (Optional)</label>
    <input type="text" name="website" placeholder="https://pinterest.com/CatPaintings" value="' . ( isset( $_POST['website']) ? $website : null ) . '">
    </div>

    <div class="jwrr-oneliner">
    <label for="social">And maybe one more Social (Optional)</label>
    <input type="text" name="website" placeholder="https://twitter.com/rachelarmington" value="' . ( isset( $_POST['website']) ? $website : null ) . '">
    </div>

    <div class="jwrr-oneliner">
    <label for="bio">About / Bio  (Optional)</label>
    <textarea name="bio">' . ( isset( $_POST['bio']) ? $bio : null ) . '</textarea>
    </div>
    <div class="jwrr-oneliner">
    <input type="submit" name="submit" value="Register"/>
    </div>
    </form>
    ';
}

function jwrr_signup_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio )
{
  global $reg_errors;
  $reg_errors = new WP_Error;

  if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
      $reg_errors->add('field', 'Required form field is missing');
  }

  if ( 4 > strlen( $username ) ) {
      $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
  }

  if ( username_exists( $username ) )
      $reg_errors->add('user_name', 'Sorry, that username already exists!');

  if ( ! validate_username( $username ) ) {
      $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
  }

  if ( 5 > strlen( $password ) ) {
          $reg_errors->add( 'password', 'Password length must be greater than 5' );
  }

  if ( !is_email( $email ) ) {
      $reg_errors->add( 'email_invalid', 'Email is not valid' );
  }

  if ( email_exists( $email ) ) {
      $reg_errors->add( 'email', 'Email Already in use' );
  }

  if ( ! empty( $website ) ) {
      if ( ! filter_var( $website, FILTER_VALIDATE_URL ) ) {
          $reg_errors->add( 'website', 'Website is not a valid URL' );
      }
  }

  if ( is_wp_error( $reg_errors ) ) {

      foreach ( $reg_errors->get_error_messages() as $error ) {

          echo '<div>';
          echo '<strong>ERROR</strong>:';
          echo $error . '<br/>';
          echo '</div>';

      }

  }

}

function jwrr_signup_update_database() {
    global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'user_url'      =>   $website,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        'nickname'      =>   $nickname,
        'description'   =>   $bio,
        );
        $user = wp_insert_user( $userdata );
        echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';
    }
}

function jwrr_signup_wrapper() {
    if ( isset($_POST['submit'] ) ) {
        jwrr_signup_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email'],
        $_POST['website'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['nickname'],
        $_POST['bio']
        );

        // sanitize user form input
        global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $website    =   esc_url( $_POST['website'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );
        $nickname   =   sanitize_text_field( $_POST['nickname'] );
        $bio        =   esc_textarea( $_POST['bio'] );

        // call @function jwrr_signup_update_database to create the user
        // only when no WP_error is found
        jwrr_signup_update_database(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );
    }

    jwrr_signup_form(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );

  }


  // Register a new shortcode: [cr_custom_registration]
  add_shortcode( 'jwrr_signup', 'jwrr_signup' );

  // The callback function that will replace [book]
  function jwrr_signup() {
      ob_start();
      jwrr_signup_wrapper();
      return ob_get_clean();
  }
  
