<?php get_header("child"); ?>

<div class="commits"><!-- START#div.commits -->
	<header class="commits-header">
		<h1 class="commits-title">GitLab commits for projectID: 26270713 of userID: 939595</h1>
	</header>

<?php
$response = wp_remote_get("https://gitlab.com/api/v4/projects/26270713/repository/commits");
$project_commits = json_decode($response["body"]);
?>

	<ul class="projects-list"><!-- START# ul.commits-list -->

	<?php
	foreach ($project_commits as $commit) {
		echo "
		<a href='{$commit->web_url}'>
			<li>$commit->title</li>
		</a>
		";
	}
	?>

	</ul><!-- END# ul.commits-list -->
</div><!-- END#div.commits -->

<?php get_footer(); ?>