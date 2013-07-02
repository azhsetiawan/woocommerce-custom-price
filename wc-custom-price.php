<?php
/*
Plugin Name: WooCommerce Custom Price
Plugin URI: http://sanvaheilm.com/
Description: Extends WooCommerce by adding an option to display custom text for products with free price and blank price.
Version: 0.1
Author: Azh Setiawan
Author URI: https://github.com/azhkuro/
Requires at least: 3.5
Tested up to: 3.5.1
Text Domain: woocommerce-customprice
Domain Path: /languages/

  Copyright: © 2013 Azh Setiawan.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
	 * Localisation
	 **/
	load_plugin_textdomain( 'woocommerce-customprice', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


	/**
	 * Methods class
	 **/
	if ( ! class_exists( 'WC_Custom_Price' ) ) {

		class WC_Custom_Price {

			public function __construct() {

				$this->custom_free_enabled = get_option( 'wc_cp_enable_custom_free' ) == 'yes' ? true : false;
				$this->custom_empty_enabled = get_option( 'wc_cp_enable_custom_empty' ) == 'yes' ? true : false;

				add_action( 'init', array( $this, 'plugin_init' ) );

				// Init settings
				$this->settings = array(
					array(
						'name' => __( 'Custom Price', 'woocommerce-customprice' ),
						'type' => 'title',
						'id' => 'wc_cp_options'
					),
					array(
						'name' => __( 'Custom free price', 'woocommerce-customprice' ),
						'desc' => __( 'Enable custom text on product with free price', 'woocommerce-customprice' ),
						'id' => 'wc_cp_enable_custom_free',
						'type' => 'checkbox'
					),
					array(
						'name' => __( 'Custom text for free price', 'woocommerce-customprice' ),
						'desc' => __( 'Change the default text "Free!" for product with input price = 0', 'woocommerce-customprice' ),
						'id' => 'wc_cp_text_custom_free',
						'type' => 'text',
						'desc_tip' => true
					),
					array(
						'name' => __( 'Custom blank price', 'woocommerce-customprice' ),
						'desc' => __( 'Enable custom text on product with blank price', 'woocommerce-customprice' ),
						'id' => 'wc_cp_enable_custom_empty',
						'type' => 'checkbox'
					),
					array(
						'name' => __( 'Custom text for blank price', 'woocommerce-customprice' ),
						'desc' => __( 'Display custom text for product without input price', 'woocommerce-customprice' ),
						'id' => 'wc_cp_text_custom_empty',
						'type' => 'text',
						'desc_tip' => true
					),
					array(
						'type' => 'sectionend',
						'id' => 'wc_cp_options'
					),
				);

				// Default options
				add_option( 'wc_cp_enable_custom_free', 'yes' );
				add_option( 'wc_cp_enable_custom_empty', 'yes' );
				add_option( 'wc_cp_text_custom_free', 'Pre-order' );
				add_option( 'wc_cp_text_custom_empty', 'Coming Soon' );

				// Admin
				add_action( 'woocommerce_settings_image_options_after', array( $this, 'admin_settings' ), 20);
				add_action( 'woocommerce_update_options_catalog', array( $this, 'save_admin_settings' ) );

			}


	        /*-----------------------------------------------------------------------------------*/
			/* Class Functions */
			/*-----------------------------------------------------------------------------------*/

			function plugin_init() {

				if ( $this->custom_free_enabled ) {
					add_filter( 'woocommerce_free_price_html', array( $this, 'custom_free_price' ) );
				}

				if ( $this->custom_empty_enabled ) {
					add_filter( 'woocommerce_empty_price_html', array( $this, 'custom_empty_price' ) );
				}
				
			}

			// Load the settings
			function admin_settings() {
				woocommerce_admin_fields( $this->settings );
			}

			// Save the settings
			function save_admin_settings() {
				woocommerce_update_options( $this->settings );
			}

			/*-----------------------------------------------------------------------------------*/
			/* Frontend Functions */
			/*-----------------------------------------------------------------------------------*/

			// Display custom text for product with input price '0'
			function custom_free_price() {
				$free_txt = get_option( 'wc_cp_text_custom_free' );
	
				return $free_txt;
			}

			// Display custom text for product without input price
			function custom_empty_price() {
				$blank_txt = get_option( 'wc_cp_text_custom_empty' );
	
				return $blank_txt;
			}

		}

		$WC_Custom_Price = new WC_Custom_Price();
	}
}
