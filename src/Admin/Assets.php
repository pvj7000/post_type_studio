<?php

namespace PostTypeStudio\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Assets {
    public function enqueue(): void {
        $screen = get_current_screen();
        if ( ! $screen || strpos( $screen->base, 'post-type-studio' ) === false ) {
            return;
        }

        wp_enqueue_style( 'post-type-studio-admin', plugins_url( 'assets/css/admin.css', dirname( __DIR__ ) . '/../post-type-studio.php' ), [], '1.0.0' );
        wp_enqueue_script( 'post-type-studio-admin', plugins_url( 'assets/js/admin.js', dirname( __DIR__ ) . '/../post-type-studio.php' ), [ 'jquery' ], '1.0.0', true );
    }
}
