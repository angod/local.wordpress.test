<?php

get_header("child");

/*
* https://old.megangarwood.com/blog/wp_reset_postdata-not-working/
*/
$query_args = array(
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order_by' => 'ID',
	'order' => 'DESC'
);

get_template_part(
	"template-parts/content/content-grid",
	"start"
);

$optin_query = new WP_Query($query_args);
if ($optin_query->have_posts()) {
	// Non-standard loop due to ongoing bug in core: https://core.trac.wordpress.org/ticket/18408
	// Can't wp_reset_postdata on post.php edit screen
	foreach ($optin_query->get_posts() as $post) {
		get_template_part(
			"template-parts/content/content",
			"child",
			["postID" => $post->ID]
		);
	}
}
get_template_part(
	"template-parts/content/content-grid",
	"end"
);

/*$posts_list = get_posts();
foreach ($posts_list as $post) {
	get_template_part(
		"template-parts/content/content-child",
		get_theme_mod("display_excerpt_or_full_post", "excerpt")
	);
}*/

get_footer();


