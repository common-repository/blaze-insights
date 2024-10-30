<?php

if( !class_exists('Blaze_Insights_API')) {
    class Blaze_Insights_API
    {
        /**
         * Blaze_Insights_API Object
         *
         * @var Blaze_Insights_API
         */
        private static $insntance = null;

        /**
         * Blaze insights API base URL
         *
         * @var string
         */
        // protected $endpoint = 'https://insights-api-stage.herokuapp.com';
        protected $endpoint;

        /**
         * Client object
         *
         * @var GuzzleHttp\Client
         */
        protected $client = null;

        /**
         * Auth object
         */
        protected $token = null;

        public static function get_instance()
        {
            if(self::$insntance == null) {
                self::$insntance = new self;
            }

            return self::$insntance;
        }

        public function __construct()
        {
            if ( defined( 'BLAZE_INSIGHTS_API_ENDPOINT' ) ) {
                $this->endpoint = BLAZE_INSIGHTS_API_ENDPOINT;
                $this->token = Blaze_Insights_Helpers::getAuthCredentials();
            }
        }

        public function generate_credentials()
        {
            $options = [ 
                "siteURL"   => site_url(),
                "emailAddress" => sanitize_option( 'admin_email', get_option("admin_email") )
            ];
            $response = $this->post('clients', $options);
            return $this->generate_response($response);
        }

        public function authenticate($redirectUrl)
        {
            $options = [ 
                "redirectURL"   => $redirectUrl,
            ];
            $response = $this->post('google/auth/url', $options);
            if( is_wp_error( $response ) ) {
                return $this->generate_error( $response );
            }
            return $this->generate_response($response);
        }
        
        public function get_google_views()
        {
            $response = $this->get('google/views');
            return $this->generate_response($response);
        }

        public function get_current_google_view()
        {
            $response = $this->get('google/views/current');
            return $this->generate_response($response);
        }

        public function set_google_view($data)
        {
            $response = $this->put('google/views/current', $data);
            return $this->generate_response($response);
        }

        public function get_report($query)
        {
            $response = $this->get('google/reports?' . http_build_query($query));
            return $this->generate_response($response);
        }

        public function revoke_google($auth)
        {
            $response = $this->post('google/auth/revoke', []);
            return $this->generate_response($response);
        }

        public function generate_response($response)
        {
            $response_json = json_decode($response, true);
            $response_json['success'] = 1;
            return $response_json;
            // $response_json = json_decode((string) $response->getBody(), true);
            // if($response->getStatusCode() == 200) {
            //     $response_json['success'] = 1;
            //     return $response_json;
            // } else {
            //     $response_json['success'] = 0;
            //     return $response_json;
            // }
        }

        public function generate_error($response)
        {
            $response_json = [
                "error" => 1, 
                "message" => $response->get_error_message() 
            ];
            return $response_json;
        }

        public function get_contact_email()
        {
            $response = $this->get('google/users');
            return $this->generate_response($response);
        }

        public function set_industry($contact_email, $industryVertical)
        {
            $response = $this->patch('google/views/current', [ "contactEmail" => $contact_email, "industryVertical" => $industryVertical ]);
            return $this->generate_response($response);
        }

        public function get( $path )
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->endpoint . $path,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic ' . $this->token
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }

        public function post( $path, $data )
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $this->token,
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            if ( curl_errno($curl) ) {
                $error_msg = curl_error( $curl );
                return new WP_Error( 'error', __( $error_msg, "blaze-insights" ) );
            }

            curl_close($curl);
            return $response;
        }

        public function put( $path, $data )
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $this->token,
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }

        public function patch( $path, $data )
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $this->token,
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }
    }
    Blaze_Insights_API::get_instance();
}