<?php get_header(); ?>
	
	<div id="content_wrap">
	
		<div id="content">

			<?php if (have_posts()) : ?>
			<?php $post = $posts[0]; ?>

			<?php if (is_category()) { ?><h2>Archive for '<?php echo single_cat_title(); ?>'</h2>
			<?php } elseif (is_day()) { ?><h2>Archive for <?php the_time('F jS, Y'); ?></h2>
			<?php } elseif (is_month()) { ?><h2>Archive for <?php the_time('F, Y'); ?></h2>
			<?php } elseif (is_year()) { ?><h2>Archive for the year <?php the_time('Y'); ?></h2>
			<?php } elseif (is_author()) { ?><h2>Archive by Author</h2>
			<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?><h2>Archives</h2>
			<?php } elseif (is_tag()) { ?><h2>Tag Archives: <?php echo single_tag_title('', true); ?></h2>	

			<?php } ?>

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

			<?php endif; ?>
		
		</div>
	
		<?php get_sidebar(); ?>
	
	</div>

	<?php get_footer(); ?>