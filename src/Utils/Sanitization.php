<?php

namespace PostTypeStudio\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Sanitization {
    public static function sanitize_post_type( array $data ): array {
        $supports = array_map( 'sanitize_key', $data['supports'] ?? [] );

        return [
            'slug'            => sanitize_title( $data['slug'] ?? '' ),
            'singular_label'  => sanitize_text_field( $data['singular_label'] ?? '' ),
            'plural_label'    => sanitize_text_field( $data['plural_label'] ?? '' ),
            'public'          => ! empty( $data['public'] ),
            'show_ui'         => ! empty( $data['show_ui'] ),
            'show_in_rest'    => ! empty( $data['show_in_rest'] ),
            'has_archive'     => ! empty( $data['has_archive'] ),
            'hierarchical'    => ! empty( $data['hierarchical'] ),
            'supports'        => $supports ?: [ 'title', 'editor' ],
            'menu_icon'       => sanitize_text_field( $data['menu_icon'] ?? 'dashicons-admin-post' ),
            'capability_type' => sanitize_text_field( $data['capability_type'] ?? 'post' ),
            'menu_position'   => isset( $data['menu_position'] ) ? (int) $data['menu_position'] : 10,
        ];
    }

    public static function sanitize_taxonomy( array $data ): array {
        $object_type = array_filter( array_map( 'sanitize_key', $data['object_type'] ?? [] ) );
        return [
            'slug'              => sanitize_title( $data['slug'] ?? '' ),
            'singular_label'    => sanitize_text_field( $data['singular_label'] ?? '' ),
            'plural_label'      => sanitize_text_field( $data['plural_label'] ?? '' ),
            'object_type'       => $object_type,
            'public'            => ! empty( $data['public'] ),
            'show_ui'           => ! empty( $data['show_ui'] ),
            'show_in_rest'      => ! empty( $data['show_in_rest'] ),
            'hierarchical'      => ! empty( $data['hierarchical'] ),
            'show_admin_column' => ! empty( $data['show_admin_column'] ),
        ];
    }

    public static function sanitize_field_group( array $data ): array {
        $fields = [];
        if ( isset( $data['fields'] ) && is_array( $data['fields'] ) ) {
            foreach ( $data['fields'] as $field ) {
                if ( empty( $field['name'] ) ) {
                    continue;
                }

                $choices_raw = $field['choices'] ?? [];
                $choices = [];
                if ( is_array( $choices_raw ) ) {
                    foreach ( $choices_raw as $choice ) {
                        if ( ! empty( $choice['value'] ) ) {
                            $choices[] = [
                                'value' => sanitize_text_field( $choice['value'] ),
                                'label' => sanitize_text_field( $choice['label'] ?? $choice['value'] ),
                            ];
                        }
                    }
                }

                $fields[] = [
                    'field_key'        => sanitize_key( $field['field_key'] ?? '' ),
                    'label'            => sanitize_text_field( $field['label'] ?? '' ),
                    'name'             => sanitize_key( $field['name'] ?? '' ),
                    'type'             => sanitize_key( $field['type'] ?? 'text' ),
                    'instructions'     => sanitize_textarea_field( $field['instructions'] ?? '' ),
                    'required'         => ! empty( $field['required'] ),
                    'default_value'    => sanitize_text_field( $field['default_value'] ?? '' ),
                    'choices'          => $choices,
                ];
            }
        }

        $locations = array_filter( array_map( 'sanitize_key', $data['locations'] ?? [] ) );

        return [
            'group_id'  => sanitize_key( $data['group_id'] ?? '' ),
            'title'     => sanitize_text_field( $data['title'] ?? '' ),
            'locations' => $locations,
            'fields'    => $fields,
        ];
    }
}
