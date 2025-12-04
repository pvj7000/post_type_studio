<?php

namespace PostTypeStudio\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Notices {
    public function success( string $message ): void {
        add_action( 'admin_notices', function() use ( $message ) {
            printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $message ) );
        } );
    }

    public function error( string $message ): void {
        add_action( 'admin_notices', function() use ( $message ) {
            printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $message ) );
        } );
    }
}
