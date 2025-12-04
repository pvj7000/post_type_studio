<?php

namespace PostTypeStudio\Domain\Model;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Taxonomy {
    public string $slug;
    public string $singular_label;
    public string $plural_label;
    public array $object_type;
    public bool $public;
    public bool $show_ui;
    public bool $show_in_rest;
    public bool $hierarchical;
    public bool $show_admin_column;

    public function __construct( array $data ) {
        $this->slug              = $data['slug'];
        $this->singular_label    = $data['singular_label'];
        $this->plural_label      = $data['plural_label'];
        $this->object_type       = $data['object_type'] ?? [];
        $this->public            = (bool) ( $data['public'] ?? true );
        $this->show_ui           = (bool) ( $data['show_ui'] ?? true );
        $this->show_in_rest      = (bool) ( $data['show_in_rest'] ?? true );
        $this->hierarchical      = (bool) ( $data['hierarchical'] ?? false );
        $this->show_admin_column = (bool) ( $data['show_admin_column'] ?? false );
    }

    public function to_array(): array {
        return [
            'slug'              => $this->slug,
            'singular_label'    => $this->singular_label,
            'plural_label'      => $this->plural_label,
            'object_type'       => $this->object_type,
            'public'            => $this->public,
            'show_ui'           => $this->show_ui,
            'show_in_rest'      => $this->show_in_rest,
            'hierarchical'      => $this->hierarchical,
            'show_admin_column' => $this->show_admin_column,
        ];
    }
}
