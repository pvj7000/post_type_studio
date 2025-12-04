<?php

namespace PostTypeStudio\Admin\Screens;

use PostTypeStudio\Persistence\OptionsRepository;
use PostTypeStudio\Utils\PostTypeRegistry;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TaxonomiesScreen {
    private OptionsRepository $repository;

    public function __construct( OptionsRepository $repository ) {
        $this->repository = $repository;
    }

    public function render(): void {
        $config                = $this->repository->get_config();
        $taxonomies            = $config['taxonomies'];
        $post_types            = PostTypeRegistry::get_available_post_types( $config['post_types'] );
        $editing_slug          = isset( $_GET['edit'] ) ? sanitize_key( wp_unslash( $_GET['edit'] ) ) : '';
        $editing_taxonomy      = $taxonomies[ $editing_slug ] ?? null;
        $errors                = [];
        $error_query = $_GET['errors'] ?? '';
        if ( $error_query ) {
            $errors = json_decode( wp_unslash( $error_query ), true );
        }

        $updated = ! empty( $_GET['updated'] );
        $views   = dirname( __DIR__, 3 ) . '/views/';

        include $views . 'admin-header.php';
        include $views . 'taxonomies-list.php';
        include $views . 'taxonomy-edit.php';
    }
}
