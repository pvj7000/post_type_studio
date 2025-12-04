<?php

namespace PostTypeStudio\Persistence;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class OptionsRepository {
    private string $option_name;
    private array $config_cache = [];

    public function __construct( string $option_name ) {
        $this->option_name = $option_name;
    }

    public function get_config(): array {
        if ( ! empty( $this->config_cache ) ) {
            return $this->config_cache;
        }

        $defaults = [
            'config_version' => 1,
            'post_types'     => [],
            'taxonomies'     => [],
            'field_groups'   => [],
        ];

        $config = get_option( $this->option_name, $defaults );

        $this->config_cache = wp_parse_args( $config, $defaults );

        return $this->config_cache;
    }

    public function save_config( array $config ): void {
        $config['config_version'] = $config['config_version'] ?? 1;
        update_option( $this->option_name, $config );
        $this->config_cache = $config;
    }

    public function update_section( string $section, array $data ): void {
        $config = $this->get_config();
        $config[ $section ] = $data;
        $this->save_config( $config );
    }
}
