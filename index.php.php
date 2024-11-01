<?php
/*
	Plugin Name: Wanderlust PackGO
	Plugin URI: https://shop.wanderlust-webdesign.com/
	Description: Este plugin te permite autocompletar los datos del checkout.
	Version: 0.1
	Author: Wanderlust Web Design
	Author URI: https://wanderlust-webdesign.com
	WC tested up to: 4.0
	Copyright: 2007-2020 wanderlust-webdesign.com.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	function packgo_shipping_method_init() {
		if ( ! class_exists( 'WC_PackGO_Shipping_Method' ) ) {
			class WC_PackGO_Shipping_Method extends WC_Shipping_Method {
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'packgo_shipping_method'; // Id for your shipping method. Should be uunique.
					$this->method_title       = __( 'PackGO ' );  // Title shown in admin
					$this->method_description = __( 'PackGO te permite cotizar el valor del envio.' ); // Description shown in admin

					$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
					$this->title              = "Envios PackGO"; // This can be added as an setting but for this example its forced.

					$this->init();
				}

				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					// Load the settings API
					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

					// Save settings in admin if you have any defined
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}

				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping( $package ) {
					$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						'cost' => '250.00',
						'calc_tax' => 'per_item'
					);

					// Register the rate
					$this->add_rate( $rate );
				}
			}
		}
	}

	add_action( 'woocommerce_shipping_init', 'packgo_shipping_method_init' );

	function packgo_shipping_method( $methods ) {
		$methods['packgo_shipping_method'] = 'WC_PackGO_Shipping_Method';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'packgo_shipping_method' );
}
