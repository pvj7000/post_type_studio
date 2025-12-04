<?php

namespace MyCptUi\Domain;

use MyCptUi\Domain\Model\PostType;
use MyCptUi\Persistence\OptionsRepository;
use MyCptUi\Utils\Sanitization;
use MyCptUi\Utils\Validation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PostTypeManager {
    private OptionsRepository $repository;

    public function __construct( OptionsRepository $repository ) {
        $this->repository = $repository;
        add_action( 'admin_post_my_cpt_ui_save_post_type', [ $this, 'handle_save' ] );
        add_action( 'admin_post_my_cpt_ui_delete_post_type', [ $this, 'handle_delete' ] );
    }

    public function register_post_types(): void {
        $config = apply_filters( 'my_cpt_ui_post_types', $this->repository->get_config()['post_types'] );

        foreach ( $config as $slug => $data ) {
            if ( post_type_exists( $slug ) ) {
                continue;
            }

            $post_type = new PostType( $data );
            $labels    = [
                'name'               => $post_type->plural_label,
                'singular_name'      => $post_type->singular_label,
                'add_new_item'       => sprintf( __( 'Add New %s', 'my-cpt-ui' ), $post_type->singular_label ),
                'edit_item'          => sprintf( __( 'Edit %s', 'my-cpt-ui' ), $post_type->singular_label ),
                'new_item'           => sprintf( __( 'New %s', 'my-cpt-ui' ), $post_type->singular_label ),
                'view_item'          => sprintf( __( 'View %s', 'my-cpt-ui' ), $post_type->singular_label ),
                'search_items'       => sprintf( __( 'Search %s', 'my-cpt-ui' ), $post_type->plural_label ),
                'not_found'          => __( 'No items found', 'my-cpt-ui' ),
                'not_found_in_trash' => __( 'No items found in Trash', 'my-cpt-ui' ),
            ];

            $args = apply_filters(
                'my_cpt_ui_post_type_args',
                [
                    'label'           => $post_type->plural_label,
                    'labels'          => $labels,
                    'public'          => $post_type->public,
                    'show_ui'         => $post_type->show_ui,
                    'show_in_rest'    => $post_type->show_in_rest,
                    'rest_base'       => $post_type->slug,
                    'rest_namespace'  => 'wp/v2',
                    'has_archive'     => $post_type->has_archive,
                    'hierarchical'    => $post_type->hierarchical,
                    'supports'        => $post_type->supports,
                    'menu_icon'       => $post_type->menu_icon,
                    'menu_position'   => $post_type->menu_position,
                    'capability_type' => $post_type->capability_type,
                ],
                $post_type
            );

            register_post_type( $post_type->slug, $args );
        }
    }

    public function handle_save(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You are not allowed to do this action.', 'my-cpt-ui' ) );
        }

        check_admin_referer( 'my_cpt_ui_save_post_type' );

        $sanitized = Sanitization::sanitize_post_type( $_POST );
        $errors    = Validation::validate_post_type( $sanitized );

        $original_slug = sanitize_key( $_POST['original_slug'] ?? '' );

        if ( ! empty( $errors ) ) {
            wp_redirect( add_query_arg( [ 'page' => 'my-cpt-ui', 'tab' => 'post-types', 'errors' => urlencode( wp_json_encode( $errors ) ) ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $config = $this->repository->get_config();
        if ( $original_slug && $original_slug !== $sanitized['slug'] ) {
            unset( $config['post_types'][ $original_slug ] );
        }
        $config['post_types'][ $sanitized['slug'] ] = $sanitized;
        $this->repository->save_config( $config );

        do_action( 'my_cpt_ui_post_type_saved', $sanitized );

        wp_redirect( add_query_arg( [ 'page' => 'my-cpt-ui', 'tab' => 'post-types', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
        exit;
    }

    public function handle_delete(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You are not allowed to do this action.', 'my-cpt-ui' ) );
        }

        check_admin_referer( 'my_cpt_ui_delete_post_type' );

        $slug = sanitize_key( $_POST['slug'] ?? '' );
        if ( empty( $slug ) ) {
            wp_redirect( add_query_arg( [ 'page' => 'my-cpt-ui', 'tab' => 'post-types' ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $config = $this->repository->get_config();
        unset( $config['post_types'][ $slug ] );
        $this->repository->save_config( $config );

        do_action( 'my_cpt_ui_post_type_deleted', $slug );

        wp_redirect( add_query_arg( [ 'page' => 'my-cpt-ui', 'tab' => 'post-types', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
        exit;
    }
}
