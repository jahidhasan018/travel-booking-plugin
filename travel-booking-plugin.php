<?php
/**
 * Plugin Name: Travel Booking Plugin
 * Plugin URI:  https://yourwebsite.com/travel-booking-plugin
 * Description: A customizable travel booking plugin with widgets for flights, cars, activities, and stays.
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://yourwebsite.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: travel-booking-plugin
 * Domain Path: /languages
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Define Constants
define('TRAVEL_BOOKING_PLUGIN_VERSION', '1.0.0');
define('TRAVEL_BOOKING_PLUGIN_FILE', __FILE__);
define('TRAVEL_BOOKING_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TRAVEL_BOOKING_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TRAVEL_BOOKING_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('TRAVEL_BOOKING_PLUGIN_ASSETS', TRAVEL_BOOKING_PLUGIN_URL . 'assets/');

// Instantiate and run the plugin
if (class_exists('TravelBookingPlugin\\Core\\Plugin')) {
    $plugin = new TravelBookingPlugin\Core\Plugin();
    $plugin->run();
}