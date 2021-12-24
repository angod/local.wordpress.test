<?php
/**
 * Plugin Name:       gl_project
 * Description:       custom post type[gl_project]
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.0.1
 * Author:            angod
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gl_project
 *
 * @package           ns_gl_project
 */
//==============================================================================
// CPT: GL Projects
function gl_project_register_post_type() {
	$labels = array(
		"name"			=> __("GL Projects"),
		"singular_name"	=> __("GL Project"),
	);

	$args = array(
		"labels"			=> $labels,
		"menu_position"		=> 4,
		"has_archive"		=> true,
		"public"			=> true,
		"hierarchical"		=> false,
		"show_in_rest"		=> true,
		"taxonomies"		=> array("users"),
		//> disable CPT post creating
		"capabilities"		=> array(
			"create_posts"	=> "do_not_allow"
		),
		"map_meta_recap"	=> true,
		"supports"			=> array(
			"title",
			"editor",
			"excerpt",
			"custom-fields",
			"thumbnail",
			"page-attributes"
		),
		"rewrite"			=> array(
			"slug"	=> "gl-projects"
		),
	);

	register_post_type("gl_project", $args);
}
add_action("init", "gl_project_register_post_type");

//==============================================================================
// Taxonomy [Users] for GL Projects
function gl_project_users_taxonomy() {
	$labels = array(
		"name"							=> __("Users"),
		"singular_name"					=> __("User"),
		"add_new_item"					=> __("Add New User"),
		"edit_item"						=> __("Edit User"),
		"update_item"					=> __("Update User"),
		"search_items"					=> __("Search User"),
		"new_item_name"					=> __("New User"),
		"back_to_items"					=> __("Back to Users"),
		"add_or_remove_item"			=> __("Add or Remove User"),
		"separate_items_with_commas"	=> __("Separate users with commas"),
		"choose_from_most_used"			=> __("Choose from the most used users"),
		"not_found"						=> __("No users found."),
	);

	register_taxonomy(
		"users",
		"gl_project",
		array(
			"labels"		=> $labels,
			"hierarchical"	=> false,
			"rewrite"		=> array(
				"slug"	=> "users"
			)
		)
	);
}
add_action("init", "gl_project_users_taxonomy");

//==============================================================================
function gl_project_users_add_term_fields($taxonomy) {
	echo
	'
		<div class="form-field form-required">
			<label for="gl-id">GitLab ID</label>
			<input type="text" name="gitlab-id" id="gl-id" />
			<p class="description">Enter GitLab ID</p>
		</div>
	';
}
add_action("users_add_form_fields", "gl_project_users_add_term_fields");

//==============================================================================
function gl_projects_users_edit_term_fields($term, $taxonomy) {
	$value = get_term_meta($term->term_id, "gitlab_id", true);

	echo
	'
		<tr class="form-field">
			<th>
				<label for="gl-id">GitLab ID</label>
			</th>
			<td>
				<input type="text" name="gitlab-id" id="gl-id" value="' . esc_attr($value) .'" />
				<p class="description">Update GitLab ID</p>
			</td>
		</tr>
	';
}
add_action("users_edit_form_fields", "gl_projects_users_edit_term_fields", 10, 2);

//==============================================================================
function gl_project_users_save_term_fields($term_id) {
	update_term_meta(
		$term_id,
		"gitlab_id",
		sanitize_text_field($_POST["gitlab-id"])
	);
}

add_action("created_users", "gl_project_users_save_term_fields");
add_action("edited_users", "gl_project_users_save_term_fields");

//==============================================================================
function gl_project_users_taxonomy_columns($users_list_table_columns) {
	$new_list_table_columns = array(
		"cb"			=> '<input type="checkbox" />',
		"name"			=> __("Name"),
		"gitlab_id"		=> __("GitLab ID"),
		"slug"			=> __("Slug"),
		"description"	=> __("Description"),
	);

	return $new_list_table_columns;
}
add_filter("manage_edit-users_columns", "gl_project_users_taxonomy_columns");

function gl_project_users_taxonomy_manage_gitlab_id_column($column_content, $column_name, $term_id) {
	$gitlab_id = get_term_meta($term_id, "gitlab_id", true);
	switch ($column_name) {
		case "gitlab_id":
			// get GitLab ID
			$column_content = esc_attr($gitlab_id);
			break;

		default:
			break;
	}

	return $column_content;
}
add_filter("manage_users_custom_column", "gl_project_users_taxonomy_manage_gitlab_id_column", 10, 3);

//==============================================================================
// https://wordpress.stackexchange.com/questions/388741/custom-taxonomies-with-custom-rewrites-slug-and-loading-a-taxonomy-archive-tem
function parse_taxonomy_root_request($wp) {
	// Bail out if no taxonomy QV was present, or if the term QV is.
	if (!isset($wp->query_vars["taxonomy"]) || isset($wp->query_vars["term"]))
		return;

	$tax_name = $wp->query_vars["taxonomy"];

	$tax			= get_taxonomy($tax_name);
	$tax_query_var	= $tax->query_var;

	// Bail out if a tax-specific qv for the specific taxonomy is present.
	if (isset($wp->query_vars[$tax_query_var]))
		return;

	$term_query = new WP_Term_Query(
		array(
			"taxonomy"		=> $tax_name, //	<====	Custom Taxonomy name
			"fields"		=> "slugs",
			"orderby"		=> "name",
			"order"			=> "ASC",
			"child_of"		=> 0,
			"parent"		=> 0,
			"hide_empty"	=> false,
			)
	);

	$tax_term_slugs = $term_query->terms;

	// Unlike #taxonomy/term# QVs, tax-specific QVs can specify an AND/OR list of terms.
	$wp->set_query_var($tax_query_var, implode(",", $tax_term_slugs));
}
add_action("parse_request", "parse_taxonomy_root_request");

function register_tax_root_rewrite($name, $types, $tax) {
	if (empty($tax["publicly_queryable"]))
		return;

	$slug = empty($tax["rewrite"]) || empty($tax["rewrite"]["slug"])
		? $name
		: $tax["rewrite"]["slug"];

	// add_rewrite_rule("\busers\b\/?", "/{$name}", "top");
	add_rewrite_rule("^$slug/?$", "index.php?taxonomy=$name", 'top');
}
add_action("registered_taxonomy", "register_tax_root_rewrite", 10, 3);

function users_template($template) {
	if (is_tax("users")) {
		return get_taxonomy_template();
	}

	return $template;
}
add_filter("template_include", "users_template");

//==============================================================================
// PLAYGROUND






