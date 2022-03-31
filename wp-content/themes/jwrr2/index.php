<?php 
require_once "config.php";
get_header();
if (wp_is_mobile()) {
 echo "<style  type='text/css'>
#theme_content {width:99%;float:none;padding:0 0 0 0; margin:0 0 0 0;}
#theme_nav {width:99%;float:none;padding:0 0 0 0; margin:0 0 0 0;}
.theme_the_title {font-size:1.3em;margin:0.5em 0 0 0em;}
.theme_the_content > p {font-size:1.3em;padding:0 0.4em 0 0.4em;}
.theme_the_thumbnail img {display:block;width:98%;}
.alignleft { display: block; float: none; padding:0.4em 0em 0.4em 0em;}
.size-medium {width:98%;}
.ddmb {position:relative;height:1.3em;}
</style>\n";
}
echo "<div id='theme_outer'>\n";
 echo do_shortcode( '[mailchimp]' );
 echo " <div id='theme_main'>\n";
  echo do_shortcode( '[jwrr-ddmb]' );
  echo do_shortcode( '[jwrr-random-banner]' );
  jwrr_breadcrumbs();
  get_h1();
  get_the_posts();
  get_sidebar(); 
  echo " </div> <!-- theme_main -->\n";
 echo do_shortcode( '[jwrr-social]' );
echo "</div> <!-- theme_outer  -->\n";

get_footer(); 

