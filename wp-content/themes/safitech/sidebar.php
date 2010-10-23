<?php 
	for($i=1; $i<=2; $i++): 
		$position = get_option('sidebar' . $i . '_position');
		$visibility = get_option('sidebar' . $i . '_visibility');	
		if($visibility == "hidden") continue;
?>
<div class="sidebar sidebar_<?php echo $position; ?>" id="sidebar<?php echo $i; ?>">
<ul>

<?php if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_' . $i))): ?>
		
<?php endif; ?>

<li>
<div class="sidebar_ads">
<?php include (TEMPLATEPATH . '/adsense_sidebar160.php'); ?>
</div>
</li>

</ul>

</div> 
<?php endfor; ?>
