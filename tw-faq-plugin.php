<?php
/*
 * Plugin Name: TW_FAQ_Plugin
 * Version: 1.0
 * Plugin URI: http://www.hughlashbrooke.com/
 * Description: This is your starter template for your next WordPress plugin.
 * Author: Hugh Lashbrooke
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: tw-faq-plugin
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-tw-faq-plugin.php' );
require_once( 'includes/class-tw-faq-plugin-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-tw-faq-plugin-admin-api.php' );
require_once( 'includes/lib/class-tw-faq-plugin-post-type.php' );
require_once( 'includes/lib/class-tw-faq-plugin-taxonomy.php' );

/**
 * Returns the main instance of TW_FAQ_Plugin to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object TW_FAQ_Plugin
 */
function TW_FAQ_Plugin () {
	$instance = TW_FAQ_Plugin::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = TW_FAQ_Plugin_Settings::instance( $instance );
	}

	return $instance;
}

TW_FAQ_Plugin();
