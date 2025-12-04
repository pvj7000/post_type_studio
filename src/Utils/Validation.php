<?php

namespace PostTypeStudio\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Validation {
    public static function validate_post_type( array $data ): array {
        $errors = [];
        if ( empty( $data['slug'] ) ) {
            $errors['slug'] = __( 'Slug is required.', 'post-type-studio' );
        }
        if ( empty( $data['singular_label'] ) ) {
            $errors['singular_label'] = __( 'Singular label is required.', 'post-type-studio' );
        }
        if ( empty( $data['plural_label'] ) ) {
            $errors['plural_label'] = __( 'Plural label is required.', 'post-type-studio' );
        }

        return $errors;
    }

    public static function validate_taxonomy( array $data ): array {
        $errors = [];
        if ( empty( $data['slug'] ) ) {
            $errors['slug'] = __( 'Slug is required.', 'post-type-studio' );
        }
        if ( empty( $data['singular_label'] ) ) {
            $errors['singular_label'] = __( 'Singular label is required.', 'post-type-studio' );
        }
        if ( empty( $data['plural_label'] ) ) {
            $errors['plural_label'] = __( 'Plural label is required.', 'post-type-studio' );
        }

        return $errors;
    }

    public static function validate_field_group( array $data ): array {
        $errors = [];
        if ( empty( $data['group_id'] ) ) {
            $errors['group_id'] = __( 'Group key is required.', 'post-type-studio' );
        }
        if ( empty( $data['title'] ) ) {
            $errors['title'] = __( 'Group title is required.', 'post-type-studio' );
        }

        return $errors;
    }
}
