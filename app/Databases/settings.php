<?php 
namespace Databases\settings;

class settings
{
	//Set the Post Custom Field in the WP dashboard as Name/Value pair 
	function Count($post_ID) {
	
		//Set the name of the Posts Custom Field.
		$count_key = 'post_views_count'; 
		
		//Returns values of the custom field with the specified key from the specified post.
		$count = get_post_meta($post_ID, $count_key, true);
		
		//If the the Post Custom Field value is empty. 
		if($count == ''){
			$count = 0; // set the counter to zero.
			
			//Delete all custom fields with the specified key from the specified post. 
			delete_post_meta($post_ID, $count_key);
			
			//Add a custom (meta) field (Name/value)to the specified post.
			add_post_meta($post_ID, $count_key, '0');
			return $count . ' View';
		
		//If the the Post Custom Field value is NOT empty.
		}else{
			$count++; //increment the counter by 1.
			//Update the value of an existing meta key (custom field) for the specified post.
			update_post_meta($post_ID, $count_key, $count);
			
			//If statement, is just to have the singular form 'View' for the value '1'
			if($count == '1'){
			return $count . ' View';
			}
			//In all other cases return (count) Views
			else {
			return $count . ' Views';
			}
		}
	}
	//Function: Gets the number of Post Views to be used later.
	function get_PostViews($post_ID){
		$count_key = 'post_views_count';
		//Returns values of the custom field with the specified key from the specified post.
		$count = get_post_meta($post_ID, $count_key, true);
	
		return $count;
	}
	//Function: Add/Register the Non-sortable 'Views' Column to your Posts tab in WP Dashboard.
	function post_column_views($newcolumn){
		//Retrieves the translated string, if translation exists, and assign it to the 'default' array.
		$newcolumn['post_views'] = __('Views');
		return $newcolumn;
	}
	
	
	//Function: Populate the 'Views' Column with the views count in the WP dashboard.
	function post_custom_column_views($column_name, $id){   
		if($column_name === 'post_views'){
			// Display the Post View Count of the current post.
			// get_the_ID() - Returns the numeric ID of the current post.
			echo get_PostViews(get_the_ID());
		}
	}
	
	
	/**UP TO HERE IS THE SAME AS CODE-3. NEXT IS WHAT IS REQUIRED FOR SORTABLE 'VIEWS' COLUMN**/
	
	//Function: Register the 'Views' column as sortable in the WP dashboard.
	function register_post_column_views_sortable( $newcolumn ) {
		$newcolumn['post_views'] = 'post_views';
		return $newcolumn;
	}

	
	//Function: Sort Post Views in WP dashboard based on the Number of Views (ASC or DESC).
	function sort_views_column( $vars ) 
	{
		if ( isset( $vars['orderby'] ) && 'post_views' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'post_views_count', //Custom field key
				'orderby' => 'meta_value_num') //Custom field value (number)
			);
		}
		return $vars;
	}
	
}
	add_filter( 'request', 'sort_views_column' );
	//Hooks a function to a specific filter action.
	//Applied to the list of columns to print on the manage posts screen.
	add_filter( 'manage_posts_columns', 'post_column_views' );
	//Hooks a function to a specific action. 
	//allows you to add custom columns to the list post/custom post type pages.
	//'10' default: specify the function's priority.
	//'2' is the number of the functions' arguments.
	add_action('manage_posts_custom_column', 'post_custom_column_views',10,2);
	add_filter( 'manage_edit-post_sortable_columns', 'register_post_column_views_sortable' );
?>