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
</style>
";
}

echo "<div id='theme_outer'>\n";
echo do_shortcode( '[jwrr_button_bar]' );
echo " <div id='theme_main'>\n";
echo do_shortcode( '[jwrr-ddmb]' );
echo do_shortcode( '[jwrr-random-banner]' );
jwrr_breadcrumbs();
get_h1();
  
if (is_front_page()) {  
  get_sidebar();
  $path = "art/*/small";
  $images = glob($path . "/*.jpg");
  
  echo "<div id=\"theme_inner\">";
  foreach($images as $image)
  {
    $b = basename($image);
    echo "<a href='/art?img=/$image'><img src=\"/$image\" class=\"small\"></a>\n";
  }
  echo '   <div style="clear:both"></div>';
  echo '   Please note that all copyright and reproduction rights remain with the artist.';
  echo '  </div>';

} else {
  get_the_posts();
  get_sidebar();
}

echo " </div> <!-- theme_main -->\n";
echo do_shortcode( '[jwrr-social]' );
echo "</div> <!-- theme_outer  -->\n";

get_footer();

