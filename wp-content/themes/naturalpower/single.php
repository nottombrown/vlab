<?php get_header(); ?>
	
	<div id="content_wrap">
	
		<div id="content">

			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
			
			<div class="post_wrap">
		
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<p class="post_details"><?php the_time('F jS, Y'); ?>. Published under <?php the_category(', ') ?>. <a href="<?php comments_link(); ?>"><?php comments_number('No Comments','1 Comment','% Comments'); ?></a>.</p>
			
			<?php the_content(); ?>

			</div>
			
			<?php comments_template(); ?>

			<?php endwhile; else: ?>

			<p>Sorry, no posts matched your criteria.</p>

			<?php endif; ?>
		
		</div>
	
		<?php get_sidebar(); ?>
	
	</div>

	<?php get_footer(); ?>