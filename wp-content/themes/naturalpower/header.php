<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
<meta name="description" content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" />
<meta name="keywords" content="" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<title><?php bloginfo('name'); wp_title(); ?></title>

<!--[if gt IE 5]>
<style>
#header #text_in {position: absolute; top: 50%;}
</style>
<![endif]-->

<?php
global $options;
foreach ($options as $value) {
if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); } }
?>

<?php wp_head(); ?>
</head>

<body>

<div id="wrap">

	<div id="top">
	
		<div id="menu">
		<ul>
		<?php if (is_page()) { $highlight = "page_item"; } else {$highlight = "page_item current_page_item"; } ?>
		<li class="<?php echo $highlight; ?>"><a href="<?php bloginfo('url'); ?>"><span>Home</span></a></li>
		<?php
		$pages = wp_list_pages('sort_column=menu_order&depth=1&title_li=&echo=0');
		$pages = preg_replace('%<a ([^>]+)>%U','<a $1><span>', $pages);
		$pages = str_replace('</a>','</span></a>', $pages);
		echo $pages;
		?>
		</ul>
		</div>
	
		<div id="title">
		<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
		<p><?php bloginfo('description'); ?></p>
		</div>
	
	</div>

	<div id="header">
	
		<div id="text">
		<div id="text_in">
		<div id="inside">
		<p><?php echo $C9_header_text; ?></p>
		</div>
		</div>
		</div>

	</div>