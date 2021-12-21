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

function wp_projects_shortcode() {
	return '
		<div class="projects-title">
			<a href="/projects">Projects</a>
		</div>
	';
}

function wp_commits_shortcode() {
	return '
		<div class="commits-title">
			<a href="/commits">Commits</a>
		</div>
	';
}

function sc_gitlab_shortcodes_init() {
	add_shortcode('commits', 'wp_commits_shortcode');
	add_shortcode('projects', 'wp_projects_shortcode');
}
add_action("init", "sc_gitlab_shortcodes_init");

