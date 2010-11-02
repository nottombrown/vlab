<?php
/**
 * Template Name: Research
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

		<div id="container">
			<div id="content">
<?php 
$type = 'research_item';
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
?>

<?php

 get_template_part( 'loop', 'index' );?>
			</div><!-- #content -->
		    </div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>