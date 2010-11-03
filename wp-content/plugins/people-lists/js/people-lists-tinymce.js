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

// Javascript to load TinyMCE to textarea in Your Profile
jQuery(function($) {
	if ( typeof( tinyMCE ) == "object" && typeof( tinyMCE.execCommand ) == "function"){
		tinyMCE.execCommand("mceAddControl", false, "description");
	}
	$("#description").before("<a href='#' class='plain'>HTML</a> <a href='#' class='visual active'>Visual</a>");
	
	$(".visual").live("click",function(){
		$(this).addClass("active");
		$(".plain").removeClass("active");
		
		tinyMCE.execCommand('mceAddControl', false, 'description');
		return false;
	});
	$(".plain").live("click",function(){
		$(this).addClass("active");
		$(".visual").removeClass("active");
		
		tinyMCE.execCommand('mceRemoveControl', false, 'description');
		return false;
	});
	
	$("label[for='description']").each(function(){
 		$(this).html($(this).html().replace(/[^>]+$/,"General Information"));
	}); 

}); 

/* --- End of File --- */