<?php

namespace PostTypeStudio\Admin\Screens;

use PostTypeStudio\Persistence\OptionsRepository;
use PostTypeStudio\Utils\PostTypeRegistry;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PostTypesScreen {
    private OptionsRepository $repository;

    public function __construct( OptionsRepository $repository ) {
        $this->repository = $repository;
    }

    public function render(): void {
        $config              = $this->repository->get_config();
        $post_types          = PostTypeRegistry::get_available_post_types( $config['post_types'] );
        $managed_post_types  = $config['post_types'];
        $editing_slug        = isset( $_GET['edit'] ) ? sanitize_key( wp_unslash( $_GET['edit'] ) ) : '';
        $editing_post_type   = $managed_post_types[ $editing_slug ] ?? null;
        $errors              = [];
        $error_query = $_GET['errors'] ?? '';
        if ( $error_query ) {
            $errors = json_decode( wp_unslash( $error_query ), true );
        }

        $updated = ! empty( $_GET['updated'] );
        $views   = dirname( __DIR__, 3 ) . '/views/';

        include $views . 'admin-header.php';
        include $views . 'post-types-list.php';
        include $views . 'post-type-edit.php';
    }
}
