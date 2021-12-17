<?php $postID = $args["postID"]; ?>

<article id="post-<?php echo $postID; ?>" class="masonry-post">
	<header class="masonry-entry-header">
		<h2 class="masonry-entry-title">
			<a href="<?php echo esc_url(get_permalink($postID)); ?>">
				<?php echo get_the_title($postID); ?>
			</a>
		</h2>
	</header>

	<div class="masonry-post-thumbnail">
		<a href="<?php echo esc_url(get_permalink($postID)); ?>">
			<?php echo get_the_post_thumbnail($postID); ?>
		</a>
	</div>

	<div class="masonry-post-desc">
		<p class="masonry-post-desc-text">
			<?php echo get_the_excerpt($postID); ?>
		</p>
	</div>
</article>