<?php

get_header('child');

if ( have_posts() ) {
	?>
	<header class="result-header">
		<h1 class="result-title">
		<?php
			printf(
			'Results for "%s"',
			'<span class="page-description search-term">' . esc_html(get_search_query()) . '</span>'
			);
		?>
		</h1>
	</header><!-- search page-header -->

	<div class="search-result-count default-max-width">
	<?php
		printf(
			esc_html(
				_n(
					'We found %d result for your search.',
					'We found %d results for your search.',
					(int) $wp_query->found_posts,
					'twentytwentyone'
				)
			),
			(int) $wp_query->found_posts
		);
	?>
	</div><!-- .search-result-count -->
	<?php

	get_template_part(
		'template-parts/content/content-grid',
		'start'
	);

	while (have_posts()) {
		the_post();

		get_template_part(
			'template-parts/content/content',
			'child',
			["postID" => get_the_ID()]
		);
	}

	get_template_part(
		'template-parts/content/content-grid',
		'end'
	);

	// Previous/next page navigation.
	twenty_twenty_one_the_posts_navigation();
} else {
	get_template_part( 'template-parts/content/content-none' );
}

get_footer();