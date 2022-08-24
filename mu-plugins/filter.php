<?php
    // Active WebP module only.
    add_filter(
        'perflab_active_modules',
        function() {
            return array( 'images/webp-uploads' );
        }
    );

    // Allow large WenP image.
    add_filter( 'webp_uploads_discard_larger_generated_images', '__return_false' );

    // Add missing WP core image sizes.
    add_filter(
        'webp_uploads_image_sizes_with_additional_mime_type_support',
        function( $sizes ) {
            $sizes['1536x1536'] = true;
            $sizes['2048x2048'] = true;
            return $sizes;
        }
    );

    // Use a quality setting of 82 for all mime type images.
    function filter_webp_quality() {
        return 82;
    }
    add_filter( 'wp_editor_set_quality', 'filter_webp_quality' );