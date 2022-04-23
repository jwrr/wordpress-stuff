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


function jwrr_signup_form( $username, $password, $email, $website, $first_name, $last_name, $bio) // $social1, $social2, $social3 )
{
  $username_required = 'Must be lowercase letters or hyphen and at least 4 letters. For example: cat-artists';
  $readonly = '';
  $password_required = 'Required';
  $submit_value = "Register";
  $firstname_autofocus = ' autofocus ';
  $email_autofocus = '';
  $name_required_locked = 'Required';
  if (jwrr_is_logged_in()) {
    $readonly = 'readonly="readonly"';
    $username_required = 'Locked';
    $password_required = 'Leave blank if you do not want to change your password';
    $submit_value      = 'Update';
    $firstname_autofocus = '';
    $email_autofocus = ' autofocus ';
  $name_required_locked = 'Locked';

    $userdata    = jwrr_get_userdata();
//     $usermeta = jwrr_get_usermeta();
//     var_dump($usermeta);
    $username    = $userdata->user_login;
    if (empty($email))      $email       = $userdata->user_email;
    if (empty($website))    $website     = $userdata->user_url;
    if (empty($first_name)) $first_name  = $userdata->first_name;
    if (empty($last_name))  $last_name   = $userdata->last_name;
    if (empty($bio))        $bio         = $userdata->description;
//     $social1     = $usermeta->social1;
//     $social2     = $usermeta->social2;
//     $social3     = $usermeta->social3;
  }

  $website_placeholder = (empty($website) || $website=='') ? 'placeholder="widgetbluesky.com"' : '';
  $social1_placeholder = (empty($social1) || $social1=='') ? 'placeholder="facebook.com/tickles"' : '';
  $social2_placeholder = (empty($social2) || $social2=='') ? 'placeholder="pinterest.com/nikos"' : '';
  $social3_placeholder = (empty($social3) || $social3=='') ? 'placeholder="twitter.com/maurina"' : '';

    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">

    <div class="css-oneliner">
    <label for="fname">First Name (' . $name_required_locked . ')</label>
    <input type="text" name="fname" value="' . $first_name . '" ' . $firstname_autofocus . $readonly . '>
    </div>

    <div class="css-oneliner">
    <label for="lname">Last Name (' . $name_required_locked . ')</label>
    <input type="text" name="lname" value="' . $last_name . '" ' . $readonly . '>
    </div>

    <div class="css-oneliner">
    <label for="email">Email (Required). We need this to contact you. Your privacy is imporant and we don\'t share your info with anyone.</label>
    <input type="text" name="email" value="' . $email . '"' . $email_autofocus  .'>
    </div>

    <div class="css-oneliner">
    <label for="password">Password (' . $password_required .  ') Passwords are encrypted so even we can\'t see what they are</label>
    <input type="password" name="password" value="' . $password .'">
    </div>

    <div class="css-oneliner">
    <input type="submit" name="submit" value="' . $submit_value  . '"/>
    </div>
    </form>
    ';

//     <div class="css-oneliner">
//     <label for="website">Website (Optional)</label>
//     <input type="text" name="website" ' . $website_placeholder . ' value="' . $website . '">
//     </div>
//
//     <div class="css-oneliner">
//     <label for="social1">Social such as Facebook, Instagram, Twitter... (Optional)</label>
//     <input type="text" name="social1" ' . $social1_placeholder . ' value="' . $social1 . '">
//     </div>
//
//     <div class="css-oneliner">
//     <label for="social2">Another Social (Optional)</label>
//     <input type="text" name="social2" ' . $social2_placeholder . ' value="' . $social2 . '">
//     </div>
//
//     <div class="css-oneliner">
//     <label for="social3">And maybe one more Social (Optional)</label>
//     <input type="text" name="social3" ' . $social3_placeholder . ' value="' . $social3 . '">
//     </div>
//
//     <div class="css-oneliner">
//     <label for="bio">About / Bio  (Optional)</label>
//     <textarea name="bio">' . $bio . '</textarea>
//     </div>

//     ';
}

function jwrr_signup_validation( $username, $password, $email, $website, $first_name, $last_name, $bio) // , $social1, $social2, $social3 )
{
  global $reg_errors;
  $reg_errors = new WP_Error;
  $is_logged_in = jwrr_is_logged_in();
  $error_count = 0;
  if ($is_logged_in) {
    if ($username != jwrr_get_username()) {
      $reg_errors->add('username_wrong', 'The username is not correct');
      $error_count++;
    }
  } else {
    if (empty($username)) {
      $reg_errors->add('username_missing', 'Username is required');
      $error_count++;
    } else if (!validate_username($username)) {
      $reg_errors->add( 'username_invalid', 'The username is not valid' );
      $error_count++;
    } else if (strlen($username) < 4 ) {
      $reg_errors->add( 'username_length', 'Username must be at least 4 letters long' );
      $error_count++;
    } else if (username_exists( $username ) ) {
      $reg_errors->add('username_exists', 'The username already exists!');
      $error_count++;
    }
  }

  $password_len = strlen($password);
  if ($is_logged_in) {
    if (!empty($password) && $password_len > 0 && $password_len < 5) {
      $reg_errors->add( 'password_length', 'Password length must be at least 5 characters' );
      $error_count++;
    }
  } else {
    if (empty($password)) {
      $reg_errors->add('password_missing', 'Password is required');
      $error_count++;
    } else if ($password_len < 5) {
      $reg_errors->add( 'password_length', 'Password length must be at least 5 characters' );
      $error_count++;
    }
  }

  if ($is_logged_in) {
    $current_user_email = jwrr_get_email();
    if (!empty($email) && $email != $current_user_email && email_exists($email) ) {
      $reg_errors->add( 'email_existsl', 'Email Already in use' );
      $error_count++;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $reg_errors->add( 'email_invalid', 'Email doesn\'t look right' );
      $error_count++;
    }
  } else {
    if (empty($email)) {
      $reg_errors->add('email_missing', 'Email is required');
      $error_count++;
    } else if (email_exists($email)) {
      $reg_errors->add( 'email_exists', 'Email Already in use' );
      $error_count++;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $reg_errors->add( 'email_invalid', 'Email doesn\'t look right' );
      $error_count++;
    }
  }

//   if (!empty($website)) {
//       if (!str_starts_with(strtolower($website), 'http')) $website = 'https://' . $website;
//       if ( ! filter_var( $website, FILTER_VALIDATE_URL ) ) {
//           $reg_errors->add( 'website', 'Website is not a valid URL' );
//       }
//   }
//
//   if (!empty($social1)) {
//       if (!str_starts_with(strtolower($social1), 'http')) $social1 = 'https://' . $social1;
//       if ( ! filter_var( $social1, FILTER_VALIDATE_URL ) ) {
//           $reg_errors->add( 'social1', 'Social link is not a valid URL' );
//       }
//   }
//
//   if (!empty($social2)) {
//       if (!str_starts_with(strtolower($social2), 'http')) $social2 = 'https://' . $social2;
//       if ( ! filter_var( $social2, FILTER_VALIDATE_URL ) ) {
//           $reg_errors->add( 'social2', 'Social link is not a valid URL' );
//       }
//   }
//
//   if (!empty($social3)) {
//       if (!str_starts_with(strtolower($social3), 'http')) $social3 = 'https://' . $social3;
//       if ( ! filter_var( $social3, FILTER_VALIDATE_URL ) ) {
//           $reg_errors->add( 'social3', 'Social link is not a valid URL' );
//       }
//   }

  if (is_wp_error($reg_errors)) {
     foreach ($reg_errors->get_error_messages() as $error) {
       echo '<div class="css-error"><strong>ERROR</strong>: ' . $error . '</div>';
     }
  }
  return ($error_count > 0);
}

function jwrr_signup_update_database() {
  $new_account_successfully_created = false;
  global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $bio;  // , $social1, $social2, $social3;
  $new_userdata = array(
    'user_login'    =>   $username,
    'user_email'    =>   $email,
    'user_pass'     =>   $password,
//  'user_url'      =>   $website,
    'first_name'    =>   $first_name,
    'last_name'     =>   $last_name,
    'description'   =>   $bio,
  );

  if (jwrr_is_logged_in()) {
    $new_userdata['ID'] = jwrr_get_userid();
    $userid = wp_update_user($new_userdata);
    $account_update_successful = !is_wp_error($userid);
    if ($account_update_successful) {
      echo '<div class="css-success"><strong>Success! </strong<strong>Your info has been updated.</a></div>';
    } else {
      echo '<div class="css-error"><strong>Sorry! </strong<strong>We could not update your info</a></div>';
    }
  } else {
    $userid = wp_insert_user($new_userdata);
    $new_account_successfully_created = !is_wp_error($userid);
    if ($new_account_successfully_created) {
      echo '<div class="css-success"><strong>Success! </strong>You can now <a href="/signin">Sign In</a></div>';
    } else {
      echo '<div class="css-error"><strong>Sorry! </strong>We could not create your account</div>';
    }
  }

// if (!enmpty($social1)) add_user_meta( $user->ID, 'jwrr_social1', $social1);
// if (!enmpty($social2)) add_user_meta( $user->ID, 'jwrr_social2', $social2);
// if (!enmpty($social3)) add_user_meta( $user->ID, 'jwrr_social3', $social3);

  return $new_account_successfully_created;
}


function jwrr_signup_wrapper() {
  $new_account_successfully_created = false;
  $_POST['fname']    = empty($_POST['fname']) ? '' : $_POST['fname'];
  $_POST['lname']    = empty($_POST['lname']) ? '' : $_POST['lname'];
  $_POST['username'] = strtolower($_POST['fname'] . '-' . $_POST['lname']);
  $_POST['password'] = empty($_POST['password']) ? '' : $_POST['password'];
  $_POST['email']    = empty($_POST['email'])    ? '' : $_POST['email'];
  $_POST['website']  = empty($_POST['website'])  ? '' : $_POST['website'];
  $_POST['bio']      = empty($_POST['bio'])      ? '' : $_POST['bio'];

  if (isset($_POST['submit'])) {
    
    
    $validation_error = jwrr_signup_validation(
       $_POST['username'],
       $_POST['password'],
       $_POST['email'],
       $_POST['website'],
       $_POST['fname'],
       $_POST['lname'],
       $_POST['bio']
//     $_POST['social1'],
//     $_POST['social2'],
//     $_POST['social3']
    );
        
    if (!$validation_error) {
      // sanitize user form input
      global $username, $password, $email, $website, $first_name, $last_name, $bio, $social1, $social2, $social3;
      $username   =   jwrr_is_logged_in() ? jwrr_get_username() : sanitize_user( $_POST['username'] );
      $password   =   esc_attr( $_POST['password'] );
      $email      =   sanitize_email( $_POST['email'] );
      $website    =   esc_url( $_POST['website'] );
      $first_name =   sanitize_text_field( $_POST['fname'] );
      $last_name  =   sanitize_text_field( $_POST['lname'] );
      $bio        =   esc_textarea( $_POST['bio'] );
//    $social1    =   esc_url( $_POST['social1'] );
//    $social2    =   esc_url( $_POST['social2'] );
//    $social3    =   esc_url( $_POST['social3'] );

      $new_account_successfully_created = jwrr_signup_update_database(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $bio
      );
    }
  }

  if (!$new_account_successfully_created) {
  
  
    jwrr_signup_form(
      $_POST['username'],
      $_POST['password'],
      $_POST['email'],
      $_POST['website'],
      $_POST['fname'],
      $_POST['lname'],
      $_POST['bio']
//  $_POST['social1'],
//  $_POST['social2'],
//  $_POST['social3']
  );
    
  }


}


  //  a new shortcode: [cr_custom_registration]
  add_shortcode( 'jwrr_signup', 'jwrr_signup' );

  // The callback function that will replace [book]
  function jwrr_signup() {
      ob_start();
      jwrr_signup_wrapper();
      return ob_get_clean();
  }

