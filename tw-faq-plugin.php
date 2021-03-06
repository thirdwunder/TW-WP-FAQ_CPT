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

//$faq_topic    = get_option('wpt_tw_faq_topic')    == 'on' ? true : false;
$faq_category = get_option('wpt_tw_faq_category') == 'on' ? true : false;
$faq_tag      = get_option('wpt_tw_faq_tag')      == 'on' ? true : false;

$faq_topic_slug = get_option('wpt_tw_faq_topic_slug')     ? get_option('wpt_tw_faq_topic_slug')     : "faq-topic";
$faq_cat_slug   = get_option('wpt_tw_faq_category_slug')  ? get_option('wpt_tw_faq_category_slug')  : "faq-category";
$faq_tag_slug   = get_option('wpt_tw_faq_tag_slug')       ? get_option('wpt_tw_faq_tag_slug')       : "faq-tag";

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

//if($faq_topic){
  TW_FAQ_Plugin()->register_taxonomy( 'tw_faq_topic', __( 'FAQ Topics', 'tw-faq-plugin' ), __( 'FAQ Topic', 'tw-faq-plugin' ), 'tw_faq', array('hierarchical'=>true, 'rewrite'=>array('slug'=>$faq_topic_slug, 'hierarchical' => true)) );
//}

if($faq_category){
  TW_FAQ_Plugin()->register_taxonomy( 'tw_faq_category', __( 'FAQ Categories', 'tw-faq-plugin' ), __( 'FAQ Category', 'tw-faq-plugin' ), 'tw_faq', array('hierarchical'=>true, 'rewrite'=>array('slug'=>$faq_cat_slug, 'hierarchical' => true)) );
}

if($faq_tag){
  TW_FAQ_Plugin()->register_taxonomy( 'tw_faq_tag',      __( 'FAQ Tags', 'tw-faq-plugin' ),       __( 'FAQ Tag', 'tw-faq-plugin' ), 'tw_faq', array('hierarchical'=>false, 'rewrite'=>array('slug'=>$faq_tag_slug)) );
}

function tw_faq_show_siderbar(){
  $show_sidebar  = (get_option('wpt_tw_faq_show_sidebar') && get_option('wpt_tw_faq_show_sidebar')=='on') ? true : false;
  return $show_sidebar;
}




add_action('init', 'tw_faq_plugin_external_link_fields', 10);
if(!function_exists('tw_faq_plugin_external_link_fields')){
  function tw_faq_plugin_external_link_fields(){
    $faq_external_links = get_option('wpt_tw_faq_external_links', false)=='on' ? true: false;

    if($faq_external_links){
      $faq_external_links_config = array(
        'id'             => 'faq_external_links_meta_box',
        'title'          => 'FAQ External Links',
        'pages'          => array('tw_faq'),
        'context'        => 'normal',
        'priority'       => 'high',
        'fields'         => array(),
        'local_images'   => false,
        'use_with_theme' => false
      );
      $faq_el_meta =  new AT_Meta_Box($faq_external_links_config);

      $repeater_fields[] = $faq_el_meta->addText('tw_link_name',array('name'=> 'Link Name'),true);
      $repeater_fields[] = $faq_el_meta->addText('tw_link_url',array('name'=> 'Link URL'),true);
      $repeater_fields[] = $faq_el_meta->addCheckbox('tw_link_external',array('name'=> 'External Link', 'desc'=>'Check this if this link is outside of this website'),true);

      $faq_el_meta->addRepeaterBlock('tw_external_links',array(
        'inline'   => false,
        'name'     => 'External Links',
        'fields'   => $repeater_fields,
        'sortable' => true
      ));

      $faq_el_meta->Finish();
    }


  }
}

function tw_faq_plugin_get_enabled_taxonomies(){
  $taxonomies = array();
  //$faq_topic    = get_option('wpt_tw_faq_topic')    == 'on' ? true : false;
  $faq_category = get_option('wpt_tw_faq_category') == 'on' ? true : false;
  $faq_tag      = get_option('wpt_tw_faq_tag')      == 'on' ? true : false;

  $faq_topic_slug = get_option('wpt_tw_faq_topic_slug')     ? get_option('wpt_tw_faq_topic_slug')     : "faq-topic";
  $faq_cat_slug   = get_option('wpt_tw_faq_category_slug')  ? get_option('wpt_tw_faq_category_slug')  : "faq-category";
  $faq_tag_slug   = get_option('wpt_tw_faq_tag_slug')       ? get_option('wpt_tw_faq_tag_slug')       : "faq-tag";

  //if($faq_topic){
    $taxonomies[] = $faq_topic_slug;
  //}
  if($faq_category){ $taxonomies[] = $faq_cat_slug; }
  if($faq_tag){ $taxonomies[] = $faq_tag_slug; }

  return $taxonomies;
}

/******************************
*********** Filters ***********
******************************/
add_filter( 'template_include', 'tw_faq_plugin_template_chooser');
function tw_faq_plugin_template_chooser( $template ) {
  // Post ID
  $post_id = get_the_ID();

  $faq_page = get_option('wpt_tw_faq_page') ? get_option('wpt_tw_faq_page') : false;
  $is_faq_page = is_page($faq_page);

  // For Assigned FAQ Page
  if($faq_page && $is_faq_page ){
    return tw_faq_plugin_get_template_hierarchy( 'template-faq' );
  }

  // For all other CPT
  if ( get_post_type( $post_id ) != 'tw_faq' ) {
      return $template;
  }

  // Else use custom template
  if ( is_single() ) {
    return tw_faq_plugin_get_template_hierarchy( 'single-tw_faq' );
  }elseif(is_archive()){
    //$is_faq_archive = is_post_type_archive('tw_faq');
    //if($faq_page!=='' && $is_faq_archive==true){
    //  return  tw_faq_plugin_get_template_hierarchy( 'template-faq' );
    //}else{
      return tw_faq_plugin_get_template_hierarchy( 'archive-tw_faq' );
    //}
  }

}

function tw_faq_plugin_get_template_hierarchy( $template ) {

    // Get the template slug
    $template_slug = rtrim( $template, '.php' );
    $template = $template_slug . '.php';

    // Check if a custom template exists in the theme folder, if not, load the plugin template file
    if ( $theme_file = locate_template( array($template, 'plugin_template/'.$template ) )  ) {
        $file = $theme_file;
    }
    else {
      $file = TW_FAQ_Plugin()->dir.'/templates/'.$template;
    }

    return apply_filters( 'rc_repl_template_' . $template, $file );
}