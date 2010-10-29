<?php get_header(); ?>
	
	<div id="content_wrap">
	
		<div id="content">

			<?php if (have_posts()) : ?>
			<h2>Search results</h2>
			<?php while (have_posts()) : the_post(); ?>
			
			<div class="post_wrap">
		
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<p class="post_details"><?php the_time('F jS, Y'); ?>. Published under <?php the_category(', ') ?>. <a href="<?php comments_link(); ?>"><?php comments_number('No Comments','1 Comment','% Comments'); ?></a>.</p>
			
			<?php the_content(); ?>

			</div>
			
			<?php endwhile; ?>
			
			<div id="more_entries">
			<h2><?php next_posts_link('&laquo; Older Entries') ?> &nbsp; <?php previous_posts_link ('Recent Entries &raquo;') ?></h2>
			</div>

			<?php else : ?>

			<h2>Search results</h2>
			<p>No matches. Please try again, or use the navigation menus to find what you search for.</p>
			<?php endif; ?>
		
		</div>
	
		<?php get_sidebar(); ?>
	
	</div>

	<?php get_footer(); ?>