<?php
/**
 * Template Name: Publications
 *
 * Selectable from a dropdown menu on the edit page screen.
 */
?>

<style type='text/css'>
    .entry-meta {
        display:none;
    }
</style>

<?php get_header(); ?>

  <!-- main content: primary + sidebar(s) -->
  <div id="main">
   <div id="main-inside" class="clear-block">
    <!-- primary content -->
    <div id="primary-content">
     <div class="blocks">
       <?php do_action('mystique_before_primary'); ?>
       <?php
       $type = 'publication';
       $args=array(
         'post_type' => $type,
         'post_status' => 'publish',
         'paged' => $paged,
         'posts_per_page' => 20,
         'caller_get_posts'=> 1
       );
       $temp = $wp_query;  // assign orginal query to temp variable for later use   
       $wp_query = null;
       $wp_query = new WP_Query($args);
       
        if (have_posts()):
         while (have_posts()):
          the_post();
          mystique_page();
         endwhile;
        endif;

        comments_template();
       ?>
       <?php do_action('mystique_after_primary'); ?>
     </div>
    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>

