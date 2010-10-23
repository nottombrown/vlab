<?php include_once('includes.php'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php if (is_home () ) { bloginfo('name'); echo " - "; bloginfo('description'); 
} else { wp_title('',true); echo " - "; bloginfo('name'); }?></title>
<meta name="robots" content="index,follow" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/javascript/jquery-1.1.3.1.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/javascript/jquery.easing.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/javascript/jquery.lavalamp.js"></script>
<script type="text/javascript">
$(function() {
 	$("#mainMenu ul").lavaLamp({
 		fx: "backout",
 		speed: 700,
 		click: function(event, menuItem) {
 	return true;
 	}
	});	var mainWidth = $("#main").width();	var numsidebars = "<?php echo numberOfSidebars(); ?>";	var contentWidth = mainWidth - ( 175 * numsidebars );	$("#contentwrapper").css("width", contentWidth + "px");	$("#main").addClass('<?php echo getLayoutClassName(); ?>');	$('.adsense_top:empty').remove();	$('.sidebar_ads:empty').remove(); 	var contentHeight = 'auto';	var s1h = $('#sidebar1').height();	var s2h = $('#sidebar2').height();	var ch  = $('#content').height();	if(s1h > s2h && s1h > ch) {		contentHeight = s1h;	} else if(s2h > s1h && s2h > ch) {		contentHeight = s2h;	}	$('#content').css('height', contentHeight);
});
</script>

<?php wp_head(); ?>

</head>

<body>
<div id="wrapper">

<div id="header">
<div class="cleared"></div>
<div id="toprss">
	<div>
		<a class="logo"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" alt="SAFI Tech" title="SAFI Tech" border="0" /></a>
	</div>
	<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
	<div>	<input type="text" value="<?php if(get_search_query() == "") { echo 'enter keyword...'; } else {the_search_query(); } ?>" name="s" id="searchbox" onfocus="if(this.value == 'enter keyword...') { this.value='';}" onblur="if(this.value == '') {this.value='enter keyword...';}"/>	<input type="submit" id="searchbutton" value="Seach"/>	</div>
	</form>
	<div class="blogerName">
		<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>	
	</div>
</div></div> <!-- Closes Header -->

<div id="mainMenu" class="lavaLamp">
<ul class="lavaLamp">
<?php
if (is_home()) {
  $addclass = ' class="current"';
  } else {
  $addclass = '';
  }
echo "<li" . $addclass . "><a href='" . get_option('home') . "' title='Home'><span>Home</span></a></li>";
echo listPages();?>
</ul>

<div class="rss">
<img title="Rss feed" alt="Rss feed" src="<?php bloginfo('stylesheet_directory'); ?>/images/rss.png"/> <a href="feed:<?php bloginfo('rss2_url'); ?>">Subscribe</a>
</div>
<div class="cleared"></div>
</div> <!-- Closes Nav -->

<div id="main">