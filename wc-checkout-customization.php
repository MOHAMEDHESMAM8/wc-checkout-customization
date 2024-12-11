<?php
/*
Plugin Name: Dynamic Checkout Customizations
Description: Customize the WooCommerce checkout process with dynamic fields and recommendations based on user behavior.
Version: 1.0
Author: Mohamed hesham

Requires Plugins: woocommerce
Elementor tested up to: 9.4.3
*/
namespace DynamicCheckout;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// Load required files
require_once plugin_dir_path(__FILE__) . 'includes/class-custom-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-custom-field-factory.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-dynamic-checkout-customizations.php';

// Initialize the main plugin class
new WC_Dynamic_Checkout_Customizations();
