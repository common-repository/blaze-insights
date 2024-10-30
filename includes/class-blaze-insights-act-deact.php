<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Blaze_Insights_Act_Deact' ) ) {

	/**
	 * Class for handling actions to be performed during initialization
	 */
	class Blaze_Insights_Act_Deact {

		/**
		 * Database changes required for Blaze Insights
		 */
		public static function blaze_insights_activate() {
			$client_id = get_option('blaze_insights_client_id');
			$client_secret = get_option('blaze_insights_client_secret');

			if(empty($client_id) || empty($client_secret)) {
				Blaze_Insights_Helpers::set_setup_cookie();
				$credentials = Blaze_Insights_API::get_instance()->generate_credentials();
				update_option('blaze_insights_client_id', $credentials["clientId"]);
				update_option('blaze_insights_client_secret', $credentials["clientSecret"]);
			}
		}

		/**
		 * Process activation
		 */
		public static function process_activation() {
		}

		/**
		 * Database changes required for Blaze Insights
		 */
		public static function blaze_insights_deactivate() {
			delete_option('blaze_insights_wizard_complete');
			delete_option('blaze_insights_wizard_skip');
			delete_option('blaze_insights_client_id');
			delete_option('blaze_insights_client_secret');
			Blaze_Insights_Helpers::remove_setup_cookie();
		}
	}

}
