		<div id="sidebar">
		
			<div id="rss">
			<a href="<?php bloginfo('rss_url'); ?>"><span>Subscribe</span></a>
			</div>
			
			<div id="sidebar_main">

			<h2>Search</h2>
			
			<div id="search_block">
			<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
			<div>
			<input type="text" name="s" id="s" class="field" value="Searching for ?" onfocus="if (this.value == 'Searching for ?') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Searching for ?';}" />
			<input type="image" src="<?php bloginfo('template_directory'); ?>/img/search.gif" class="submit" name="submit" />
			</div>
			</form>
			</div>

			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
			
			<?php include (TEMPLATEPATH . "/sidebar-text.php"); ?>
		
			<h2>Recent Entries</h2>
			<ul>
			<?php get_archives('postbypost', 5); ?>
			</ul>
			
			<h2>Categories</h2>
			<ul>
			<?php wp_list_categories('orderby=name&show_count=1&hide_empty=0&hierarchical=0&exclude=,2&title_li='); ?>
			</ul>

			<h2>Archives</h2>
			<ul>
			<?php wp_get_archives('type=monthly'); ?>
			</ul>
			
			<?php endif; ?>

			</div>
			
			<div id="sidebar_bottom">
			</div>

		</div>