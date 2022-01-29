<?php
/**
 * Sydney Logo
 *
 * @package     Sydney Logo
 * @author      kharisblank
 * @copyright   2022 kharisblank
 * @license     GPL-2.0+
 *
 * @sydney-logo
 * Plugin Name: Sydney Logo
 * Plugin URI:  https://easyfixwp.com/
 * Description: Prepend logo image to header area when site title and description is showing. This plugin is made for Sydney WordPress theme.
 * Version:     0.0.1
 * Author:      kharisblank
 * Author URI:  https://easyfixwp.com
 * Text Domain: sydney-logo
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 */

// Disallow direct access to file
defined( 'ABSPATH' ) or die( __('Not Authorized!', 'sydney-logo') );

function sydney_logo_plugin_is_sydney_active() {

  $theme  = wp_get_theme();
  $parent = wp_get_theme()->parent();

  if ( ($theme != 'Sydney' ) && ($theme != 'Sydney Pro' ) && ($parent != 'Sydney') && ($parent != 'Sydney Pro') ) {
   return false;
  }

  return true;

}

function sydney_logo_plugin_main() {
  
  $is_sydney = sydney_logo_plugin_is_sydney_active();
  
  if( !$is_sydney ) {
    return;
  }  
  
  $site_logo = '';
  
  if ( get_theme_mod('site_logo') ) :
    
    $logo_id 	= attachment_url_to_postid( get_theme_mod( 'site_logo' ) );
    $logo_attrs = wp_get_attachment_image_src( $logo_id );
    
    ob_start();
    ?>
    
    <div class="sydney-site-logo">
  		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>">
        <img width="<?php echo esc_attr( $logo_attrs[1] ); ?>" height="<?php echo esc_attr( $logo_attrs[2] ); ?>" class="site-logo" src="<?php echo esc_url(get_theme_mod('site_logo')); ?>" alt="<?php bloginfo('name'); ?>" />
      </a>
    </div>
    
    <?php
    $site_logo = ob_get_contents();
    ob_end_clean();
    
  endif;

  $site_title = '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a></h1>';
  $site_description = '<p class="site-description"> ' . get_bloginfo( 'description' ) . '</p>';

  $settings                     = array();
  $settings['logo_url']         = esc_url(get_theme_mod('site_logo'));
  $settings['logo_img_link']    = $site_logo;
  $settings['site_title']       = $site_title;
  $settings['site_description'] = $site_description;
  
  wp_localize_script( 'sydney-scripts', 'site_logo_settings', $settings );

}
add_action( 'wp_enqueue_scripts', 'sydney_logo_plugin_main', 9999 );

function sydney_logo_plugin_script() {
  
  $is_sydney = sydney_logo_plugin_is_sydney_active();
  
  if( !$is_sydney ) {
    return;
  }  
  
  ?>
  
  <script type='text/javascript'>
    
  jQuery(function($) {
    
    var SydneyBranding = site_logo_settings.logo_img_link + site_logo_settings.site_title + site_logo_settings.site_description;
    
    if( $('.main-header .site-branding').length ) {
      $('.main-header .site-branding').html(SydneyBranding);
    }

  });   
    
  </script>
    
  <?php   
}
add_action( 'wp_footer', 'sydney_logo_plugin_script', 9999 );