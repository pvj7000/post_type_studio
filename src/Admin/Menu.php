<?php

namespace PostTypeStudio\Admin;

use PostTypeStudio\Admin\Screens\FieldsScreen;
use PostTypeStudio\Admin\Screens\PostTypesScreen;
use PostTypeStudio\Admin\Screens\TaxonomiesScreen;
use PostTypeStudio\Domain\FieldManager;
use PostTypeStudio\Domain\PostTypeManager;
use PostTypeStudio\Domain\TaxonomyManager;
use PostTypeStudio\Persistence\OptionsRepository;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Menu {
    private PostTypeManager $post_types;
    private TaxonomyManager $taxonomies;
    private FieldManager $fields;
    private OptionsRepository $repository;
    private Notices $notices;

    public function __construct( PostTypeManager $post_types, TaxonomyManager $taxonomies, FieldManager $fields, OptionsRepository $repository, Notices $notices ) {
        $this->post_types  = $post_types;
        $this->taxonomies  = $taxonomies;
        $this->fields      = $fields;
        $this->repository  = $repository;
        $this->notices     = $notices;
    }

    public function register(): void {
        add_menu_page(
            __( 'Content Types', 'post-type-studio' ),
            __( 'Content Types', 'post-type-studio' ),
            'manage_options',
            'post-type-studio',
            [ $this, 'render_page' ],
            'dashicons-index-card',
            30
        );

        add_submenu_page( 'post-type-studio', __( 'Post Types', 'post-type-studio' ), __( 'Post Types', 'post-type-studio' ), 'manage_options', 'post-type-studio', [ $this, 'render_page' ] );
        add_submenu_page( 'post-type-studio', __( 'Taxonomies', 'post-type-studio' ), __( 'Taxonomies', 'post-type-studio' ), 'manage_options', 'post-type-studio-taxonomies', [ $this, 'render_taxonomies' ] );
        add_submenu_page( 'post-type-studio', __( 'Custom Fields', 'post-type-studio' ), __( 'Custom Fields', 'post-type-studio' ), 'manage_options', 'post-type-studio-fields', [ $this, 'render_fields' ] );
    }

    public function render_page(): void {
        $screen = new PostTypesScreen( $this->repository );
        $screen->render();
    }

    public function render_taxonomies(): void {
        $screen = new TaxonomiesScreen( $this->repository );
        $screen->render();
    }

    public function render_fields(): void {
        $screen = new FieldsScreen( $this->repository );
        $screen->render();
    }
}
