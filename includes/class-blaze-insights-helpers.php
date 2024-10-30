<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists('Blaze_Insights_Helpers')) {
    class Blaze_Insights_Helpers
    {
        /**
         * Check if the current browser is the one setting up the wizard
         *
         * @return boolean
         */
        public static function is_wizard_user_cookie()
        {
            return isset($_COOKIE['blaze_insights_wizard']) && $_COOKIE['blaze_insights_wizard'] == 1;
        }

        /**
         * Check if the wizard setup has been done
         *
         * @return boolean
         */
        public static function is_wizard_process_done()
        {
            return get_option('blaze_insights_wizard_complete');
        }

        /**
         * Check if the wizard has been skipped
         *
         * @return boolean
         */
        public static function is_wizard_process_skipped()
        {
            return get_option('blaze_insights_wizard_skip');
        }
        
        /**
         * Check if the current view is the wizard page
         *
         * @return boolean
         */
        public static function isWizardPage()
        {
            return $_GET['page'] == 'blaze-insights-wizard';
        }

        /**
         * Get the blaze insights credentials
         *
         * @return Array
         */
        public static function getAuthCredentials()
        {
            $client_id = get_option('blaze_insights_client_id');
			$client_secret =  get_option('blaze_insights_client_secret');
            return base64_encode($client_id . ":" . $client_secret );
        }

        /**
         * Sets the $_COOKE['blaze_insights_wizard]
         *
         * @return void
         */
        public static function set_setup_cookie()
        {
            setcookie("blaze_insights_wizard", 1, time()+24*60*60);
        }

        /**
         * Removes the $_COOKE['blaze_insights_wizard]
         *
         * @return void
         */
        public static function remove_setup_cookie()
        {
            setcookie("blaze_insights_wizard", "", time()-3600);
        }
    }
}