<?php

function numberOfSidebars()
{
	$num = 0;
	for($i=1; $i<=2; $i++) {
		if(get_option('sidebar' . $i . '_visibility') == "show") {
			$num +=1;
		}	
	}	
return $num;
}
function getLayoutClassName()
{
	$class = 'layout';
	for($i=1; $i<=2; $i++) {
		$position = get_option('sidebar' . $i . '_position');
		if(get_option('sidebar' . $i . '_visibility') == "show") {
			$class .= '_sidebar' . $i . $position;
		}	
	}
	
return $class;
}

function getPages() 
{
  global $wpdb;
  if ( ! $these_pages = wp_cache_get('these_pages', 'pages') ) {
     $these_pages = $wpdb->get_results('select ID, post_title from '. $wpdb->posts .' where post_status = "publish" and post_type = "page" order by ID');

   }
  return $these_pages;
}

function listPages()
{
	$all_pages = getPages();
	foreach ($all_pages as $thats_all){
		$the_page_id = $thats_all->ID;
	
		if (is_page($the_page_id)) {
	  		$addclass = ' class="current"';
	  	} else {
	  		$addclass = '';
	  	}
		$output .= '<li' . $addclass . '><a href="'.get_permalink($thats_all->ID).'" title="'.$thats_all->post_title.'"><span>'.$thats_all->post_title.'</span></a></li>';
	}
return $output;
}

?>