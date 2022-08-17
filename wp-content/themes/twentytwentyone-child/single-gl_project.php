<?php

get_header("child");

const TAXONOMY = "users";
$post_id = get_the_ID();

// add_post_meta($post_id, "gitlab_project_id", "15714647");

$post_terms = wp_set_object_terms($post_id, "user_4", TAXONOMY);
$post_terms = wp_get_object_terms($post_id, TAXONOMY);

ob_start();
?>

<p>GL_PROJECT[SINGLE] TEMPLATE?!!</p>
<table>
	<tr>
		<td>POST ID:</td>
		<td><?php echo $post_id; ?></td>
	</tr>
	<tr>
		<td>TAXONOMY:</td>
		<td><?php echo TAXONOMY; ?></td>
	</tr>
</table>
<?php
echo ob_get_clean();

get_footer();


