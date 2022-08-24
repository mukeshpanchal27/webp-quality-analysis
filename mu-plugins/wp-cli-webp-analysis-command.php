<?php
/**
 * Plugin Name: WebP Analysis Command
 * Description: A WP CLI command to run WebP analysis
 * Version: 0.1
*/

use function WP_CLI\Utils\format_items;

/**
 * WebP Analysis Command.
 */
class Webp_Analysis_Command {

    function run( $args, $assoc_args ) {

        $query_args = [
            'post_type' => 'attachment',
            'post_status' => 'any',
            'fields'    => 'ids',
            'posts_per_page' => 5,
        ];

        $query = new WP_Query( $query_args );


        // var_dump( $query ); die();

        // Create a rows array
        $items = array();

        foreach ( $query->posts as $post_id ) {
            $metadata = wp_get_attachment_metadata( $post_id );


            foreach ( $metadata['sizes'] as $size => $data ) {
                $items[] = array(
                    'ID'             => $post_id,
                    'filename'       => $data['file'],
                    'size'           => $size,
                    'width'          => $data['width'],
                    'height'         => $data['height'],
                    'jpeg_filesize'  => $data['sources']['image/jpeg']['filesize'],
                    'webp_filesize'  => $data['sources']['image/webp']['filesize'],
                    'is_webp_larger' => ( $data['sources']['image/jpeg']['filesize'] < $data['sources']['image/webp']['filesize'] ),
                );
            }
        }

        // Organise metadata into rows.
       format_items( 'table', $items, array_keys( $items[0] ) );
    }
}

if ( class_exists( 'WP_CLI' ) ) {
    WP_CLI::add_command( 'webp-analysis', 'Webp_Analysis_Command' );
}


