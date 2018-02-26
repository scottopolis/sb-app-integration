<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'SB_WPAPI_Mods' ) ) {

    class SB_WPAPI_Mods {

    	private static $instance;

		public static function instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
				self::$instance->hooks();
			}

			return self::$instance;
		}

		public function hooks() {

			add_action( 'rest_api_init', array( $this, 'add_featured_urls' ) );
		}

		/***
		* Add featured image urls to post response.
		* Sample usage in the app files would be data.featured_image_urls.thumbnail
		***/
		public function add_featured_urls() {
			register_rest_field( 'post',
			    'featured_image_urls',
			    array(
			        'get_callback'    => array( $this, 'image_sizes' ),
			        'update_callback' => null,
		            'schema'          => null,
			    )
			);
		}

		public function image_sizes( $post ) {

		    $featured_id = get_post_thumbnail_id( $post['id'] );

			$sizes = wp_get_attachment_metadata( $featured_id );

			$size_data = new stdClass();
					
			if ( ! empty( $sizes['sizes'] ) ) {

				foreach ( $sizes['sizes'] as $key => $size ) {
					// Use the same method image_downsize() does
					$image_src = wp_get_attachment_image_src( $featured_id, $key );

					if ( ! $image_src ) {
						continue;
					}
					
					$size_data->$key = $image_src[0];
					
				}

			}

			return $size_data;
		    
		}

	}

	$SB_WPAPI_Mods = new SB_WPAPI_Mods();
    $SB_WPAPI_Mods->instance();

} // End if class_exists check