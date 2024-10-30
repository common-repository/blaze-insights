<?php
/**
 * Plugin Name: Blaze Insights - use Google Analytics data to get more sales from WooCommerce
 * Plugin URI: https://blaze.online/insights
 * Description: Blaze Insights lets you evaluate the weaknesses of your purchase funnel and users shopping experience using your Google Analytics data. Leverage the insights to correct UX issues and boost conversion rates.
 * Version: 1.1.5
 * Author: Blaze Online
 * Author URI: https://blaze.online
 * Developer: Blaze Online
 * Developer URI: https://blaze.online
 * Requires at least: 4.4
 * Tested up to: 5.4.1
 * WC requires at least: 3.0.0
 * WC tested up to: 4.1.0
 * Text Domain: blaze-insights
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package blaze-insights
 */

if ( ! defined( 'BLAZE_INSIGHTS_PLUGIN_FILE' ) ) {
    define( 'BLAZE_INSIGHTS_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'BLAZE_INSIGHTS_VERSION' ) ) {
    define( 'BLAZE_INSIGHTS_VERSION', '1.1.5' );
}
if ( ! defined( 'BLAZE_INSIGHTS_PLUGIN_DIRNAME' ) ) {
    define( 'BLAZE_INSIGHTS_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
}
if ( ! defined( 'BLAZE_INSIGHTS_PLUGIN_URL' ) ) {
    define( 'BLAZE_INSIGHTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'BLAZE_INSIGHTS_API_ENDPOINT' ) ) {
    define( 'BLAZE_INSIGHTS_API_ENDPOINT', 'https://api.blaze.online/' );
}

 /**
 * Include class having function to execute during activation & deactivation of plugin
 */
require_once 'includes/class-blaze-insights-helpers.php';
require_once 'lib/blaze-insights-api/BlazeInsightsAPI.php';
require_once 'includes/class-blaze-insights-act-deact.php';

/**
 * On activation
 */
register_activation_hook( __FILE__, array( 'Blaze_Insights_Act_Deact', 'blaze_insights_activate' ) );

/**
 * On deactivation
 */
register_deactivation_hook( __FILE__, array( 'Blaze_Insights_Act_Deact', 'blaze_insights_deactivate' ) );

require_once 'includes/class-blaze-insights.php';