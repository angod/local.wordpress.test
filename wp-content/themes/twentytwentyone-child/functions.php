<?php

add_action("wp_enqueue_scripts", "tto_child_enqueue_styles");
function tto_child_enqueue_styles() {
	wp_enqueue_style("child-style", get_stylesheet_uri(),
		array("twenty-twenty-one-style"),
		wp_get_theme()->get("Version")
	);
}