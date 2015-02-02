<?php
/*
 * Plugin Name: Third Wunder FAQ Plugin
 * Version: 1.0
 * Plugin URI: http://www.thirdwunder.com/
 * Description: Third Wunder FAQ CPT plugin
 * Author: Mohamed Hamad
 * Author URI: http://www.thirdwunder.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: tw-faq-plugin
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Mohamed Hamad
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

if(!class_exists('AT_Meta_Box')){
  require_once("includes/My-Meta-Box/meta-box-class/my-meta-box-class.php");
}

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

$dir = TW_FAQ_Plugin()->dir;
$prefix = 'tw_';

$faq_slug = get_option('wpt_tw_faq_slug') ? get_option('wpt_tw_faq_slug') : "faq";
$faq_search = get_option('wpt_tw_faq_search') ? true : false;
$faq_archive = get_option('wpt_tw_faq_archive') ? true : false;

$faq_category = get_option('wpt_tw_faq_category') ? get_option('wpt_tw_faq_category') : "off";
$faq_tag      = get_option('wpt_tw_faq_tag') ? get_option('wpt_tw_faq_tag') : "off";

$faq_cat_slug = get_option('wpt_tw_faq_category_slug') ? get_option('wpt_tw_faq_category_slug') : "faq-category";
$faq_tag_slug = get_option('wpt_tw_faq_tag_slug') ? get_option('wpt_tw_faq_tag_slug') : "faq-tag";

TW_FAQ_Plugin()->register_post_type(
                    'tw_faq',
                    __( 'FAQ',      'tw-faq-plugin' ),
                    __( 'FAQ',      'tw-faq-plugin' ),
                    __( 'FAQ CPT',  'tw-faq-plugin' ),
                    array(
                      'menu_icon'=>plugins_url( 'assets/img/cpt-icon-faq.png', __FILE__ ),
                      'rewrite' => array('slug' => $faq_slug),
                      'exclude_from_search' => $faq_search,
                      'has_archive'     => $faq_archive,
                    )
                  );

if($faq_category=='on'){
  TW_FAQ_Plugin()->register_taxonomy( 'tw_faq_category', __( 'FAQ Categories', 'tw-faq-plugin' ), __( 'FAQ Category', 'tw-faq-plugin' ), 'tw_faq', array('hierarchical'=>true, 'rewrite'=>array('slug'=>$faq_cat_slug)) );
}

if($faq_tag=='on'){
  TW_FAQ_Plugin()->register_taxonomy( 'tw_faq_tag',      __( 'FAQ Tags', 'tw-faq-plugin' ),       __( 'FAQ Tag', 'tw-faq-plugin' ), 'tw_faq', array('hierarchical'=>false, 'rewrite'=>array('slug'=>$faq_tag_slug)) );
}

function tw_faq_show_siderbar(){
  $show_sidebar  = (get_option('wpt_tw_faq_show_sidebar') && get_option('wpt_tw_faq_show_sidebar')=='on') ? true : false;
  return $show_sidebar;
}