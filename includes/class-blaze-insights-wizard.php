<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists('Blaze_Insights_Wizard') ) {
    class Blaze_Insights_Wizard
    {
        private static $instance;

        public $wizard_url;
        public $dashboard_url;

        public static function get_instance()
        {
            if( !self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            $this->wizard_url = admin_url() . "admin.php?page=blaze-insights-wizard";
            $this->dashboard_url = admin_url() . "admin.php?page=blaze-insights";

            add_action( 'admin_init', array( $this, 'show_wizard') );
            add_action( 'admin_enqueue_scripts', array( $this, 'register_wizard_scripts') );
            add_action( 'admin_enqueue_scripts', array( $this, 'load_wizard_styles') );

            add_action( 'wp_ajax_skip_wizard', array( $this, 'skip_wizard' ) );
            add_action( 'wp_ajax_nopriv_skip_wizard', array( $this, 'skip_wizard' ) );
            add_action( 'wp_ajax_blaze_insights_google_authentication', array( $this, 'google_authentication' ) );
            add_action( 'wp_ajax_nopriv_blaze_insights_google_authentication', array( $this, 'google_authentication' ) );
            add_action( 'wp_ajax_get_google_views', array( $this, 'get_google_views' ) );
            add_action( 'wp_ajax_nopriv_get_google_views', array( $this, 'get_google_views' ) );
            add_action( 'wp_ajax_set_google_view', array( $this, 'set_google_view' ) );
            add_action( 'wp_ajax_nopriv_set_google_view', array( $this, 'set_google_view' ) );
            add_action( 'wp_ajax_set_industry', array( $this, 'set_industry' ) );
            add_action( 'wp_ajax_nopriv_set_industry', array( $this, 'set_industry' ) );
            add_action( 'wp_ajax_get_selected_industry_vertical', array( $this, 'get_selected_industry_vertical' ) );
            add_action( 'wp_ajax_nopriv_get_selected_industry_vertical', array( $this, 'get_selected_industry_vertical' ) );
        }

        public function show_wizard()
        {
            if( wp_doing_ajax() ) {
                return false;
            }
            if( !Blaze_Insights_Helpers::is_wizard_user_cookie() || Blaze_Insights_Helpers::is_wizard_process_done() || Blaze_Insights_Helpers::is_wizard_process_skipped() || Blaze_Insights_Helpers::isWizardPage() ) {
                return false;
            } else {
                wp_redirect( $this->wizard_url );
            }
        }

        public function wizard_cotnent()
        {
            // change this to a better way insted of requiring
            require_once Blaze_Insights::locate_blaze_insights_template('wizard/wizard.php');
        }

        public function register_wizard_scripts()
        {
            wp_register_style( 'blaze-insights-wizard', untrailingslashit( plugin_dir_url( BLAZE_INSIGHTS_PLUGIN_FILE ) ) . '/assets/css/wizard.css', array(), BLAZE_INSIGHTS_VERSION );
            wp_register_script( 'blaze-insights-wizard', untrailingslashit( plugin_dir_url( BLAZE_INSIGHTS_PLUGIN_FILE ) ) . '/assets/js/wizard.js', array( 'jquery' ), BLAZE_INSIGHTS_VERSION );

            $wizard_object = [ 
                "admin_url" => admin_url(),
                "blaze_insights_dashboard" => $this->dashboard_url,
            ];
            wp_localize_script( 'blaze-insights-wizard', "wizard_object", $wizard_object );
        }

        public function load_wizard_styles($hook)
        {
            // Load only blaze-insights-wizard page
            if( $hook != 'blaze-insights_page_blaze-insights-wizard' ) {
                return;
            }
            wp_enqueue_style( 'blaze-insights-wizard' );
            wp_enqueue_script( 'blaze-insights-wizard' );
        }

        public function skip_wizard()
        {
            update_option( 'blaze_insights_wizard_skip', true);
            echo json_encode( [ 'success' => 1, 'message' => 'Wizard skipped.'] );
            wp_die();
        }

        public function google_authentication()
        {
            $request = Blaze_Insights_API::get_instance()->authenticate( $this->wizard_url . "&step=2");

            if( $request['error'] == 1 ) {
                echo json_encode( $request );
            } else {
                echo json_encode( [ 'success' => 1, 'url' => $request["authURL"] ] );
            } 
            wp_die();
        }

        public function get_google_views()
        {
            $request = Blaze_Insights_API::get_instance()->get_google_views();

            update_option( 'blaze_insights_google_views', $request['views'] );

            echo json_encode( $request );
            wp_die();
        }

        public function set_google_view()
        {
            // Validation: Check if the parameters are not empty
            if( !isset($_POST['view']) ) {
                echo json_encode( [ 'error' => 1, 'message' => 'No view object found.' ] );
                wp_die();
            }

            $accountId = sanitize_text_field( $_POST['view']["accountId"] );
            $webPropertyId = sanitize_text_field( $_POST['view']["webPropertyId"] );
            $viewId = sanitize_text_field( $_POST['view']["viewId"] );

            // Validation: Check if the parameters are not empty
            if( empty($accountId) && empty($webPropertyId) && empty($viewId) ) {
                echo json_encode( [ 'error' => 1, 'message' => 'Analytics view data incorrect.' ] );
                wp_die();
            }

            $request = Blaze_Insights_API::get_instance()->set_google_view([
                "accountId" => $accountId,
                "webPropertyId" => $webPropertyId,
                "viewId" => $viewId,
            ]);

            if($request["success"] == 1) {
                update_option( 'blaze_insights_selected_view', $viewId );
            }

            echo json_encode( $request );
            wp_die();
        }

        public function get_contact_email()
        {
            $request = Blaze_Insights_API::get_instance()->get_contact_email();

            echo json_encode( $request );
            wp_die();
        }

        public function set_industry()
        {
            // Validate
            if( !isset($_POST['contact_email'])  ) {
                echo json_encode( [ 'error' => 1, 'message' => 'Contact Email is required' ] );
                wp_die();
            }

            // Sanitize
            $contact_email = sanitize_email( $_POST["contact_email"] );
            $industry = sanitize_text_field( $_POST["industry"] );

            $request = Blaze_Insights_API::get_instance()->set_industry($contact_email, $industry);

            if($request["success"] == 1) {
                update_option( 'blaze_insights_wizard_complete', true );
                Blaze_Insights_Helpers::remove_setup_cookie();
            }

            echo json_encode( $request );
            wp_die();
        }

        public function get_selected_industry_vertical()
        {
            $request = Blaze_Insights_API::get_instance()->get_current_google_view();
            ob_start();
            foreach ($this->industry_vertical_options() as $industry): 
                $selected = false;
                if( isset( $request["industryVertical"] ) ) {
                    $selected = $industry["value"] == $request["industryVertical"];
                }
            ?>
                <option value="<?php _e( $industry["value"] ) ?>" <?php echo $selected ? "selected" : "" ?>><?php _e( $industry["label"] ) ?></option>
            <?php endforeach;
            $select_values = ob_get_contents();
            ob_end_clean();

            $contact_request = Blaze_Insights_API::get_instance()->get_contact_email();

            $contact_email = "";

            if( $contact_request["success"] == 1) {
                $contact_email = $contact_request["authEmail"];
            }
            echo json_encode([
                "industry_list_options" => $select_values,
                "contact_email" => $contact_email
            ]);
            wp_die();
        }

        public function industry_vertical_options()
        {
            return [
                [
                    "label" => "Select One",
                    "value" => "UNSPECIFIED"
                ],
                [
                    "label" => "Arts and Entertainment",
                    "value" => "ARTS_AND_ENTERTAINMENT"
                ],
                [
                    "label" => "Automotive",
                    "value" => "AUTOMOTIVE"
                ],
                [
                    "label" => "Beaty and Fitness",
                    "value" => "BEAUTY_AND_FITNESS"
                ],
                [
                    "label" => "Books and Literature",
                    "value" => "BOOKS_AND_LITERATURE"
                ],
                [
                    "label" => "Business and Industrial Markets",
                    "value" => "BUSINESS_AND_INDUSTRIAL_MARKETS"
                ],
                [
                    "label" => "Computers and Electronics",
                    "value" => "COMPUTERS_AND_ELECTRONICS"
                ],
                [
                    "label" => "Finance",
                    "value" => "FINANCE"
                ],
                [
                    "label" => "Food and Drink",
                    "value" => "FOOD_AND_DRINK"
                ],
                [
                    "label" => "Games",
                    "value" => "GAMES"
                ],
                [
                    "label" => "Healthcare",
                    "value" => "HEALTHCARE"
                ],
                [
                    "label" => "Hobbies and Leisure",
                    "value" => "HOBBIES_AND_LEISURE"
                ],
                [
                    "label" => "Home and Garden",
                    "value" => "HOME_AND_GARDEN"
                ],
                [
                    "label" => "Internet and Telecom",
                    "value" => "INTERNET_AND_TELECOM"
                ],
                [
                    "label" => "Jobs and Education",
                    "value" => "JOBS_AND_EDUCATION"
                ],
                [
                    "label" => "Law and Government",
                    "value" => "LAW_AND_GOVERNMENT"
                ],
                [
                    "label" => "News",
                    "value" => "NEWS"
                ],
                [
                    "label" => "Online Communities",
                    "value" => "ONLINE_COMMUNITIES"
                ],
                [
                    "label" => "People and Society",
                    "value" => "PEOPLE_AND_SOCIETY"
                ],
                [
                    "label" => "Pets and Animals",
                    "value" => "PETS_AND_ANIMALS"
                ],
                [
                    "label" => "Real Estate",
                    "value" => "REAL_ESTATE"
                ],
                [
                    "label" => "Reference",
                    "value" => "REFERENCE"
                ],
                [
                    "label" => "Science",
                    "value" => "SCIENCE"
                ],
                [
                    "label" => "Shopping",
                    "value" => "SHOPPING"
                ],
                [
                    "label" => "Sports",
                    "value" => "SPORTS"
                ],
                [
                    "label" => "Travel",
                    "value" => "TRAVEL"
                ],
                [
                    "label" => "Other",
                    "value" => "OTHER"
                ],
            ];
        }
    }
    Blaze_Insights_Wizard::get_instance();
}
