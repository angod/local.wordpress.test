<?php
/**
 * Plugin Name: sc_commits_and_projects
 * Description: shortcodes commits and projects for gitlab API
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version: 0.0.1
 * Author: angod
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       commits and projects shortcodes
 *
 * @package           nssc_commits_and_projects
 */

//==============================================================================
// PROJECTS & COMMITS: id isn't set error
function sc_projects_and_commits_error($sc_type) {
	$sc_id_title = ($sc_type === "PROJECTS") ? "userID" : "projectID";
	ob_start();
?>

	<div class="sc-error">
		<header class="sc-error-header">
			<h1 class="sc-error-title"><?php echo $sc_id_title; ?> isn't set!</h1>
		</header>
	</div>

<?php
	return ob_get_clean();
}

//==============================================================================
// PROJECTS & COMMITS: markup helper
function sc_projects_and_commits_markup($sc_type, $sc_id, $sc_amount, $sc_data) {
	$sc_title = ($sc_type === "PROJECTS") ? "projects" : "commits";
	$sc_id_title = ($sc_type === "PROJECTS") ? "userID" : "projectID";
	ob_start();
?>

	<div class="<?php echo $sc_title; ?>"><!-- START#div.$sc_title -->
		<header class="<?php echo $sc_title; ?>-header">
			<h1 class="<?php echo $sc_title; ?>-title">
				GitLab last <?php echo $sc_amount; ?>
				<?php echo $sc_title; ?>
				for <?php echo $sc_id_title; ?>:
				<?php echo $sc_id; ?>
			</h1>
		</header>
		<ul class="<?php echo $sc_title; ?>-list"><!-- START# ul.$sc_title-list -->
		<?php foreach ($sc_data as $sc_entry): ?>
			<li>
				<a href="<?php echo $sc_entry->web_url; ?>">
				<?php
					echo ($sc_type === "PROJECTS" ? $sc_entry->name : $sc_entry->title);
				?>
				</a>
			</li>
		<?php endforeach; ?>
		</ul><!-- END# ul.$sc_title-list -->
	</div><!-- END#div.$sc_title -->

<?php
	return ob_get_clean();
}

//==============================================================================
// PROJECTS shortcode handler START
function get_user_projects($user_id, $amount) {
	$response = wp_remote_get(
		"https://gitlab.com/api/v4/users/${user_id}/projects?per_page=${amount}"
	);
	return json_decode($response["body"]);
}

function wp_projects_shortcode($atts) {
	if (!isset($atts["user_id"])) {
		return sc_projects_and_commits_error("PROJECTS");
	}

	$projects_atts = shortcode_atts(
		array(
			"user_id"	=> NULL,
			"amount"	=> 10
		),
		$atts
	);

	$user_projects = get_user_projects(
		$projects_atts["user_id"],
		$projects_atts["amount"]
	);

	return sc_projects_and_commits_markup(
		"PROJECTS",
		$projects_atts["user_id"],
		$projects_atts["amount"],
		$user_projects
	);
}
// PROJECTS shortcode handler END
//==============================================================================

//==============================================================================
// COMMITS shortcode handler START
function get_project_commits($project_id, $amount) {
	$response = wp_remote_get(
		"https://gitlab.com/api/v4/projects/{$project_id}/repository/commits?per_page={$amount}"
	);
	return json_decode($response["body"]);
}

function wp_commits_shortcode($atts) {
	if (!isset($atts["project_id"])) {
		return sc_projects_and_commits_error("COMMITS");
	}

	$commits_atts = shortcode_atts(
		array(
			"project_id"	=> NULL,
			"amount"		=> 10
		),
		$atts
	);

	$project_commits = get_project_commits(
		$commits_atts["project_id"],
		$commits_atts["amount"]
	);

	return sc_projects_and_commits_markup(
		"COMMITS",
		$commits_atts["project_id"],
		$commits_atts["amount"],
		$project_commits
	);
}
// COMMITS shortcode handler END
//==============================================================================

function sc_gitlab_shortcodes_init() {
	add_shortcode('commits', 'wp_commits_shortcode');
	add_shortcode('projects', 'wp_projects_shortcode');
}
add_action("init", "sc_gitlab_shortcodes_init");

