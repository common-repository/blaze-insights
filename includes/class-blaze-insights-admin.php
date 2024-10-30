<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists('Blaze_Insights_Admin') ) {
    /**
     * Main class of Blaze Insights Admin Dashboard
     */
    class Blaze_Insights_Admin
    {
        /**
         * Variable to hold instance of Blaze_Insights_Admin
         *
         * @var $instance
         */
        private static $instance = null;

        /**
         * Gets or sets the singleton class
         *
         * @return Blaze_Insights_Admin Singleton object of Blaze_Insights_Admin
         */
        public static function get_instance()
        {
            if( !self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * 
         * @return void
         */
        public function __construct()
        {
            add_action( 'admin_menu', array( $this, 'admin_menu') );
            add_action( 'admin_enqueue_scripts', array( $this, 'register_blaze_insights_scripts') );
            add_action( 'admin_enqueue_scripts', array( $this, 'load_blaze_insights_scripts') );

            add_action( 'wp_ajax_blaze_insights_get_report', array( $this, 'blaze_insights_get_report' ) );
            add_action( 'wp_ajax_nopriv_blaze_insights_get_report', array( $this, 'blaze_insights_get_report' ) );
            add_action( 'wp_ajax_update_blaze_insights_plugin', array( $this, 'update_blaze_insights_plugin' ) );
            add_action( 'wp_ajax_nopriv_update_blaze_insights_plugin', array( $this, 'update_blaze_insights_plugin' ) );

            add_filter( 'blaze_insights_current_view', array( $this, 'blaze_insights_current_view' ) );
        }

        public function blaze_insights_current_view( $view_object = false )
        {
            if ( !$view_object ) {
                $google_views = get_option('blaze_insights_google_views');
                $selected_view = get_option('blaze_insights_selected_view');

                $view_object = array_filter($google_views, function( $view ) use ( $selected_view ) {
                    return $view["viewId"] == $selected_view;
                });

                return reset($view_object);
            }

            return $view_object;
        }

        public function admin_menu()
        {
            add_menu_page(
                __( 'Blaze Insights', 'blaze-insights' ),
                __( 'Blaze Insights', 'blaze-insights' ),
                'manage_options',
                'blaze-insights',
                array( $this, 'admin_contents' ),
                'dashicons-chart-line',
                3
            );

            add_submenu_page( 
                'blaze-insights', 
                __( 'Blaze Insights Settings', 'blaze-insights' ), 
                __( 'Blaze Insights Settings', 'blaze-insights' ), 
                'manage_options', 
                'blaze-insights-wizard', 
                array( Blaze_Insights_Wizard::get_instance(), 'wizard_cotnent'), 
                null 
            );
        }

        public function admin_contents()
        {
            $view_object = apply_filters('blaze_insights_current_view', false);

            require_once Blaze_Insights::locate_blaze_insights_template('admin/dashboard.php');
        }

        public function admin_header()
        {
            $view_object = apply_filters('blaze_insights_current_view', false);

            require_once Blaze_Insights::locate_blaze_insights_template('admin/header.php');
        }

        public function register_blaze_insights_scripts()
        {
            wp_register_style( 'blaze-insights', untrailingslashit( plugin_dir_url( BLAZE_INSIGHTS_PLUGIN_FILE ) ) . '/assets/css/blazeinsights.css', array(), BLAZE_INSIGHTS_VERSION );
            wp_register_script( 'chart', untrailingslashit( plugin_dir_url( BLAZE_INSIGHTS_PLUGIN_FILE ) ) . '/assets/js/chart.min.js', array( 'jquery' ), BLAZE_INSIGHTS_VERSION );
            wp_register_script( 'chartjs-plugin-annotation', untrailingslashit( plugin_dir_url( BLAZE_INSIGHTS_PLUGIN_FILE ) ) . '/assets/js/chartjs-plugin-annotation.min.js', array( 'jquery', 'chart' ), BLAZE_INSIGHTS_VERSION );
            wp_register_script( 'blaze-insights', untrailingslashit( plugin_dir_url( BLAZE_INSIGHTS_PLUGIN_FILE ) ) . '/assets/js/blazeinsights.js', array( 'jquery', 'chart' ), BLAZE_INSIGHTS_VERSION );

            $dashboard_object = [ 
                "plugin_basename" => plugin_basename( BLAZE_INSIGHTS_PLUGIN_FILE ),
            ];
            wp_localize_script( 'blaze-insights', "dashboard_object", $dashboard_object );
        }

        public function load_blaze_insights_scripts($hook)
        {
            // Load only blaze-insights page
            if( $hook != 'toplevel_page_blaze-insights' ) {
                return;
            }
            wp_enqueue_style( 'blaze-insights' );
            wp_enqueue_script( 'chart' );
            wp_enqueue_script( 'chartjs-plugin-annotation' );
            wp_enqueue_script( 'blaze-insights' );

            remove_all_actions('admin_notices');
            add_action( 'wp_after_admin_bar_render', array( $this, 'admin_header' ) );
        }

        public function blaze_insights_get_report()
        {
            $fuzzy_date = sanitize_text_field( $_POST['fuzzy_date'] );
            $start_date = sanitize_text_field( $_POST['start_date'] );
            $end_date = sanitize_text_field( $_POST['end_date'] );

            // Validation: Check if the parameters are not empty
            if( empty($fuzzy_date) && empty($start_date) && empty($end_date) ) {
                echo json_encode( [ 'error' => 1, 'message' => 'Missing query for fetching reports' ] );
                wp_die();
            }

            $query = [];

            if ( $start_date == '' || $end_date == '' ) {
                $query['fuzzy-date'] = $fuzzy_date;
            } else {
                $query['start-date'] = $start_date;
                $query['end-date'] = $end_date;
            }

            $request = Blaze_Insights_API::get_instance()->get_report($query);

            echo json_encode( $request );
            wp_die();
        }

        public function update_blaze_insights_plugin()
        {
            Blaze_Insights_Update_Checker::handle_plugin_upgrade( plugin_basename( BLAZE_INSIGHTS_PLUGIN_FILE ) );
            wp_die();
        }
    }
    Blaze_Insights_Admin::get_instance();
}
