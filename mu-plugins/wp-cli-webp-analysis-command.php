<?php
/**
 * Plugin Name: WebP Analysis Command
 * Description: A WP CLI command to run WebP analysis
 * Version: 0.1
*/

use function WP_CLI\Utils\format_items;
use function \WP_CLI\Utils\write_csv;

/**
 * WebP Analysis Command.
 */
class Webp_Analysis_Command {

    /**
     * Runs the WebP Quality Analysis
     *
     * ## OPTIONS
     *
     * [--limit=<limit>]
     * : Set the total number of attachments to analyse
     * ---
     * default: 500
     * ---
     *
     * [--preview]
     * : Preview results as an ASCII table
     *
     * ## EXAMPLES
     *
     *     wp webp-analysis run --limit=10
     *
     * @when after_wp_load
     */
    function run( $args, $assoc_args ) {
        // Preview results in an ASCII table.
        $preview = isset( $assoc_args['preview'] ) ? true : false;

        // Set the correct limit. When previewing results limit total attachments to 5 otherwise use the limit passed.
        $limit = $preview ? min( (int) $assoc_args['limit'], 5 ) : (int) $assoc_args['limit'];

        // Create WP_Query to retrieve attachment IDs.
        $query_args = [
            'post_type'      => 'attachment',
            'post_status'    => 'any',
            'fields'         => 'ids',
            'posts_per_page' => $limit,
        ];

        $query = new WP_Query( $query_args );

        if ( empty( $query->posts ) ) {
            WP_CLI::error( __( 'No attachments found.') );
        }

        WP_CLI::line( sprintf( __( '%d attachments found.', 'webp-quality-analysis' ), count( $query->posts ) ) );

        // Create a items array.
        $items = array();

        foreach ( $query->posts as $post_id ) {
            $metadata = wp_get_attachment_metadata( $post_id );

            if ( ! isset( $metadata['sources']['image/webp'] ) ) {
                WP_CLI::error( sprintf( __( 'Skipping attachment %d, no WebP data found.', 'webp-quality-analysis' ), $post_id ), false );
                continue;
            }

            // Get the full size image data.
            $items[] = array(
                'ID'            => $post_id,
                'filename'      => $metadata['sources']['image/jpeg']['file'],
                'size'          => 'full',
                'width'         => $metadata['width'],
                'height'        => $metadata['height'],
                'jpeg_filesize' => $metadata['sources']['image/jpeg']['filesize'],
                'webp_filesize' => $metadata['sources']['image/webp']['filesize'],
                'larger_webp'   => ( $metadata['sources']['image/jpeg']['filesize'] < $metadata['sources']['image/webp']['filesize'] ) ? 1 : 0,
            );

            // Get image data for each sub size.
            foreach ( $metadata['sizes'] as $size => $data ) {
                $items[] = array(
                    'ID'            => $post_id,
                    'filename'      => $data['file'],
                    'size'          => $size,
                    'width'         => $data['width'],
                    'height'        => $data['height'],
                    'jpeg_filesize' => $data['sources']['image/jpeg']['filesize'],
                    'webp_filesize' => $data['sources']['image/webp']['filesize'],
                    'larger_webp'   => ( $data['sources']['image/jpeg']['filesize'] < $data['sources']['image/webp']['filesize'] ) ? 1 : 0,
                );
            }
        }

        if ( empty( $items ) ) {
            WP_CLI::error( __( 'No data collected.', 'webp-quality-analysis' ) );
        }

        // Display results in an ASCII table.
        if ( $preview ) {
            format_items( 'table', $items, array_keys( $items[0] ) );
            exit;
        }

        // Write results to CSV file with timestamp.
        $filename = plugin_dir_path( __FILE__ ) . "../exports/" . time() . "-webp-quality-analysis.csv";
        $file     = fopen( $filename, 'w' );
        write_csv( $file, $items, array_keys( $items[0] ) );

        WP_CLI::success( sprintf( __( 'WebP analysis export created: %s', 'webp-quality-analysis' ), wp_basename( $filename ) ) );
    }
}

if ( class_exists( 'WP_CLI' ) ) {
    WP_CLI::add_command( 'webp-analysis', 'Webp_Analysis_Command' );
}


