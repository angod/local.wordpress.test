<?php
/**
 * Plugin Name:       Daction plugin
 * Description:       Simple test plugin for learning purpose
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.0.1
 * Author:            angod
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       daction
 *
 * @package           nsdaction
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
 */
function nsdaction_daction_block_init() {
	register_block_type( __DIR__ );
}
add_action( 'init', 'nsdaction_daction_block_init' );
