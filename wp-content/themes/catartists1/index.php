<?php
require_once "config.php";

// Redirect to home page after logging out
  if (!empty($_GET['action']) && $_GET['action']=='logout' && is_user_logged_in()) {
    wp_logout();
    wp_redirect('/');
    exit();
  }

get_header();


if (wp_is_mobile()) {
  echo "<style >
#theme_content {width:99%;float:none;padding:0 0 0 0; margin:0 0 0 0;}
#theme_nav {width:99%;float:none;padding:0 0 0 0; margin:0 0 0 0;}
.theme_the_title {font-size:1.3em;margin:0.5em 0 0 0em;}
.theme_the_content > p {font-size:1.3em;padding:0 0.4em 0 0.4em;}
.theme_the_thumbnail img {display:block;width:98%;}
.alignleft { display: block; float: none; padding:0.4em 0em 0.4em 0em;}
.size-medium {width:98%;}
.ddmb {position:relative;height:1.3em;}
</style>
";
}

echo "<div id='css-outer'>\n";
echo do_shortcode( '[jwrr_button_bar]' );
echo " <div id='css-main'>\n";
  
if (is_front_page()) {  
//   echo do_shortcode( '[jwrr-ddmb]' );
  $search_string = get_post_search_string();
  if ($search_string == "") {
    echo do_shortcode( '[jwrr_random_banner]' );
  }
//   jwrr_breadcrumbs();
//  get_h1();
//   get_sidebar();
  echo do_shortcode('[jwrr_search_form]');
  search_and_show_images();

} else {
  get_the_posts();
}

echo " </div> <!-- css-main -->\n";
// if (is_front_page()) {
//   echo do_shortcode( '[jwrr-social]' );
// }
echo "</div> <!-- css-outer  -->\n";

get_footer();

