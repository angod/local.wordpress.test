<?php get_header("child"); ?>

<div class="projects"><!-- START#div.projects -->
	<header class="projects-header">
		<h1 class="projects-title">GitLab projects for userID: 939595</h1>
	</header>

<?php
$response = wp_remote_get("https://gitlab.com/api/v4/users/939595/projects");
$user_projects = json_decode($response["body"]);
?>

	<ul class="projects-list"><!-- START# ul.projects-list -->

	<?php
	foreach ($user_projects as $project) {
		echo "
		<a href='{$project->web_url}'>
			<li>$project->name</li>
		</a>
		";
	}
	?>

	</ul><!-- END# ul.projects-list -->
</div><!-- END#div.projects -->

<?php get_footer(); ?>