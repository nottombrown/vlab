<?php 
/*
Plugin Name: User Avatar
Plugin URI: http://wordpress.org/extend/plugins/user-avatar/
Description: Allows users to associate photos with their accounts by accessing their "Your Profile" page that default as Gravatar or WordPress Default image (from Discussion Page). 
Version: 1.2.1
Author: Enej Bajgoric / Gagan Sandhu / CTLT DEV


GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_action('init', 'user_avatar_core_set_avatar_constants', 8 );
add_action('show_user_profile', 'user_avatar_form');
add_action('edit_user_profile', 'user_avatar_form');
add_action('wp_ajax_user_avatar_add_photo', 'user_avatar_add_photo');
add_action('user_avatar_iframe_head','user_avatar_init');


if($GLOBALS['pagenow'] == 'profile.php' or $GLOBALS['pagenow'] == 'user-edit.php') { 
	wp_enqueue_script("thickbox");
	wp_enqueue_style("thickbox");
	add_action( 'init', 'user_avatar_init');
}

/**
 * user_avatar_init function.
 * Description: Initializing user avatar style.
 * @access public
 * @return void
 */
function user_avatar_init(){
	wp_enqueue_style('user-avatar', plugins_url('/user-avatar/css/user-avatar.css'), 'css');
}

/**
 * user_avatar_core_set_avatar_constants function.
 * Description: Establishing restraints on sizes of files and dimensions of images.
 * Sets the default constants 
 * @access public
 * @return void
 */
function user_avatar_core_set_avatar_constants() {
	global $bp;

	if ( !defined( 'USER_AVATAR_UPLOAD_PATH' ) )
		define( 'USER_AVATAR_UPLOAD_PATH', user_avatar_core_avatar_upload_path() );

	if ( !defined( 'USER_AVATAR_URL' ) )
		define( 'USER_AVATAR_URL', user_avatar_core_avatar_url() );

	if ( !defined( 'USER_AVATAR_THUMB_WIDTH' ) )
		define( 'USER_AVATAR_THUMB_WIDTH', 50 );

	if ( !defined( 'USER_AVATAR_THUMB_HEIGHT' ) )
		define( 'USER_AVATAR_THUMB_HEIGHT', 50 );

	if ( !defined( 'USER_AVATAR_FULL_WIDTH' ) )
		define( 'USER_AVATAR_FULL_WIDTH', 150 );

	if ( !defined( 'USER_AVATAR_FULL_HEIGHT' ) )
		define( 'USER_AVATAR_FULL_HEIGHT', 150 );

	if ( !defined( 'USER_AVATAR_ORIGINAL_MAX_FILESIZE' ) ) {
		if ( !get_site_option( 'fileupload_maxk', 1500 ) )
			define( 'USER_AVATAR_ORIGINAL_MAX_FILESIZE', 5120000 ); /* 5mb */
		else
			define( 'USER_AVATAR_ORIGINAL_MAX_FILESIZE', get_site_option( 'fileupload_maxk', 1500 ) * 1024 );
	}

	if ( !defined( 'USER_AVATAR_DEFAULT' ) )
		define( 'USER_AVATAR_DEFAULT', plugins_url('/user-avatar/images/mystery-man.jpg') );

	if ( !defined( 'USER_AVATAR_DEFAULT_THUMB' ) )
		define( 'USER_AVATAR_DEFAULT_THUMB', plugins_url('/user-avatar/images/mystery-man-50.jpg') );
}

/**
 * user_avatar_core_avatar_upload_path function.
 * Description: Establishing upload path/area where images that are uploaded will be stored.
 * @access public
 * @return void
 */
function user_avatar_core_avatar_upload_path()
{
	if( !file_exists(WP_CONTENT_DIR."/uploads/avatars/") )
		mkdir(WP_CONTENT_DIR."/uploads/avatars/", 0777 ,true);
	
	return WP_CONTENT_DIR."/uploads/avatars/";
}

/**
 * user_avatar_core_avatar_url function.
 * Description: Establishing the path of the core content avatar area.
 * @access public
 * @return void
 */
function user_avatar_core_avatar_url()
{	
	return WP_CONTENT_URL."/uploads/avatars/";
}

/**
 * user_avatar_add_photo function.
 * The content inside the iframe 
 * Description: Creating panels for the different steps users take to upload a file and checking their uploads.
 * @access public
 * @return void
 */
function user_avatar_add_photo() {
	global $current_user;
	

	if(($_GET['uid'] == $current_user->ID || is_super_admin($current_user->ID)) &&  is_numeric($_GET['uid'])) 
	{
		$uid = $_GET['uid'];

	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e('WordPress'); ?></title>
<?php

	wp_enqueue_style( 'global' );
	wp_enqueue_style( 'wp-admin' );
	wp_enqueue_style( 'colors' );
	wp_enqueue_style( 'ie' );
	
	wp_enqueue_style('imgareaselect');
	wp_enqueue_script('imgareaselect');
	do_action('user_avatar_iframe_head');
	do_action('admin_print_styles');
	do_action('admin_print_scripts');
	do_action('admin_head');
?>
</head>
<body>
<?php
	switch($_GET['step'])
	{
		case 1:
			user_avatar_add_photo_step1($uid);
		break;
		
		case 2:
			user_avatar_add_photo_step2($uid);
		break;
		
		case 3:
			user_avatar_add_photo_step3($uid);
		break;
	}
		
	do_action('admin_print_footer_scripts');
?>
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
</body>
</html>
<?php
	}else {
		wp_die("You are not allowed to do that.");
	}
	die();
}

/**
 * user_avatar_add_photo_step1 function.
 * The First Step in the process 
 * Description: Displays the users photo and they can choose to upload another if they please.
 * @access public
 * @param mixed $uid
 * @return void
 */
function user_avatar_add_photo_step1($uid)
{
	?>
	<p id="step1-image" >
	<?php
	echo get_avatar( $uid , 150);
	?>
	</p>
	<div id="user-avatar-step1">
	<form enctype="multipart/form-data" id="uploadForm" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=user_avatar_add_photo&step=2&uid=<?php echo $uid; ?>" >
			<label for="upload"><?php _e('Choose an image from your computer:'); ?></label><br /><input type="file" id="upload" name="uploadedfile" />
			<input type="hidden" name="action" value="save" />
			<?php wp_nonce_field('user-avatar') ?>
		<p class="submit"><input type="submit" value="<?php esc_attr_e('Upload'); ?>" /></p>
	</form>
	</div>
	
	<?php
}

/**
 * user_avatar_add_photo_step2 function.
 * The Second Step in the process 
 * Description: Takes the uploaded photo and saves it to database.
 * @access public
 * @param mixed $uid
 * @return void
 */
function user_avatar_add_photo_step2($uid)
{
	check_admin_referer('user-avatar'); 
	
		if (!(($_FILES["uploadedfile"]["type"] == "image/gif") || ($_FILES["uploadedfile"]["type"] == "image/jpeg") || ($_FILES["uploadedfile"]["type"] == "image/png") || ($_FILES["uploadedfile"]["type"] == "image/pjpeg") || ($_FILES["uploadedfile"]["type"] == "image/x-png"))){
			echo "<div class='error'><p>Please upload an image file (.jpeg, .gif, .png).</p></div>";
			user_avatar_add_photo_step1($uid);
			die();
		}
		$overrides = array('test_form' => false);
		$file = wp_handle_upload($_FILES['uploadedfile'], $overrides);

		if ( isset($file['error']) ){
			die( $file['error'] );
		}
		
		$url = $file['url'];
		$type = $file['type'];
		$file = $file['file'];
		$filename = basename($file);
		
		// Construct the object array
		$object = array(
		'post_title' => $filename,
		'post_content' => $url,
		'post_mime_type' => $type,
		'guid' => $url);

		// Save the data
		list($width, $height, $type, $attr) = getimagesize( $file );
		
		if ( $width > 500 ) {
			$oitar = $width / 500;
			$image = wp_crop_image($file, 0, 0, $width, $height, 500, $height / $oitar, false, str_replace(basename($file), 'midsize-'.basename($file), $file));
			

			$url = str_replace(basename($url), basename($image), $url);
			$width = $width / $oitar;
			$height = $height / $oitar;
		} else {
			$oitar = 1;
		}
		
		
		?>
		<form id="iframe-crop-form" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=user_avatar_add_photo&step=3&uid=<?php echo $uid; ?>">
		
		<h4><?php _e('Choose the part of the image you want to use as your profile image.'); ?></h4>
		
		<div id="testWrap">
		<img src="<?php echo $url; ?>" id="upload" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
		</div>
		<div id="user-avatar-preview">
		<h4>Preview</h4>
		<div id="preview" style="width: <?php echo USER_AVATAR_FULL_WIDTH; ?>px; height: <?php echo USER_AVATAR_FULL_HEIGHT; ?>px; overflow: hidden;">
		<img src="<?php echo $url; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
		</div>
		<p class="submit" >
		<input type="hidden" name="x1" id="x1" value="0" />
		<input type="hidden" name="y1" id="y1" value="0" />
		<input type="hidden" name="x2" id="x2" />
		<input type="hidden" name="y2" id="y2" />
		<input type="hidden" name="width" id="width" value="<?php echo $width ?>" />
		<input type="hidden" name="height" id="height" value="<?php echo $height ?>" />
		<input type="hidden" name="attachment_file" id="attachment_file" value="<?php echo esc_url($file); ?>" />
		<input type="hidden" name="oitar" id="oitar" value="<?php echo esc_attr($oitar); ?>" />
		<?php wp_nonce_field('user-avatar') ?>
		<input type="submit" id="user-avatar-crop-button" value="<?php esc_attr_e('Crop Image'); ?>" /></p>
		</div>
		</form>
		
		<script type="text/javascript">

	function onEndCrop( coords ) {
		jQuery( '#x1' ).val(coords.x);
		jQuery( '#y1' ).val(coords.y);
		jQuery( '#width' ).val(coords.w);
		jQuery( '#height' ).val(coords.h);
	}

	jQuery(document).ready(function() {
		var xinit = <?php echo USER_AVATAR_FULL_WIDTH; ?>;
		var yinit = <?php echo USER_AVATAR_FULL_HEIGHT; ?>;
		var ratio = xinit / yinit;
		var ximg = jQuery('img#upload').width();
		var yimg = jQuery('img#upload').height();

		if ( yimg < yinit || ximg < xinit ) {
			if ( ximg / yimg > ratio ) {
				yinit = yimg;
				xinit = yinit * ratio;
			} else {
				xinit = ximg;
				yinit = xinit / ratio;
			}
		}

		jQuery('img#upload').imgAreaSelect({
			handles: true,
			keys: true,
			aspectRatio: xinit + ':' + yinit,
			show: true,
			x1: 0,
			y1: 0,
			x2: xinit,
			y2: yinit,
			//maxHeight: <?php echo USER_AVATAR_FULL_HEIGHT; ?>,
			//maxWidth: <?php echo USER_AVATAR_FULL_WIDTH; ?>,
			onInit: function () {
				jQuery('#width').val(xinit);
				jQuery('#height').val(yinit);
			},
			onSelectChange: function(img, c) {
				jQuery('#x1').val(c.x1);
				jQuery('#y1').val(c.y1);
				jQuery('#width').val(c.width);
				jQuery('#height').val(c.height);
				
				
				
				if (!c.width || !c.height)
        			return;
    
			    var scaleX = <?php echo USER_AVATAR_FULL_WIDTH; ?> / c.width;
			    var scaleY = <?php echo USER_AVATAR_FULL_HEIGHT; ?> / c.height;
				
			    jQuery('#preview img').css({
			        width: Math.round(scaleX * <?php echo $width; ?>),
			        height: Math.round(scaleY * <?php echo $height; ?>),
			        marginLeft: -Math.round(scaleX * c.x1),
			        marginTop: -Math.round(scaleY * c.y1)
			    });

			}
		});
	});
</script>
		<?php
}
/**
 * user_avatar_add_photo_step3 function.
 * The Third Step in the Process
 * Description: Deletes previous uploaded picture and creates a new cropped image in its place. 
 * @access public
 * @param mixed $uid
 * @return void
 */
function user_avatar_add_photo_step3($uid)
{
	check_admin_referer('user-avatar');
	
	if ( $_POST['oitar'] > 1 ) {
		$_POST['x1'] = $_POST['x1'] * $_POST['oitar'];
		$_POST['y1'] = $_POST['y1'] * $_POST['oitar'];
		$_POST['width'] = $_POST['width'] * $_POST['oitar'];
		$_POST['height'] = $_POST['height'] * $_POST['oitar'];
	}
		
	if( !file_exists($_POST['attachment_file']) ) {
		echo "<div class='error'><p>Sorry, No file available</p></div>";
		return true;
	}
	
	$original_file = $_POST['attachment_file'];
		
	$cropped_full = USER_AVATAR_UPLOAD_PATH."{$uid}/".time()."-bpfull.jpg";
	$cropped_thumb = USER_AVATAR_UPLOAD_PATH."{$uid}/".time()."-bpthumb.jpg";
	
	// delete the previous files
	user_avatar_delete_files($uid);
	
	if(!file_exists(USER_AVATAR_UPLOAD_PATH."{$uid}/"))
		mkdir(USER_AVATAR_UPLOAD_PATH."{$uid}/");
	
	// update the files 
	$cropped_full = wp_crop_image( $original_file, $_POST['x1'], $_POST['y1'], $_POST['width'], $_POST['height'], USER_AVATAR_FULL_WIDTH, USER_AVATAR_FULL_HEIGHT, false, $cropped_full );
	
	$cropped_thumb = wp_crop_image( $original_file, $_POST['x1'], $_POST['y1'], $_POST['width'], $_POST['height'], USER_AVATAR_THUMB_WIDTH, USER_AVATAR_THUMB_HEIGHT, false, $cropped_thumb );
	
	/* Remove the original */
	@unlink( $original_file );
		
	if ( is_wp_error( $cropped_full ) )
		wp_die( __( 'Image could not be processed.  Please go back and try again.' ), __( 'Image Processing Error' ) );		
	?>
	
	<script type="text/javascript">
		self.parent.user_avatar_refresh_image('<?php echo get_avatar($uid, 150); ?>');
	</script>
	<div id="user-avatar-step3">
		<h3>Here's your new profile picture...</h3>
		<span style="float:left;">
		<?php
		echo get_avatar( $uid, 150);
		?>
		</span>
		<a id="user-avatar-step3-close" class="button" onclick="self.parent.tb_remove();" >Close</a>
	</div>
<?php	
}	
/**
 * user_avatar_delete_files function.
 * Description: Deletes the avatar files based on the user id.
 * @access public
 * @param mixed $uid
 * @return void
 */
function user_avatar_delete_files($uid)
{
	$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$uid}/";
	if ( !file_exists( $avatar_folder_dir ) )
		return false;

	if ( $av_dir = opendir( $avatar_folder_dir ) ) {
		while ( false !== ( $avatar_file = readdir($av_dir) ) ) {
			if ( ( preg_match( "/-bpfull/", $avatar_file ) || preg_match( "/-bpthumb/", $avatar_file ) ) && '.' != $avatar_file && '..' != $avatar_file )
				@unlink( $avatar_folder_dir . '/' . $avatar_file );
		}
	}
	closedir($av_dir);

	@rmdir( $avatar_folder_dir );

}

/**
 * Based on the 
 * user_avatar_core_fetch_avatar_filter() 1.2.5 BP
 *
 * Description: Attempts to filter get_avatar function and let Word/BuddyPress have a go at  
 * 				finding an avatar that may have been uploaded locally.
 *
 * @global array $authordata
 * @param string $avatar The result of get_avatar from before-filter
 * @param int|string|object $user A user ID, email address, or comment object
 * @param int $size Size of the avatar image (thumb/full)
 * @param string $default URL to a default image to use if no avatar is available
 * @param string $alt Alternate text to use in image tag. Defaults to blank
 * @return <type>
 */
function user_avatar_fetch_avatar_filter( $avatar, $user, $size, $default, $alt ) {
	global $pagenow;
	
	//If user is on discussion page, return $avatar 
    if($pagenow == "options-discussion.php")
    	return $avatar;
    	
	// If passed an object, assume $user->user_id
	if ( is_object( $user ) )
		$id = $user->user_id;

	// If passed a number, assume it was a $user_id
	else if ( is_numeric( $user ) )
		$id = $user;

	// If passed a string and that string returns a user, get the $id
	else if ( is_string( $user ) && ( $user_by_email = get_user_by_email( $user ) ) )
		$id = $user_by_email->ID;

	// If somehow $id hasn't been assigned, return the result of get_avatar
	if ( empty( $id ) )
		return !empty( $avatar ) ? $avatar : $default;

	// Let us handle the fetching of the avatar
	$user_avatar = user_avatar_fetch_avatar( array( 'item_id' => $id, 'width' => $size, 'height' => $size, 'alt' => $alt ) );

	// If we found an avatar, use it. If not, use the result of get_avatar
	$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$id}/";
	if ( file_exists( $avatar_folder_dir ) )
		return $user_avatar;
	// Otherwise, load the gravatar if it exists, or the selected default image from the Discussions page if not.	
	else if(!file_exists( $avatar_folder_dir )){
		return $avatar;
	}
}

add_filter( 'get_avatar', 'user_avatar_fetch_avatar_filter', 10, 5 );

/**
 * user_avatar_core_fetch_avatar()
 *
 * Description: Fetches an avatar from a BuddyPress object. Supports user/group/blog as
 * 				default, but can be extended to include your own custom components too.
 *
 * @global object $bp
 * @global object $current_blog
 * @param array $args Determine the output of this function
 * @return string Formatted HTML <img> element, or raw avatar URL based on $html arg
 */
function user_avatar_fetch_avatar( $args = '' ) {
	//var_dump($args);
	$defaults = array(
		'item_id'		=> false,
		'object'		=> $def_object,	// user/group/blog/custom type (if you use filters)
		'type'			=> $def_type,	// thumb or full
		'avatar_dir'	=> false,		// Specify a custom avatar directory for your object
		'width'			=> false,		// Custom width (int)
		'height'		=> false,		// Custom height (int)
		'class'			=> $def_class,	// Custom <img> class (string)
		'css_id'		=> false,		// Custom <img> ID (string)
		'alt'			=> $def_alt,	// Custom <img> alt (string)
		'email'			=> false,		// Pass the user email (for gravatar) to prevent querying the DB for it
		'no_grav'		=> false,		// If there is no avatar found, return false instead of a grav?
		'html'			=> true			// Wrap the return img URL in <img />
	);
	
	// Compare defaults to passed and extract
	$params = wp_parse_args( $args, $defaults );
	extract( $params, EXTR_SKIP );

	$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$item_id}/";
	$avatar_folder_url = USER_AVATAR_URL."{$item_id}";
	if($width > 50)
	$type = "full";
	$avatar_size = ( 'full' == $type ) ? '-bpfull' : '-bpthumb';
	
	
	// Add an identifying class to each item
	$class .= ' ' . $object . '-' . $item_id . '-avatar';

	// Set CSS ID if passed
	if ( !empty( $css_id ) )
		$css_id = " id=\"{$css_id}\"";
	
	// Set avatar width
	if ( $width )
		$html_width = " width=\"{$width}\"";
	else
		$html_width = ( 'thumb' == $type ) ? ' width="' . USER_AVATAR_THUMB_WIDTH . '"' : ' width="' . USER_AVATAR_FULL_WIDTH . '"';

	// Set avatar height
	if ( $height )
		$html_height = " height=\"{$height}\"";
	else
		$html_height = ( 'thumb' == $type ) ? ' height="' . USER_AVATAR_THUMB_HEIGHT . '"' : ' height="' . USER_AVATAR_FULL_HEIGHT . '"';
	
	
	// Check for directory
	if ( file_exists( $avatar_folder_dir ) ) {
		
		// Open directory
		if ( $av_dir = opendir( $avatar_folder_dir ) ) {
			
			// Stash files in an array once to check for one that matches
			$avatar_files = array();
			while ( false !== ( $avatar_file = readdir($av_dir) ) ) {
				// Only add files to the array (skip directories)
				if ( 2 < strlen( $avatar_file ) )
					$avatar_files[] = $avatar_file;
			}
			
			// Check for array
			if ( 0 < count( $avatar_files ) ) {

				// Check for current avatar
				if( is_array($avatar_files) ):
					foreach( $avatar_files as $key => $value ) {
						if ( strpos ( $value, $avatar_size )!== false )
							$avatar_url = $avatar_folder_url . '/' . $avatar_files[$key];
					}
				endif;
				
			}
		}

		// Close the avatar directory
		closedir( $av_dir );
		
		// If we found a locally uploaded avatar
		if ( $avatar_url ) {

			// Return it wrapped in an <img> element
			if ( true === $html ) {
				return '<img src="' . $avatar_url . '" alt="' . $alt . '" class="' . $class . '"' . $css_id . $html_width . $html_height . ' />';
			// ...or only the URL
			} else {
				return  $avatar_url ;
			}
		}
	}

	// Skips gravatar check if $no_grav is passed
	if ( !$no_grav ) {

		// Set gravatar size
		if ( $width )
			$grav_size = $width;
		else if ( 'full' == $type )
			$grav_size = USER_AVATAR_FULL_WIDTH;
		else if ( 'thumb' == $type )
			$grav_size = USER_AVATAR_THUMB_WIDTH;

		// Set gravatar type
		if ( empty( $bp->grav_default->{$object} ) )
			$default_grav = 'wavatar';
		else if ( 'mystery' == $bp->grav_default->{$object} )
			$default_grav = apply_filters( 'bp_core_mysteryman_src', BP_AVATAR_DEFAULT, $grav_size );
		else
			$default_grav = $bp->grav_default->{$object};

		// Set gravatar object
		if ( empty( $email ) ) {
			if ( 'user' == $object ) {
				$email = bp_core_get_user_email( $item_id );
			} else if ( 'group' == $object || 'blog' == $object ) {
				$email = "{$item_id}-{$object}@{$bp->root_domain}";
			}
		}

		// Set host based on if using ssl
		if ( is_ssl() )
			$host = 'https://secure.gravatar.com/avatar/';
		else
			$host = 'http://www.gravatar.com/avatar/';

		// Filter gravatar vars
		$email		= apply_filters( 'bp_core_gravatar_email', $email, $item_id, $object );
		$gravatar	= apply_filters( 'bp_gravatar_url', $host ) . md5( strtolower( $email ) ) . '?d=' . $default_grav . '&amp;s=' . $grav_size;

		// Return gravatar wrapped in <img />
		if ( true === $html )
			return apply_filters( 'bp_core_fetch_avatar', '<img src="' . $gravatar . '" alt="' . $alt . '" class="' . $class . '"' . $css_id . $html_width . $html_height . ' />', $params, $item_id, $avatar_dir, $css_id, $html_width, $html_height, $avatar_folder_url, $avatar_folder_dir );

		// ...or only return the gravatar URL
		else
			return  $gravatar;

	} else {
		return apply_filters( 'bp_core_fetch_avatar', false, $params, $item_id, $avatar_dir, $css_id, $html_width, $html_height, $avatar_folder_url, $avatar_folder_dir );
	}
}

/**
 * user_avatar_form function.
 * Description: Creation and calling of appropriate functions on the overlay form. 
 * @access public
 * @param mixed $profile
 * @return void
 */
function user_avatar_form($profile)
{
	global $current_user;
	
	// Check if it is current user or super admin role
	if(($profile->ID == $current_user->ID || is_super_admin($current_user->ID))) 
	{
		$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$profile->ID}/";
		
		// Remove the User-Avatar button if there is no uploaded image
		if ( !file_exists( $avatar_folder_dir ) ){
			?> <style type="text/css">#user-avatar-remove{display:none;}</style>
			<?php			
		}
		
		// If user clicks the remove avatar button, in URL deleter_avatar=true
		if(isset($_GET['delete_avatar']))
		{
			$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$profile->ID}/";
			
			// Redirect to profile.php if user does not have uploaded images
			if ( !file_exists( $avatar_folder_dir ) ){
				wp_redirect(get_option('siteurl') . '/wp-admin/profile.php');			
			}
			// If user has an uploaded User Avatar, delete the directory is associated with their uploaded avatars
			else{
				if ( $av_dir = opendir( $avatar_folder_dir ) ) {
					while ( false !== ( $avatar_file = readdir($av_dir) ) ) {
						if ( ( preg_match( "/-bpfull/", $avatar_file ) || preg_match( "/-bpthumb/", $avatar_file ) ) && '.' != $avatar_file && '..' != $avatar_file )
							@unlink( $avatar_folder_dir . '/' . $avatar_file );
					}
				}
				closedir($av_dir);
			
				@rmdir( $avatar_folder_dir );
			}
		}

	?>
	<div id="user-avatar-display" >
	<h3>Picture</h3>
	<p id="user-avatar-display-image"><?php echo get_avatar($profile->ID, 150); ?></p>
	<a id="user-avatar-link" class="button thickbox" href="<?php echo admin_url('admin-ajax.php'); ?>?action=user_avatar_add_photo&step=1&uid=<?php echo $profile->ID; ?>&TB_iframe=true&width=720&height=450" title="Upload and Crop an Image to be Displayed" >Change Picture</a>
	<a id="user-avatar-remove" class="button" href="?delete_avatar=true" title="Remove User Avatar Image" >Remove User Avatar</a>
	</div>
	<script>
	function user_avatar_refresh_image(img){
	 jQuery('#user-avatar-display-image').html(img);
	}
	</script>
	<?php
	}
} 

/* --- END OF FILE --- */
?>