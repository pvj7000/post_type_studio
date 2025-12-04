<?php

namespace PostTypeStudio\Domain\Model;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PostType {
    public string $slug;
    public string $singular_label;
    public string $plural_label;
    public bool $public;
    public bool $show_ui;
    public bool $show_in_rest;
    public bool $has_archive;
    public bool $hierarchical;
    public array $supports;
    public string $menu_icon;
    public string $capability_type;
    public ?int $menu_position;

    public function __construct( array $data ) {
        $this->slug            = $data['slug'];
        $this->singular_label  = $data['singular_label'];
        $this->plural_label    = $data['plural_label'];
        $this->public          = (bool) ( $data['public'] ?? true );
        $this->show_ui         = (bool) ( $data['show_ui'] ?? true );
        $this->show_in_rest    = (bool) ( $data['show_in_rest'] ?? true );
        $this->has_archive     = (bool) ( $data['has_archive'] ?? false );
        $this->hierarchical    = (bool) ( $data['hierarchical'] ?? false );
        $this->supports        = $data['supports'] ?? [ 'title', 'editor' ];
        $this->menu_icon       = $data['menu_icon'] ?? 'dashicons-admin-post';
        $this->capability_type = $data['capability_type'] ?? 'post';
        $this->menu_position   = isset( $data['menu_position'] ) ? (int) $data['menu_position'] : null;
    }

    public function to_array(): array {
        return [
            'slug'            => $this->slug,
            'singular_label'  => $this->singular_label,
            'plural_label'    => $this->plural_label,
            'public'          => $this->public,
            'show_ui'         => $this->show_ui,
            'show_in_rest'    => $this->show_in_rest,
            'has_archive'     => $this->has_archive,
            'hierarchical'    => $this->hierarchical,
            'supports'        => $this->supports,
            'menu_icon'       => $this->menu_icon,
            'capability_type' => $this->capability_type,
            'menu_position'   => $this->menu_position,
        ];
    }
}
