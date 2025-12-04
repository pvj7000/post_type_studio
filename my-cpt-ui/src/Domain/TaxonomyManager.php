<?php

namespace MyCptUi\Domain;

use MyCptUi\Domain\Model\Taxonomy;
use MyCptUi\Persistence\OptionsRepository;
use MyCptUi\Utils\Sanitization;
use MyCptUi\Utils\Validation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TaxonomyManager {
    private OptionsRepository $repository;

    public function __construct( OptionsRepository $repository ) {
        $this->repository = $repository;
        add_action( 'admin_post_my_cpt_ui_save_taxonomy', [ $this, 'handle_save' ] );
        add_action( 'admin_post_my_cpt_ui_delete_taxonomy', [ $this, 'handle_delete' ] );
    }

    public function register_taxonomies(): void {
        $config = apply_filters( 'my_cpt_ui_taxonomies', $this->repository->get_config()['taxonomies'] );

        foreach ( $config as $slug => $data ) {
            if ( taxonomy_exists( $slug ) ) {
                continue;
            }

            $taxonomy = new Taxonomy( $data );
            $labels   = [
                'name'          => $taxonomy->plural_label,
                'singular_name' => $taxonomy->singular_label,
                'search_items'  => sprintf( __( 'Search %s', 'my-cpt-ui' ), $taxonomy->plural_label ),
                'all_items'     => sprintf( __( 'All %s', 'my-cpt-ui' ), $taxonomy->plural_label ),
                'edit_item'     => sprintf( __( 'Edit %s', 'my-cpt-ui' ), $taxonomy->singular_label ),
                'update_item'   => sprintf( __( 'Update %s', 'my-cpt-ui' ), $taxonomy->singular_label ),
                'add_new_item'  => sprintf( __( 'Add New %s', 'my-cpt-ui' ), $taxonomy->singular_label ),
            ];

            $args = apply_filters(
                'my_cpt_ui_taxonomy_args',
                [
                    'labels'            => $labels,
                    'public'            => $taxonomy->public,
                    'show_ui'           => $taxonomy->show_ui,
                    'show_in_rest'      => $taxonomy->show_in_rest,
                    'hierarchical'      => $taxonomy->hierarchical,
                    'show_admin_column' => $taxonomy->show_admin_column,
                ],
                $taxonomy
            );

            register_taxonomy( $taxonomy->slug, $taxonomy->object_type, $args );
        }
    }

    public function handle_save(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You are not allowed to do this action.', 'my-cpt-ui' ) );
        }

        check_admin_referer( 'my_cpt_ui_save_taxonomy' );

        $sanitized = Sanitization::sanitize_taxonomy( $_POST );
        $errors    = Validation::validate_taxonomy( $sanitized );
        $original_slug = sanitize_key( $_POST['original_slug'] ?? '' );

        if ( ! empty( $errors ) ) {
            wp_redirect( add_query_arg( [ 'page' => 'my-cpt-ui-taxonomies', 'errors' => urlencode( wp_json_encode( $errors ) ) ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $config = $this->repository->get_config();
        if ( $original_slug && $original_slug !== $sanitized['slug'] ) {
            unset( $config['taxonomies'][ $original_slug ] );
        }
        $config['taxonomies'][ $sanitized['slug'] ] = $sanitized;
        $this->repository->save_config( $config );

        do_action( 'my_cpt_ui_taxonomy_saved', $sanitized );

        wp_redirect( add_query_arg( [ 'page' => 'my-cpt-ui-taxonomies', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
        exit;
    }

    public function handle_delete(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You are not allowed to do this action.', 'my-cpt-ui' ) );
        }

        check_admin_referer( 'my_cpt_ui_delete_taxonomy' );

        $slug = sanitize_key( $_POST['slug'] ?? '' );
        if ( empty( $slug ) ) {
            wp_redirect( add_query_arg( [ 'page' => 'my-cpt-ui-taxonomies' ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $config = $this->repository->get_config();
        unset( $config['taxonomies'][ $slug ] );
        $this->repository->save_config( $config );

        do_action( 'my_cpt_ui_taxonomy_deleted', $slug );

        wp_redirect( add_query_arg( [ 'page' => 'my-cpt-ui-taxonomies', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
        exit;
    }
}
