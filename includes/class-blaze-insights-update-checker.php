<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists('Blaze_Insights_Update_Checker') ) {
    class Blaze_Insights_Update_Checker
    {
        private static $instance;

        public static $updater = null;

        public static function get_instance()
        {
            if( !self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function getUpdatedPluginObject()
        {
            $update_plugins = get_site_transient( 'update_plugins' );
            if( isset($update_plugins->response["blaze-insights/blaze-insights.php"]) ) {
                return $update_plugins->response["blaze-insights/blaze-insights.php"];
            }

            return false;
        }

        public static function checkUpdate()
        {
            self::$updater = self::getUpdatedPluginObject();
        }

        protected static function version_checker( $version_1, $version_2 )
        {
            if( !$version_2 ) {
                return false;
            }

            if( $version_1 == $version_2 ) {
                return false;
            }

            $version_1_breakdown = explode( ".", $version_1 );
            $version_2_breakdown = explode( ".", $version_2 );

            if($version_1_breakdown[0] != $version_2_breakdown[0]) {
                return "major";
            }

            if($version_1_breakdown[1] != $version_2_breakdown[1]) {
                return "minor";
            }

            return "patch";
        }

        public static function getUpdateType()
        {
            if( !self::$updater ) {
                return false;
            }
            return self::version_checker(BLAZE_INSIGHTS_VERSION, self::$updater->new_version);
        }

        public static function isMajor()
        {
            return self::getUpdateType() == "major";
        }

        public static function isMinor()
        {
            return self::getUpdateType() == "minor";
        }

        public static function isPatch()
        {
            return self::getUpdateType() == "patch";
        }

        public static function getNotice()
        {
            if( !self::$updater ) {
                return '';
            }
            return self::$updater->upgrade_notice;
        }

        public function is_plugin_installed( $slug )
        {
            if ( ! function_exists( 'get_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $all_plugins = get_plugins();
            
            if ( !empty( $all_plugins[$slug] ) ) {
                return true;
            } else {
                return false;
            }
        }

        public function install_plugin( $plugin_zip ) {
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            wp_cache_flush();
             
            $upgrader = new Plugin_Upgrader();
            $installed = $upgrader->install( $plugin_zip );
           
            return $installed;
        }

        public function upgrade_plugin( $plugin_slug ) {
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            wp_cache_flush();
             
            $upgrader = new Plugin_Upgrader();
            $upgraded = $upgrader->upgrade( $plugin_slug );
           
            return $upgraded;
        }

        public static function handle_plugin_upgrade( $plugin_slug )
        {
            if ( self::$instance->is_plugin_installed( $plugin_slug ) ) {
                self::$instance->upgrade_plugin( $plugin_slug );
                $installed = true;
            } else {
                $installed = false;
            }

            if ( !is_wp_error( $installed ) && $installed ) {
                activate_plugin( $plugin_slug );
            }
        }
    }
    Blaze_Insights_Update_Checker::get_instance();
}
