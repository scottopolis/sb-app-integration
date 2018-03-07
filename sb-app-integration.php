<?php
/**
 * Plugin Name:     SB App Integration
 * Plugin URI:      http://scottbolinger.com
 * Description:     Integrate WordPress with your mobile app. Login, post list images, and more.
 * Version:         0.1
 * Author:          Scott Bolinger
 * Author URI:      http://scottbolinger.com
 *
 * @author          Scott Bolinger
 * @copyright       Copyright (c) Scott Bolinger 2018
 *
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'SB_App_Integration' ) ) {

    class SB_App_Integration {

    	private static $instance;

		public static function instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
         * Include necessary files
         *
         * @access      private
         * @since       0.1.0
         * @return      void
         */
        private function includes() {

            require_once plugin_dir_path( __FILE__ ) . 'inc/class-wpapi-login.php';

            require_once plugin_dir_path( __FILE__ ) . 'inc/class-wpapi-mods.php';

            require_once plugin_dir_path( __FILE__ ) . 'inc/class-sb-woocommerce.php';
            
        }

	}
} // End if class_exists check


/**
 * The main function responsible for returning the instance
 *
 * @since       0.1.0
 * @return      SB_App_Integration::instance()
 *
 */
function sb_app_integration_load() {
    return SB_App_Integration::instance();
}
add_action( 'plugins_loaded', 'sb_app_integration_load' );