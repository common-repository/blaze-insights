<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists('Blaze_Insights') ) {
    class Blaze_Insights
    {
        private static $instance;

        public static function get_instance()
        {
            if( !self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            $this->includes();
        }

        public function includes()
        {
            require_once "class-blaze-insights-update-checker.php";
            require_once "class-blaze-insights-admin.php";
            require_once "class-blaze-insights-wizard.php";

			Blaze_Insights_Update_Checker::checkUpdate();
        }

        /**
		 * Locate template for Smart Coupons
		 *
		 * @param string $template_name The template name.
		 * @param mixed  $template Default template.
		 * @return mixed $template
		 */
		public static function locate_blaze_insights_template( $template_name = '', $template = '' ) {

			$default_path = untrailingslashit( plugin_dir_path( BLAZE_INSIGHTS_PLUGIN_FILE ) ) . '/templates/';

			$plugin_base_dir = substr( plugin_basename( BLAZE_INSIGHTS_PLUGIN_FILE ), 0, strpos( plugin_basename( BLAZE_INSIGHTS_PLUGIN_FILE ), '/' ) + 1 );

			// Look within passed path within the theme - this is priority.
			$template = locate_template(
				array(
					'woocommerce/' . $plugin_base_dir . $template_name,
					$plugin_base_dir . $template_name,
					$template_name,
				)
			);

			// Get default template.
			if ( ! $template ) {
				$template = $default_path . $template_name;
			}

			return $template;
		}
    }
    Blaze_Insights::get_instance();
}
