<?php

get_header("child");

const TAXONOMY = "users";
$post_id = get_the_ID();

$post_terms = wp_set_object_terms($post_id, "user_4", TAXONOMY);
$post_terms = wp_get_object_terms($post_id, TAXONOMY);

ob_start();
?>

<table>
	<tr><td>GL PROJECT TEMPLATE?!!</td></tr>
	<tr><td><?php echo $post_id; ?></td></tr>
	<tr><td><?php echo TAXONOMY; ?></td></tr>
</table>
<?php
echo ob_get_clean();

get_footer();


