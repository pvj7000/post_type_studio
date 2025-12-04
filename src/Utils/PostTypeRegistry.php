<?php

namespace PostTypeStudio\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PostTypeRegistry {
    public static function get_available_post_types( array $saved_post_types ): array {
        $available = [];

        foreach ( $saved_post_types as $slug => $post_type ) {
            $available[ $slug ]            = $post_type;
            $available[ $slug ]['managed'] = true;
        }

        $registered = get_post_types( [
            '_builtin' => false,
        ], 'objects' );

        foreach ( $registered as $slug => $post_type_object ) {
            if ( isset( $available[ $slug ] ) ) {
                continue;
            }

            $available[ $slug ] = [
                'slug'           => $slug,
                'singular_label' => $post_type_object->labels->singular_name ?? $post_type_object->label ?? $slug,
                'plural_label'   => $post_type_object->labels->name ?? $post_type_object->label ?? $slug,
                'supports'       => [],
                'managed'        => false,
            ];
        }

        return $available;
    }
}

