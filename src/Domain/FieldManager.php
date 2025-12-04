<?php

namespace PostTypeStudio\Domain;

use PostTypeStudio\Domain\Model\FieldGroup;
use PostTypeStudio\Persistence\OptionsRepository;
use PostTypeStudio\Utils\Sanitization;
use PostTypeStudio\Utils\Validation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class FieldManager {
    private OptionsRepository $repository;

    public function __construct( OptionsRepository $repository ) {
        $this->repository = $repository;
        add_action( 'admin_post_post_type_studio_save_field_group', [ $this, 'handle_save' ] );
        add_action( 'admin_post_post_type_studio_delete_field_group', [ $this, 'handle_delete' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_meta' ], 10, 2 );
    }

    public function register_fields(): void {
        $groups = apply_filters( 'post_type_studio_field_groups', $this->repository->get_config()['field_groups'] );

        foreach ( $groups as $group_id => $data ) {
            $group = new FieldGroup( $data );
            foreach ( $group->locations as $post_type ) {
                foreach ( $group->fields as $field ) {
                    register_post_meta(
                        $post_type,
                        $field['name'],
                        [
                            'show_in_rest'  => true,
                            'single'        => true,
                            'type'          => 'string',
                            'auth_callback' => static function() {
                                return current_user_can( 'edit_posts' );
                            },
                            'default'       => $field['default_value'] ?? '',
                        ]
                    );
                }
            }
        }
    }

    public function handle_save(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You are not allowed to do this action.', 'post-type-studio' ) );
        }

        check_admin_referer( 'post_type_studio_save_field_group' );

        $sanitized = Sanitization::sanitize_field_group( $_POST );
        $errors    = Validation::validate_field_group( $sanitized );
        $original_group_id = sanitize_key( $_POST['original_group_id'] ?? '' );

        if ( ! empty( $errors ) ) {
            wp_redirect( add_query_arg( [ 'page' => 'post-type-studio-fields', 'errors' => urlencode( wp_json_encode( $errors ) ) ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $config = $this->repository->get_config();
        if ( $original_group_id && $original_group_id !== $sanitized['group_id'] ) {
            unset( $config['field_groups'][ $original_group_id ] );
        }
        $config['field_groups'][ $sanitized['group_id'] ] = $sanitized;
        $this->repository->save_config( $config );

        do_action( 'post_type_studio_field_group_saved', $sanitized );

        wp_redirect( add_query_arg( [ 'page' => 'post-type-studio-fields', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
        exit;
    }

    public function handle_delete(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You are not allowed to do this action.', 'post-type-studio' ) );
        }

        check_admin_referer( 'post_type_studio_delete_field_group' );

        $group_id = sanitize_key( $_POST['group_id'] ?? '' );
        if ( empty( $group_id ) ) {
            wp_redirect( add_query_arg( [ 'page' => 'post-type-studio-fields' ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $config = $this->repository->get_config();
        unset( $config['field_groups'][ $group_id ] );
        $this->repository->save_config( $config );

        do_action( 'post_type_studio_field_group_deleted', $group_id );

        wp_redirect( add_query_arg( [ 'page' => 'post-type-studio-fields', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
        exit;
    }

    public function add_meta_boxes(): void {
        $groups = $this->repository->get_config()['field_groups'];
        foreach ( $groups as $data ) {
            $group = new FieldGroup( $data );
            foreach ( $group->locations as $post_type ) {
                add_meta_box(
                    'post_type_studio_' . $group->group_id,
                    esc_html( $group->title ),
                    function( $post ) use ( $group ) {
                        wp_nonce_field( 'post_type_studio_fields_' . $group->group_id, 'post_type_studio_fields_nonce_' . $group->group_id );
                        echo '<div class="post-type-studio-metabox">';
                        foreach ( $group->fields as $field ) {
                            $value = get_post_meta( $post->ID, $field['name'], true );
                            $value = $value !== '' ? $value : ( $field['default_value'] ?? '' );
                            echo '<div class="post-type-studio-field">';
                            echo '<label for="' . esc_attr( $field['name'] ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label>';
                            echo '<p class="description">' . esc_html( $field['instructions'] ?? '' ) . '</p>';
                            $this->render_input( $field, $value );
                            echo '</div>';
                        }
                        echo '</div>';
                    },
                    $post_type,
                    'normal',
                    'default'
                );
            }
        }
    }

    private function render_input( array $field, $value ): void {
        $name = esc_attr( $field['name'] );
        $type = $field['type'] ?? 'text';

        switch ( $type ) {
            case 'textarea':
                echo '<textarea name="' . $name . '" id="' . $name . '" class="widefat" rows="3">' . esc_textarea( $value ) . '</textarea>';
                break;
            case 'select':
                echo '<select name="' . $name . '" id="' . $name . '" class="widefat">';
                foreach ( $field['choices'] ?? [] as $choice ) {
                    $selected = selected( $value, $choice['value'], false );
                    echo '<option value="' . esc_attr( $choice['value'] ) . '" ' . $selected . '>' . esc_html( $choice['label'] ) . '</option>';
                }
                echo '</select>';
                break;
            case 'checkbox':
                $checked = checked( ! empty( $value ), true, false );
                echo '<label><input type="checkbox" name="' . $name . '" value="1" ' . $checked . ' /> ' . esc_html__( 'Required', 'post-type-studio' ) . '</label>';
                break;
            default:
                echo '<input type="text" name="' . $name . '" id="' . $name . '" class="widefat" value="' . esc_attr( $value ) . '" />';
                break;
        }
    }

    public function save_meta( int $post_id, $post ): void {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $groups = $this->repository->get_config()['field_groups'];
        foreach ( $groups as $data ) {
            $group = new FieldGroup( $data );
            if ( ! in_array( $post->post_type, $group->locations, true ) ) {
                continue;
            }

            $nonce_name = 'post_type_studio_fields_nonce_' . $group->group_id;
            if ( ! isset( $_POST[ $nonce_name ] ) || ! wp_verify_nonce( $_POST[ $nonce_name ], 'post_type_studio_fields_' . $group->group_id ) ) {
                continue;
            }

            foreach ( $group->fields as $field ) {
                $value = $_POST[ $field['name'] ] ?? '';
                $value = is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : sanitize_text_field( $value );
                update_post_meta( $post_id, $field['name'], $value );
            }
        }
    }
}
