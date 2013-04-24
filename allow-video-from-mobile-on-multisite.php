<?php
/*
Plugin Name: Allow-video-from-mobile-on-multisite
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: YOUR NAME HERE
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: allow-video-from-mobile-on-multisite
Domain Path: /languages
Network: true
*/

function avfmom_init( $context ) {
	if ( 'wp.newPost' === $context ) {
		add_filter( 'wp_kses_allowed_html', 'avfmom_tags', 10, 2 );
	}
}
add_action( 'xmlrpc_call', 'avfmom_init' );

function avfmom_remove_filters() {
	remove_filter( 'wp_kses_allowed_html', 'avfmom_tags', 10, 2 );
	remove_filter( 'kses_allowed_protocols', 'avfmom_protocols' );
}
add_action( 'insert_post', 'avfmom_remove_filters' );

function avfmom_tags( $allowedtags, $context ) {
	if ( 'post' === $context ) {
		$allowedtags['video'] = array(
			'src' => 1,
			'controls' => 1,
			'width' => 1,
			'height' => 1,
		);

		$allowedtags['object'] = array(
			'classid' => 1,
			'clsid' => 1,
			'codebase' => 1,
			'width' => 1,
			'height' => 1,
		);

		$allowedtags['param'] = array(
			'name' => 1,
			'value' => 1,
		);

		$allowedtags['embed'] = array(
			'src' => 1,
			'autoplay' => 1,
			'width' => 1,
			'height' => 1,
			'type' => 1,
			'pluginspage' => 1,
		);
	}
	return $allowedtags;
}

/**
 * Have to add this in the global scope because WP puts the protocols in a
 * static early during WP's bootstrap
 */
function avfmom_protocols( $protocols ) {
	$protocols[] = 'clsid';
	return $protocols;
}
add_filter( 'kses_allowed_protocols', 'avfmom_protocols' );


