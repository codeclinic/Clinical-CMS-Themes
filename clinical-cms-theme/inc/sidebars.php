<?php
global $themename;
//custom post type - sidebars
function theme_sidebars_init()
{
	global $themename;
	$labels = array(
		'name' => _x('Sidebars', 'post type general name', 'clinical-cms-theme'),
		'singular_name' => _x('Sidebar', 'post type singular name', 'clinical-cms-theme'),
		'add_new' => _x('Add New', $themename . '_sidebar', 'clinical-cms-theme'),
		'add_new_item' => __('Add New Sidebar', 'clinical-cms-theme'),
		'edit_item' => __('Edit Sidebar', 'clinical-cms-theme'),
		'new_item' => __('New Sidebar', 'clinical-cms-theme'),
		'all_items' => __('Sidebars', 'clinical-cms-theme'),
		'view_item' => __('View Sidebar', 'clinical-cms-theme'),
		'search_items' => __('Search Sidebars', 'clinical-cms-theme'),
		'not_found' =>  __('No sidebars found', 'clinical-cms-theme'),
		'not_found_in_trash' => __('No sidebars found in Trash', 'clinical-cms-theme'), 
		'parent_item_colon' => '',
		'menu_name' => __("Sidebars", 'clinical-cms-theme')
	);
	$args = array(  
		"labels" => $labels, 
		"public" => true,  
		"show_ui" => true,  
		"capability_type" => "post",
		"show_in_menu" => "themes.php",
		"hierarchical" => false,  
		"rewrite" => true,  
		"supports" => array("title", "page-attributes")
	);
	register_post_type($themename . "_sidebars", $args);
}  
add_action("init", "theme_sidebars_init"); 

//Adds a box to the main column on the Sidebars edit screens
function theme_add_sidebars_custom_box() 
{
	global $themename;
	add_meta_box( 
        "sidebars_config",
        __("Options", 'clinical-cms-theme'),
        "theme_inner_sidebars_custom_box_main",
        $themename . "_sidebars",
		"normal",
		"high"
    );
}
add_action("add_meta_boxes", "theme_add_sidebars_custom_box");
//backwards compatible (before WP 3.0)
//add_action("admin_init", "theme_add_custom_box", 1);

function theme_inner_sidebars_custom_box_main($post)
{
	global $themename;
	//Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), $themename . "_sidebars_noncename");
	
	//The actual fields for data entry
	$before_widget = esc_attr(get_post_meta($post->ID, "before_widget", true));
	$after_widget = esc_attr(get_post_meta($post->ID, "after_widget", true));
	$before_title = esc_attr(get_post_meta($post->ID, "before_title", true));
	$after_title = esc_attr(get_post_meta($post->ID, "after_title", true));
	$hidden = get_post_meta($post->ID, "hidden", true);
	
	echo '
	<table>
		<tr>
			<td>
				<label for="before_widget">' . __('Before widget', 'clinical-cms-theme') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="before_widget" name="before_widget" value="' . ($before_widget!='' ? ($before_widget!='empty' ? $before_widget : '') : '&lt;div id=\'%1$s\' class=\'widget %2$s\'&gt;') . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="after_widget">' . __('After widget', 'clinical-cms-theme') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="after_widget" name="after_widget" value="' . ($after_widget!='' ? ($after_widget!='empty' ? $after_widget : '') : '&lt;/div&gt;') . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="before_title">' . __('Before title', 'clinical-cms-theme') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="before_title" name="before_title" value="' . ($before_title!='' ? ($before_title!='empty' ? $before_title : '') : '&lt;h3 class=\'box_header\'&gt;') . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="after_title">' . __('After title', 'clinical-cms-theme') . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="after_title" name="after_title" value="' . ($after_title!='' ? ($after_title!='empty' ? $after_title : '') : '&lt;/h3&gt;') . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="hidden">' . __('Hidden', 'clinical-cms-theme') . ':</label>
			</td>
			<td>
				<select id="hidden" name="hidden">
					<option value="0"' . (!(int)$hidden ? ' selected="selected"' : '') . '>' . __('no', 'clinical-cms-theme') . '</option>
					<option value="1"' . ((int)$hidden ? ' selected="selected"' : '') . '>' . __('yes', 'clinical-cms-theme') . '</option>
				</select>
			</td>
		</tr>
	</table>';
}

//When the post is saved, saves our custom data
function theme_save_sidebars_postdata($post_id) 
{
	global $themename;
	//verify if this is an auto save routine. 
	//if it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	//verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times
	if(!isset($_POST[$themename . '_sidebars_noncename']) || !wp_verify_nonce($_POST[$themename . '_sidebars_noncename'], plugin_basename( __FILE__ )))
		return;


	//Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;

	//OK, we're authenticated: we need to find and save the data
	update_post_meta($post_id, "before_widget", ($_POST["before_widget"]=="" ? "empty" : $_POST["before_widget"]));
	update_post_meta($post_id, "after_widget", ($_POST["after_widget"]=="" ? "empty" : $_POST["after_widget"]));
	update_post_meta($post_id, "before_title", ($_POST["before_title"]=="" ? "empty" : $_POST["before_title"]));
	update_post_meta($post_id, "after_title", ($_POST["after_title"]=="" ? "empty" : $_POST["after_title"]));
	update_post_meta($post_id, "hidden", $_POST["hidden"]);
}
add_action("save_post", "theme_save_sidebars_postdata");

//custom sidebars items list
function clinical-cms-theme_sidebars_edit_columns($columns)
{
	$columns = array(  
		"cb" => "<input type=\"checkbox\" />",  
		"title" => _x('Sidebar name', 'post type singular name', 'clinical-cms-theme'),
		"order" =>  _x('Order', 'post type singular name', 'clinical-cms-theme'),
		"sidebars_hidden" => __('Hidden', 'clinical-cms-theme'),
		"date" => __('Date', 'clinical-cms-theme')
	);    

	return $columns;  
}  
add_filter("manage_edit-" . $themename . "_sidebars_columns", $themename . "_sidebars_edit_columns");

function manage_clinical-cms-theme_sidebars_posts_custom_column($column)
{
	global $post;
	switch($column)
	{
		case "order":
			echo get_post($post->ID)->menu_order;
			break;
		case "sidebars_hidden":
			$hidden = get_post_meta($post->ID, "hidden", true);
			echo ((int)$hidden ? __('yes', 'clinical-cms-theme') : __('no', 'clinical-cms-theme'));
			break;
	}
}
add_action("manage_" . $themename . "_sidebars_posts_custom_column", "manage_" . $themename . "_sidebars_posts_custom_column");

// Register the column as sortable
function clinical-cms-theme_sidebars_sortable_columns($columns) 
{
    $columns = array(
		"title" => "title",
		"order" => "order",
		"sidebars_hidden" => "hidden",
		"date" => "date"
	);

    return $columns;
}
add_filter("manage_edit-" . $themename . "_sidebars_sortable_columns", $themename . "_sidebars_sortable_columns");

function clinical-cms-theme_sidebars_column_orderby($vars) 
{
    if(isset($vars['orderby']) && isset($vars['hidden']) && 'hidden'==$vars['hidden']) 
	{
        $vars = array_merge($vars, array(
            'meta_key' => 'hidden',
            'orderby' => 'meta_value'
        ));
    }
 
    return $vars;
}
add_filter("request", $themename . "_sidebars_column_orderby");
?>