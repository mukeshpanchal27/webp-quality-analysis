<?php
/**
 * WP-CLI command to import the images from unsplash API.
 * 
 * @since 1.0.0
 * @package webp-quality-analysis
 */

namespace WebPQualityAnalysis\Import;

use WP_CLI;


// Fire up everything, only if we are in WP-CLI mode.
if ( !defined( 'WP_CLI' ) || !WP_CLI ) {
    // Then we don't want to load the plugin
    return;
}

/**
 * Download an image from the unsplash URL.
 *
 * @param string $url Image download URL.
 * @return int|WP_Error
 */
function download_image( $url = '', $name = '' ) {
    if ( empty( $url ) ) {
        WP_CLI::error( 'Provide the image URL for download.' );
    }

    if ( empty( $name ) ) {
        $name = time();
    }

    $download_url = download_url( $url );

    $file_array = [
        'name' => sprintf( '%s.jpg', $name ),
        'tmp_name' => $download_url,
    ];

    // If there is any error, display and exit.
    if ( ! empty( $uploaded_file['error'] ) ) {
        WP_CLI::error( $uploaded_file['error'] );
    }

    $attachment_id = media_handle_sideload(
        $file_array,
        0,
        null,
        []
    );

    return $attachment_id;
}

/**
 * Import the images from unsplash API.
 *
 * @return void
 */
function webp_analysis_import_images( $args, $assoc_args ) {
    $endpoint = 'https://api.unsplash.com';
    $search_photos_path = '/search/photos';
    $list_photos_path = '/photos';
    $access_key = 'z3pOnmimIPdiUn8qdr31nPuIFArkLKbz4sfVTw9sXno';

    if ( empty( $endpoint ) ) {
        WP_CLI::error( 'Unsplash endpoint is not configured.' );
    }

    $endpoint     = untrailingslashit( $endpoint );
    $route        = $list_photos_path;
    $query_params = [
        'client_id' => $access_key,
        'fm'        => 'jpg',
    ];


    // Set the query parameter.
    if( !empty( $assoc_args['query'] ) ) {
        $query = esc_attr( $assoc_args['query'] );
        $route = $search_photos_path;

        $query_params['query'] = (string) $assoc_args['query'];
    }

    // Set the quality.
    if( !empty( $assoc_args['quality'] ) ) {
        $query = esc_attr( $assoc_args['quality'] );
        $route = $search_photos_path;

        $query_params['q'] = (int) $assoc_args['quality'];
    }

    // Set the width.
    if( !empty( $assoc_args['width'] ) ) {
        $query = esc_attr( $assoc_args['width'] );
        $route = $search_photos_path;

        $query_params['w'] = (int) $assoc_args['width'];
    }
    
    // Set the Height.
    if( !empty( $assoc_args['height'] ) ) {
        $query = esc_attr( $assoc_args['height'] );
        $route = $search_photos_path;

        $query_params['h'] = (int) $assoc_args['height'];
    }    

    // Set the number of images to fetch.
    if( !empty( $assoc_args['number'] ) ) {
        $query = esc_attr( $assoc_args['number'] );
        $route = $search_photos_path;

        $query_params['per_page'] = (int) $assoc_args['number'];
    }

    // Set the page number.
    if( !empty( $assoc_args['page'] ) ) {
        $query = esc_attr( $assoc_args['page'] );
        $route = $search_photos_path;

        $query_params['page'] = (int) $assoc_args['page'];
    }

    $url = add_query_arg(
        $query_params,
        $endpoint . $route
    );

    $response = wp_remote_get( $url );
    $response_code = wp_remote_retrieve_response_code( $response );

    if ( 200 !== $response_code ) {
        WP_CLI::error( 'Something went wrong, please try again.' );
    }

    $data = wp_remote_retrieve_body( $response );
    $data = json_decode( $data );
    $images_data = (array) $data->results;

    foreach( $images_data as $image_data ) {
        $url = $image_data->links->download;
        $id = download_image( $url, $image_data->id );
        
        // If there is any error, display and exit.
        if ( is_wp_error( $id ) ) {
            WP_CLI::error( sprintf( 'Error while creating attachment with URL: %s', $url  ) );
        }

        WP_CLI::success( sprintf( 'Attachment %d has been created', $id ) );
    }
}
WP_CLI::add_command( 'media unsplash', __NAMESPACE__ . '\webp_analysis_import_images' );
