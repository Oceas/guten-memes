<?php
/**
 * Plugin Name: Guten Memes
 * Plugin URI: https://github.com/khleomix
 * Description: Building jokes one day at a time.
 * Author: Scott Anderson
 * Author URI: https://scottkeithanderson.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @since   1.0.0
 * @package gc-memes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp_register_script} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @author Scott Anderson
 * @since 1.0.0
 */
function gc_register_gutenberg_card_block() {

	// Register block styles for both frontend + backend.
	wp_register_style(
		'gc-card-block-style',
		plugins_url( 'blocks/dist/blocks.style.build.css', __FILE__ ),
		is_admin() ? array( 'wp-editor' ) : null,
		true
	);

	// Register block editor script for backend.
	wp_register_script(
		'gc-card-block',
		plugins_url( 'blocks/dist/blocks.build.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
		true,
		true
	);

	// Register our block's editor-specific CSS.
	if ( is_admin() ) :
		wp_register_style(
			'gc-card-block-edit-style',
			plugins_url( 'blocks/dist/blocks.editor.build.css', __FILE__ ),
			array( 'wp-edit-blocks' ),
			true
		);
	endif;

	// Enqueue the script in the editor.
	register_block_type(
		'gc-card-block/main',
		array(
			'editor_script' => 'gc-card-block',
			'editor_style'  => 'gc-card-block-edit-style',
			'style'         => 'gc-card-block-style',
		)
	);

	add_filter( 'block_categories', function( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'jokes',
					'title' => 'Jokes',
				),
			)
		);
	}, 10, 2 );
}

add_action( 'init', 'gc_register_gutenberg_card_block' );
