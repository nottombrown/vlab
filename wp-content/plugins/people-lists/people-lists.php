<?php
/** 
 * Plugin Name: People Lists
 * Plugin URI: http://www.ctlt.ubc.ca 
 * Description: Plugin providing a rich text editor on the profile page for easy modifications of specific user profile    
 *				information that can be displayed on any page using the [people-lists list=example-list] shortcode. Users 
 *				will also be able to add custom fields to their user profile and these fields can be displayed on any page 
 * 				using the People Lists template (which can be styled using HTML) that provides codes for every field that is 
 *				desired to be displayed.     
 * Author: Gagan Sandhu / Enej Bajgoric / CTLT DEV
 * Version: 1.2
 * Author URI: http://www.ctlt.ubc.ca 
 *  
 * GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */ 

if($GLOBALS['pagenow'] == 'profile.php' or $GLOBALS['pagenow'] == 'user-edit.php') { 
	add_action('init', 'people_lists_tinymce_init');	
	wp_enqueue_script('tiny_mce');
	add_action('admin_print_footer_scripts', 'wp_tiny_mce', 25 );
}

if($GLOBALS['pagenow'] == 'options-general.php' && $_GET['page'] == 'people_lists') {
	add_action('admin_init', 'people_list_options_init' );
}
add_action('admin_init', 'people_list_register_options_init');
add_action('admin_menu', 'people_list_options_add_page');
add_action('wp_ajax_people_list_save', 'people_list_saving_list');
add_action('wp_ajax_people_settings_save', 'people_list_saving_settings');
add_action('media_buttons_context', 'people_lists_overlay_button');
add_action('admin_footer', 'people_lists_overlay_popup_form');

add_shortcode('people-lists', 'people_lists_shortcode');

add_filter('widget_text', 'do_shortcode');
add_filter('user_contactmethods','people_lists_user_fields_filter');

/**
 * people_lists_user_fields_filter function.
 * Description: Saves the list of fields added by user to Wordpress default fields in array user_contactmethods for 	
 *				displaying in the Your Profile section.
 * @access public
 * @param mixed $user_fields
 * @return void
 */
function people_lists_user_fields_filter($user_fields)
{
	$option_name = 'people-lists'; 
	$people_list_option = get_option($option_name);
	
	if( is_array($people_list_option['settings']) ):
		foreach ($people_list_option['settings'] as $index => $list){
			 $user_field_added[$index] = stripslashes($list);
		}
	endif;
	
	if(empty($people_list_option['settings'])){
		return $user_fields;
	} 	
	else{
		return array_merge($user_field_added,$user_fields);
	}
}

/**
 * people_list_saving_settings function.
 * Description: Storing the new fields information in the database and saving sorting of that list.
 * @access public
 * @return void
 */
function people_list_saving_settings() {
	$option_name = 'people-lists'; 
	$people_list = get_option($option_name);
	
	wp_parse_str( urldecode($_POST['field_info']), $field_info );
	
	$final_field_array = array();
	$count = 0;
	
	if( is_array($field_info['field_slug_list']) ):	
		foreach($field_info['field_slug_list'] as $slug)
		{
			$final_field_array[$slug] = $field_info['field_name_list'][$count];
			$count++;
		}
	endif;
	
	$people_list['settings'] = $final_field_array;
	
    update_option( $option_name, $people_list);
}

/**
 * people_list_saving_list function.
 * Description: Storing list information in the database for new and updated lists to track any sorting of the lists.
 * @access public
 * @return void
 */
function people_list_saving_list() {
	$option_name = 'people-lists'; 
	$people_list['lists'] = array();
	$people_list = get_option($option_name);
		
	wp_parse_str( $_POST['list'], $list );
	$title = $_POST['title'];
	$template = trim($_POST['template']); 

	if( is_numeric( $_POST['id'] ) ||  is_array($people_list['lists'][$_POST['id']] )):
		// Updating the list
		$slug = $people_list['lists'][$_POST['id']]['slug'];
		$people_list['lists'][$_POST['id']] = array('list'=>$list,'title'=>$title,'slug' => $slug,'template'=> $template);
		echo "update";
	else:
		$slug = people_lists_slug( $title );
		// Check if the slug exists 
		$counter = 1;
		while(people_lists_slug_exists($slug,$people_list['lists']))
		{
			$slug = people_lists_slug($title)."-".$counter;
			$counter += 1;
		}
		
		// Saving for the first time
		$people_list['lists'][] = array('list'=>$list,'title'=>$title, 'slug' => $slug , 'template'=> $template);
		echo "new";
	endif;
	
    update_option( $option_name, $people_list);
    
 	die();
}


/**
 * people_lists_slug function.
 * Description: Creating a slug (shortcode list name) using the list name provided by the user.
 * @access public
 * @param mixed $str
 * @return void
 */
function people_lists_slug($str)
{
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9-]/', '-', $str);
	$str = preg_replace('/-+/', "-", $str);
	return $str;
}

/**
 * people_lists_slug_exists function.
 * Description: Check if a slug (shortcode list name) exists. 
 * @access public
 * @param mixed $slug
 * @param mixed $people_list['lists']
 * @return void
 */
function people_lists_slug_exists($slug,$people_list)
{	
	if( is_array($people_list) ):
		foreach($people_list as $list):
			if($list['slug'] == $slug)
				return $list;		
		endforeach;
	endif;
	return false;
}

/**
 * people_lists_field_slug_exists function.
 * Description: Check if a field slug with that name exists. 
 * @access public
 * @param mixed $slug
 * @param mixed $people_list['lists']
 * @return void
 */
function people_lists_field_slug_exists($field_slug,$people_list)
{
	if( is_array($people_list) ):
		foreach($people_list as $fields):
			if($fields['field_slug'] == $field_slug)
				return $fields;	
		endforeach;
	endif;	
	
	return false;
}

/**
 * people_lists_tinymce_init function.
 * Description: Calling the appropriate JS and CSS files to initialize tinyMCE on profile page.
 * @access public
 * @return void
 */
function people_lists_tinymce_init(){
	wp_enqueue_style('people-lists-tinymce', plugins_url('/people-lists/css/people-lists-tinymce.css'),'css');
	wp_enqueue_script('people-lists-tinymce', plugins_url('/people-lists/js/people-lists-tinymce.js'),'jquery');
}

/**
 * people_list_register_options_init function.
 * Description: Registering People Lists Settings Page
 * @access public
 * @return void
 */
function people_list_register_options_init(){
	register_setting( 'people_lists_options', 'people_lists', 'people_list_validate_admin_page' );
}

/**
 * people_list_options_init function.
 * Description: Calling the appropriate JS and CSS files to initialize functionality on the People Lists Settings page.
 * @access public
 * @return void
 */
function people_list_options_init(){	
	wp_enqueue_script('people-lists-jquery-sortable', plugins_url('/people-lists/js/jquery-ui.min.js'), array('jquery','jquery-ui-tabs','jquery-ui-sortable'));
	wp_enqueue_script('people-lists', plugins_url('/people-lists/js/people-lists.js'), array('jquery','jquery-ui-tabs','jquery-ui-sortable'));
	wp_enqueue_style('people-lists-style',  plugins_url('/people-lists/css/people-lists.css'),'css');
}

/**
 * people_list_options_add_page function.
 * Description: Initialize People Lists Option page.
 * @access public
 * @return void
 */
function people_list_options_add_page() {
	$page = add_options_page('People Lists', 'People Lists', 'manage_options', 'people_lists', 'people_list_admin_page');
	 add_action('admin_print_styles-' . $page,'people_lists_admin_styles');
}

/**
 * people_lists_admin_styles function.
 * Description: JQuery Sortable initialization call.
 * @access public
 * @return void
 */
function people_lists_admin_styles() {
	 wp_enqueue_script('people-lists-jquery-sortable');
	 wp_enqueue_script('people-lists');
	 wp_enqueue_style( 'people-lists-style');
	 wp_enqueue_script('jquery-ui-tabs');
}


/**
 * people_lists_overlay_button function.
 * Description: For adding the People Lists "Insert List" button to the pages & posts editing screens.
 * @access public
 * @param mixed $context
 * @return void
 */
function people_lists_overlay_button($context){
    $people_lists_overlay_image_button = plugins_url('/people-lists/img/form-button.png');
    $output_link = '<a href="#TB_inline?width=450&inlineId=people_lists_select_list_form" class="thickbox" title="' . __("Add People List", 'people-lists') . '"><img src="'.$people_lists_overlay_image_button.'" alt="' . __("Add People List", 'people-lists') . '" /></a>';
    return $context.$output_link;
}

/**
 * people_lists_overlay_popup_form function.
 * Description: For displaying the overlay to insert a People List to the pages & posts editing screens.
 * @access public
 * @return void
 */
function people_lists_overlay_popup_form(){
	$option_name = 'people-lists'; 
	$people_list_option = get_option($option_name);
    ?>
    <script>
        function people_lists_insert_overlay_form(){
		   	var people_lists_user_selection_list_value = jQuery("#people_lists_dropdown_selection option:selected").attr('value');
			if (people_lists_user_selection_list_value == "dropdown-first-option"){
				alert("Please select a list.");
				return;
			}
            var win = window.dialogArguments || opener || parent || top;
            win.send_to_editor("[people-lists list=" + people_lists_user_selection_list_value + "]");
        }
    </script>

    <div id="people_lists_select_list_form" style="display:none;">
        <div class="people_lists_select_list_form_wrap">
        	<?php if(empty($people_list_option['lists'] )) : ?>
				<div id="message" class="updated below-h2 clear"><p>You currently have no lists. Go ahead and create one! Click <a href="/wp-admin/options-general.php?page=people_lists">here</a>.</p></div>
            <?php else: ?>
            <div style="padding:15px 15px 0 15px;">
                <h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;"><?php _e("Insert A People List"); ?></h3>
                <span>
                    <?php _e("Select a list from the dropdown below to add it to your post or page."); ?>
                </span>
            </div>
            <div style="padding:15px 15px 0 15px;">
		        <select id="people_lists_dropdown_selection">
		            <option value="dropdown-first-option">  <?php _e("Select a Form"); ?>  </option>
		            <?php if( is_array($people_list_option['lists']) ):
		            	 foreach ($people_list_option['lists'] as $index =>$list_name): ?>
		                    <option value="<?php echo $list_name['slug']; ?>"><?php echo esc_html($list_name['title']); ?></option>
		            <?php endforeach; 
		            endif; ?>
		        </select> <br/>

            </div>
            <div style="padding:15px;">
                <input type="button" class="button-primary" value="Insert People List" onclick="people_lists_insert_overlay_form();"/>&nbsp;&nbsp;&nbsp;
           		<a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel"); ?></a>
            </div>
            
            <?php endif; ?>
            
        </div>
    </div>
    <?php
}

/**
 * people_list_admin_page function.
 * Description: HTML layout creation of the admin page and building/calling different panels (create/edit/manage).
 * @access public
 * @return void
 */
function people_list_admin_page() {
	
	$people_list_option = get_option('people-lists');
	
	if( is_numeric($_GET['delete']) ):
		unset($people_list_option['lists'][$_GET['delete']]);
		update_option( 'people-lists', $people_list_option);
	endif;
	
	if($_GET['delete-all'])
		delete_option('people-lists');
	?>
	<div class="wrap" id="people-list-page">
		<h2 id="people-list-header">People Lists</h2>
		<?php if($_GET['panel']=="create" || empty($people_list_option['lists']) || !isset($_GET['panel']) ):
		
		else: ?>
			<a href="options-general.php?page=people_lists&panel=create" class="button">Add New People List</a>
				
		<?php endif;
		
		if(empty($people_list_option['lists'] )) : ?>
			<div id="message" class="updated below-h2 clear"><p>You currently have no lists. Go ahead and create one!</p></div>
			
		<?php else: ?>
			<ul id="people-list-manage">
				<li><a href="options-general.php?page=people_lists&panel=manage">View All Lists</a>
				<ul>
				<?php if( is_array($people_list_option['lists']) ):
					foreach($people_list_option['lists'] as $index =>$list): ?>
					<li>
						<a href="options-general.php?page=people_lists&panel=edit&list_id=<?php echo $index;?>"><?php echo $list['title']; ?></a>
					</li>
				<?php endforeach; 
				endif; ?>
				</ul>
				</li>
			</ul>
		
			<ul id="people-list-settings">
				<li>
					<a href="options-general.php?page=people_lists&panel=settings" id="people-lists-settings-link">Profile Settings</a>
				</li>
			</ul>

		<?php endif; 
		
		if( empty($people_list_option['lists']) ):
			require_once('views/create.php');
		else:
			switch($_GET['panel']) {
				case "create":
					require_once('views/create.php');
				break;
				
				case "edit":
					if( is_numeric( $_GET['list_id'] ) && $people_list_option['lists'][$_GET['list_id']] ):
						$list_id = $_GET['list_id'];
						$list = $people_list_option['lists'][$_GET['list_id']];
						require_once('views/edit.php');
					else:
						require_once('views/create.php');
					endif;			
				break;
				
				case "manage":
					require_once('views/manage.php');
				break;
		
				case "settings":
					require_once('views/settings.php');
				break;
				
				default:
					require_once('views/create.php');
				break;
			}
		endif;
		?>	
	</div>
<?php	
}

/**
 * people_list_validate_admin_page function.
 * Description: Sanitizes and validates an inputted array.
 * @access public
 * @param mixed $input
 * @return void
 */
function people_list_validate_admin_page($input) {
	return $input;
}

/**
 * people_list_form function.
 * Description: Building of form users interact with, including a name field, a modifiable people lists profile display 	                                
 *              template and jQuery Sortable lists that allow for dragging and dropping of users for a specific list. 
 * @access public
 * @param bool $list_id. (default: false)
 * @param bool $list. (default: false)
 * @return void
 */
function people_list_form($list_id=false,$list=false)
{	
	$users_of_blog = get_users_of_blog();
	$option_name = 'people-lists'; 
	$people_list_option = get_option($option_name);
	
	if(empty($list['list']['uid']))
		$list['list']['uid'] = array();
	
	?>
	<div id="people-list-form-shell">
		<form method="post" id="people-list-form" action="options.php">
		<label for="list-title">Name</label><br />
		<input type="text" value="<?php echo $list['title'];?>" name="list-title" id="list-title" size="50" />
		<p>The name helps identify which list you are editing.</p>
	
		<a id="template-link" href="#">Template Info</a>
		<div class="template-info" id="template-info" >
			<div class="template-tabbed">
				<div class="template-area">
					<ul class="template-tabs">
						<li><a href="#default_codes" title="Default Codes" class="tabbed">Default Codes</a></li>
						<li><a href="#added_fields" title="Added Fields" class="tabbed" >Added Fields Codes</a></li>
					</ul>
					<div id="default_codes" class="template-content">
				  		<strong>Default codes you can use are: </strong>
				  		<ul>		  		
					  		<li>%nickname% - To Display Nickname </li>
							<li>%email%    - To Display Email </li>
							<li>%bio%      - To Display User Rich-Text Info from Profile </li>
							<li>%firstname%     - To Display First Name </li>
							<li>%lastname%      - To Display Last Name </li>
							<li>%username%      - To Display Username </li>
							<li>%thumbnail% - To Display User's Thumbnail Photo </li>
							<li>%website% - To Display User's Website </li>
							<li>%aim% - To Display User's Website </li>
							<li>%yahooim% - To Display User's Website </li>
							<li>%jabbergoogle% - To Display User's Website </li>
						</ul>
					</div>
					<div id="added_fields" class="template-content">
						<strong>Codes that you have added are: </strong>
						<ul>
						<?php if( is_array($people_list_option['settings']) ): 
							foreach ($people_list_option['settings'] as $index => $field_slug): ?>
								<li class="template-code-list">%<?php echo $index; ?>%</li>
						<?php endforeach; 
						endif; ?>
						</ul>
					</div>
				</div>
			</div>
			<textarea name="template-text" class="template-text" id="template-text"><?php 
				if( !empty($list['template']) )
					echo  stripslashes(trim($list['template']));	
				else 
					echo people_lists_default_template(); ?>
			</textarea>
		</div>
	
		<div id="availableList" class="listDiv"> 
			<h4>Available People</h4>
			<p>List of users that have not been selected to be in your list. 
			Drag and drop the a person into the selected people area.</p>	
			<ul id="sortable1" class='droptrue'>
			<?php if( is_array($users_of_blog) ):
				foreach($users_of_blog as $person): 
					if(!in_array($person->ID, $list['list']['uid'])): ?>
						<li class="ui-state-default" id="uid_<?php echo $person->ID; ?>"><?php echo $person->display_name; ?><span><?php echo $person->user_email; ?></span></li>
					<?php else: 
						$selected_people[$person->ID] = $person;
						endif;
				endforeach;
			endif; ?>
			</ul>
		</div>
		
		<div id="selectedUserList" class="listDiv">
			<h4>Selected People</h4> <a href="#" id="selected-lock">Pin</a>
			<p>List of users that are have been selected to be in your list. Drag and drop a person into the available people area to remove them.</p>
			<ul id="sortable2" class='droptrue clear'>
			<?php if( is_array($list['list']['uid']) ):
				foreach(  $list['list']['uid'] as $person_id ): ?>
					<li class="ui-state-default" id="uid_<?php echo $selected_people[$person_id]->ID; ?>"><?php echo $selected_people[$person_id]->display_name; ?><span><?php echo $selected_people[$person_id]->user_email; ?></span></li>
			<?php endforeach; 
			endif;?>
			</ul>
		</div>
		
		<p class="submit clear">
		<?php if(is_numeric($list_id)): ?>
			<input type="hidden" value="<?php echo $list_id;?>" name="list_id" id="list-id" />
			<input id="submit-people-list" type="submit" class="button-primary" value="<?php _e('Update Changes') ?>" /> 
		<?php else: ?>
			<input id="submit-people-list" type="submit" class="button-primary" value="<?php _e('Add List') ?>" /> 
		<?php endif; ?>
			<img src="<?php bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" id="ajax-response" />
		</p>
		</form>
	</div>
<?php 
}

/**
 * people_list_field_form function.
 * Description: Building of form users interact with to add fields that are inserted into a jQuery Sortable list that allows 
 * 				for sorting and lists the field name, the template code that goes along with that field and an option for 
 * 				deletion of added fields.
 * @access public
 * @return void
 */
function people_list_field_form(){
	$option_name = 'people-lists'; 
	$people_list_option = get_option($option_name);
		
?>
	<div id="contact-info-shell">
		<label for="contact-info-field">Name of new field</label><br />
		<input type="text" name="contact-info-field" id="contact-info-field" size="30" />
	
		<p class="submit">
			<input type="submit" id="add-field-button" class="button-secondary" value="<?php _e('Add Field') ?>" /> 
			<img src="<?php bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" id="ajax-response2" />
		</p>
	
		<p>This name should be a one or two word description of your new field. (eg. Position, Location, etc.)</p><br />
		<p>List of fields that are being added to Contact Info section in Your Profile. <br/> Drag & Drop to change the order of their display in Your Profile.</p><br />
		<form id="profile-field-form">
			<ul id="sortable-profile-field">	
			<?php if( is_array($people_list_option['settings']) ): 
				foreach ($people_list_option['settings'] as $index => $field_slug):?>
					<li class="ui-state-default" ><?php echo stripslashes($field_slug); ?><span>Template Code: %<?php echo $index; ?>%<br /><a href="#" class="delete-field-link">Delete</a></span>
						<input type="hidden" value="<?php echo $index; ?>" name="field_slug_list[]" />
						<input type="hidden" value="<?php echo stripslashes($field_slug); ?>" name="field_name_list[]" />
					</li>
			<?php endforeach; 
			endif; ?>			
			</ul>
		</form>
	</div>
<?php
}

/**
 * people_lists_default_template function.
 * Description: Display the default template, which includes a thumbnail, nickname, email and user's bio from their "Your 
 * 				Profile" tab.
 * @access public
 * @return void
 */
function people_lists_default_template()
{
	$html  =	"<div class ='user-thumbnail'>%thumbnail%</div> \n";
	$html .=	"<div class ='user-info'>%nickname%</div> \n";
	$html .=	"<div class ='user-info'>%email%</div> \n";
	$html .=	"<div class ='user-bio'>%bio%</div> \n";

	return $html;
}

/**
 * people_lists_shortcode function.
 * Description: Creation of the [people-lists list=example-list] shortcode and conversion of the template codes into the 
 *  			selected display option selected by the user.
 * @access public
 * @param mixed $atts
 * @return void
 */
function people_lists_shortcode($atts) {

	$option_name = 'people-lists'; 
	$people_list_option = get_option($option_name);
	
	extract(shortcode_atts(array(
		'list' => null,
			), $atts));
	if( !isset($list) )
		return "Empty list - Please remove the [people-lists] code.";
	
	
	$people_lists = get_option('people-lists');	
	$found_people_list = people_lists_slug_exists($list,$people_lists['lists']);
	
	if(!$found_people_list)
		return "This list is non-existent - Please remove the [people-lists list=".$list."] code.";

	$users_of_blog = get_users_of_blog();	
	$input_template = array();

	$input_template[0] = "%nickname%";
	$input_template[1] = "%email%";
	$input_template[2] = "%bio%";	
	$input_template[3] = "%firstname%";
	$input_template[4] = "%lastname%";
	$input_template[5] = "%username%";
	$input_template[6] = "%thumbnail%";
	$input_template[7] = "%website%";
	$input_template[8] = "%aim%";
	$input_template[9] = "%yahooim%";
	$input_template[10] = "%jabbergoogle%";	
	
	$counter = 11;
	if( is_array($people_list_option['settings']) ): 
		foreach($people_list_option['settings'] as $index => $field_slug):
			$input_template[$counter] = "%".$index."%";
			$counter++; 
		endforeach;
	endif;
	
	if( is_array($found_people_list['list']['uid']) ): 
		foreach($found_people_list['list']['uid'] as $id):
			$replacements = array();
			$user_data = get_userdata($id);
	
			$replacements[0] = $user_data->nickname;
			$replacements[1] = $user_data->user_email;
			$replacements[2] = $user_data->description;
			$replacements[3] = $user_data->first_name;
			$replacements[4] = $user_data->last_name;
			$replacements[5] = $user_data->user_login;
			$replacements[6] = get_avatar($id);	
			$replacements[7] = $user_data->user_url;
			$replacements[8] = $user_data->aim;
			$replacements[9] = $user_data->yim;
			$replacements[10] = $user_data->jabber;									
			
			$counter = 11;
			if( is_array($people_list_option['settings']) ): 
				foreach($people_list_option['settings'] as $index => $field_slug):
					$replacements[$counter] =  $user_data->$index; 
					$counter++; 
				endforeach;	
			endif;
			$html = '<div class="person">';
			$html .= stripslashes($found_people_list['template']);
			$html .= '</div>';
			$html2 .= str_replace($input_template, $replacements, $html);
					
		endforeach;
	endif;
	
	return $html2;
}

/* --- End of File --- */