<?php
/**
 * Plugin Name: Post Type Studio
 * Description: Minimal UI for managing custom post types, taxonomies, and custom fields.
 * Version: 1.0.0
 * Author: CentralDev
 * Text Domain: post-type-studio
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

PostTypeStudio\Plugin::boot();
