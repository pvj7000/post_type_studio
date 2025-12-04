<?php

namespace PostTypeStudio\Domain;

use PostTypeStudio\Domain\Model\PostType;
use PostTypeStudio\Persistence\OptionsRepository;
use PostTypeStudio\Utils\Sanitization;
use PostTypeStudio\Utils\Validation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PostTypeManager {
    private OptionsRepository $repository;

    public function __construct( OptionsRepository $repository ) {
        $this->repository = $repository;
        add_action( 'admin_post_post_type_studio_save_post_type', [ $this, 'handle_save' ] );
        add_action( 'admin_post_post_type_studio_delete_post_type', [ $this, 'handle_delete' ] );
    }

    public function register_post_types(): void {
        $config = apply_filters( 'post_type_studio_post_types', $this->repository->get_config()['post_types'] );

        foreach ( $config as $slug => $data ) {
            if ( post_type_exists( $slug ) ) {
                continue;
            }

            $post_type = new PostType( $data );
            $labels    = [
                'name'               => $post_type->plural_label,
                'singular_name'      => $post_type->singular_label,
                'add_new_item'       => sprintf( __( 'Add New %s', 'post-type-studio' ), $post_type->singular_label ),
                'edit_item'          => sprintf( __( 'Edit %s', 'post-type-studio' ), $post_type->singular_label ),
                'new_item'           => sprintf( __( 'New %s', 'post-type-studio' ), $post_type->singular_label ),
                'view_item'          => sprintf( __( 'View %s', 'post-type-studio' ), $post_type->singular_label ),
                'search_items'       => sprintf( __( 'Search %s', 'post-type-studio' ), $post_type->plural_label ),
                'not_found'          => __( 'No items found', 'post-type-studio' ),
                'not_found_in_trash' => __( 'No items found in Trash', 'post-type-studio' ),
            ];

            $args = apply_filters(
                'post_type_studio_post_type_args',
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
            wp_die( __( 'You are not allowed to do this action.', 'post-type-studio' ) );
        }

        check_admin_referer( 'post_type_studio_save_post_type' );

        $sanitized = Sanitization::sanitize_post_type( $_POST );
        $errors    = Validation::validate_post_type( $sanitized );

        $original_slug = sanitize_key( $_POST['original_slug'] ?? '' );

        if ( ! empty( $errors ) ) {
            wp_redirect( add_query_arg( [ 'page' => 'post-type-studio', 'tab' => 'post-types', 'errors' => urlencode( wp_json_encode( $errors ) ) ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $config = $this->repository->get_config();
        if ( $original_slug && $original_slug !== $sanitized['slug'] ) {
            unset( $config['post_types'][ $original_slug ] );
        }
        $config['post_types'][ $sanitized['slug'] ] = $sanitized;
        $this->repository->save_config( $config );

        do_action( 'post_type_studio_post_type_saved', $sanitized );

        wp_redirect( add_query_arg( [ 'page' => 'post-type-studio', 'tab' => 'post-types', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
        exit;
    }

    public function handle_delete(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You are not allowed to do this action.', 'post-type-studio' ) );
        }

        check_admin_referer( 'post_type_studio_delete_post_type' );

        $slug = sanitize_key( $_POST['slug'] ?? '' );
        if ( empty( $slug ) ) {
            wp_redirect( add_query_arg( [ 'page' => 'post-type-studio', 'tab' => 'post-types' ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $config = $this->repository->get_config();
        unset( $config['post_types'][ $slug ] );
        $this->repository->save_config( $config );

        do_action( 'post_type_studio_post_type_deleted', $slug );

        wp_redirect( add_query_arg( [ 'page' => 'post-type-studio', 'tab' => 'post-types', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
        exit;
    }
}
