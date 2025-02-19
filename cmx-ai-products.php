<?php defined( 'ABSPATH' ) or die( 'Oxytocin!' );

/**
 * Plugin Name: CLOUD Meister -  AI Products
 * Plugin URI: https://cloudmeister.ch/plugin/cmx-ai-products/
 * Description: Automatisierte, KI-gestützte Erstellung von SEO-optimierten und mehrsprachigen Produktbeschreibungen für WooCommerce.
 * Version:  2025.0219.0349
 * Author: CLOUD Meister O&Uuml;
 * Author URI: https://cloudmeister.ch/
 * License: GPL2
 * Text Domain: cmx-ai-products
 * Domain Path: /languages
 * Requires PHP: 8.1
 * Requires at least: 6.7.1
 */


foreach (array('description','settings','cloudmeister') as &$value) {
	require_once plugin_dir_path(__FILE__) .$value .'.php';
}

update_plugin_version(basename(plugin_basename(__FILE__)));
