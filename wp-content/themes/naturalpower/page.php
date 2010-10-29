<?php get_header(); ?>
	
	<div id="content_wrap">
	
		<div id="content">

			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>

			<h2><?php the_title(); ?></h2>
			<?php the_content(); ?>
			<?php edit_post_link('Edit Page', '', ''); ?>

			<?php endwhile; ?>

			<?php endif; ?>

		</div>
	
		<?php get_sidebar(); ?>
	
	</div>

	<?php get_footer(); ?>