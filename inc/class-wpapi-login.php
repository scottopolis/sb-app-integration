<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'SB_App_Login' ) ) {

    class SB_App_Login {

    	private static $instance;

		public static function instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
				self::$instance->hooks();
			}

			return self::$instance;
		}

		public function hooks() {

			add_action( 'rest_api_init', array( $this, 'login_endpoint' ) );
		}

		public function login_endpoint() {

			register_rest_route( 'app/v1', '/login', array(
			    'methods' => 'POST',
			    'callback' => array( $this, 'app_login' ),
			  ) );

		}

		/**
		 * Login 
		 */
		public function app_login() {

			@header( 'Access-Control-Allow-Origin: *' );

			// TODO: you should probably make this a token and verify using a hash
			if( empty( $_POST['security'] ) || $_POST['security'] != 'my-secure-phrase' ) {
				wp_send_json_error( array(
					'message' =>  'You are not allowed to do that.' ) );
			}

			if( isset( $_POST['logout'] ) && $_POST['logout'] == "true" ) {
				wp_logout();

				wp_send_json_success( array( 'message' => 'Successfully logged out.', 'logout' => true ) );
			}

			$info = array();

			// this handles auth headers if you want, but not all servers support them
			$info['user_login'] = ( $_POST['username'] ? $_POST['username'] : $_SERVER['PHP_AUTH_USER'] );
			$info['user_password'] = ( $_POST['password'] ? $_POST['password'] : $_SERVER['PHP_AUTH_PW'] );
			
			$info['remember'] = true;
			
			$user_signon = wp_signon( $info, false );
			
			if( is_wp_error( $user_signon ) ) {
			
				$return = array(
					'message' =>  'The log in you have entered is not valid.',
					'signon' => $info['user_login'],
					'line' => __LINE__,
					'success' => false
				);
				wp_send_json_error( $return );
				
			} else {

				$return = array(
					'message' => 'Login successful!',
					'username' => $info['user_login'],
					'success' => true
				);
					
				wp_send_json_success( $return );
				
			}
		}

		/**
		 * Logout, used via postmessage in AP3 apps
		 * @since 3.0.2
		 */
		public function app_logout() {

			@header( 'Access-Control-Allow-Origin: *' );

			wp_logout();

			$response = array(
				'message' => __('Logout success.', 'apppresser')
			);

			$redirect = $this->get_logout_redirect();
			if($redirect) {
				$response['logout_redirect'] = $redirect;
			}

			wp_send_json_success( $response );

		}

	}

	$SB_App_Login = new SB_App_Login();
    $SB_App_Login->instance();

} // End if class_exists check