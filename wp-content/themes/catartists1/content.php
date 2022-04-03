   <!-- content.php (in the loop) -->
<?php if (is_home() || is_category() || is_tag()) { ?>
    <?php if (get_the_title() != "Enter Contest") { ?>
    <div class="theme_the_thumbnail">
    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail', array( 'class' => 'alignleft')); ?></a>
    </div> 
    <?php } ?>
<?php } else { ?> 
   <div class="theme_the_post">
    <h1 class="theme_the_title"><?php the_title(); ?></h1>
    <!-- <p class="theme_the_date"><?php the_date(); ?> by <a href="#"><?php the_author(); ?></a></p> -->
    <?php echo do_shortcode('[jwrr-post-tags]'); ?>
    <div class="theme_the_content"> <?php the_content(); ?>    </div>
    <div style="clear:both;"></div>
   </div>
<?php } ?>
   
