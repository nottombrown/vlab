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
 ?>
 <!-- Panel for profile settings and for managing fields. -->
<h3>Profile Settings</h3>
<h4>Add a new Contact Info field in Your Profile</h4>
<?php people_list_field_form(); ?>

<?php /* --- End of File --- */ ?>