<?php
get_header('child');

/*
* archive description(category desc) worthless piece of sh*t
* $description = get_the_archive_description();
*/
?>

<?php if (have_posts()): ?>
	<header class="archive-header">
		<?php the_archive_title('<h1 class="archive-title">', '</h1>'); ?>
	</header><!-- archive page-header -->

	<?php
		get_template_part(
			'template-parts/content/content-grid',
			'start'
		);

		while (have_posts()) {
			the_post();
			get_template_part(
				"template-parts/content/content",
				"child",
				["postID" => get_the_ID()]
			);
		}

		get_template_part(
			'template-parts/content/content-grid',
			'end'
		);
	?>

	<?php twenty_twenty_one_the_posts_navigation(); ?>
<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

<?php get_footer(); ?>
