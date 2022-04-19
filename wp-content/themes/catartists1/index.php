<?php
require_once "config.php";

// Redirect to home page after logging out
  if (!empty($_GET['action']) && $_GET['action']=='logout' && is_user_logged_in()) {
    wp_logout();
    wp_redirect('/');
    exit();
  }

$log_files = glob('art/log*.txt');
$log_name = empty($log_files) ? 'art/log_' . bin2hex(random_bytes(10)) . '.txt' : $log_files[0];
$date = date('Y-m-d H:i:s') . ', ';
$ip = $_SERVER['REMOTE_ADDR'] . ', ';
$artsearch = empty($_POST['artsearch']) ? '' : $_POST['artsearch'];
$url = $_SERVER['REQUEST_URI'].$artsearch;
file_put_contents($log_name, $date.$ip.$url.PHP_EOL , FILE_APPEND | LOCK_EX);

// END JWRR


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

echo "<style >
#jwrr_banner {margin: 0 0 1em 0;}
#theme_content {width:100%;float:none;}
</style>
";

echo "<div id='theme_outer'>\n";
echo do_shortcode( '[jwrr_button_bar]' );
echo " <div id='theme_main'>\n";
  
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

echo " </div> <!-- theme_main -->\n";
// if (is_front_page()) {
//   echo do_shortcode( '[jwrr-social]' );
// }
echo "</div> <!-- theme_outer  -->\n";

get_footer();

