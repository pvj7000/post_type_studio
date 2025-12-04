<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$supports        = [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ];
$is_editing      = ! empty( $editing_post_type );
$post_type_state = $editing_post_type ?? [
    'singular_label'  => '',
    'plural_label'    => '',
    'slug'            => '',
    'public'          => true,
    'show_ui'         => true,
    'show_in_rest'    => true,
    'has_archive'     => false,
    'hierarchical'    => false,
    'supports'        => [ 'title', 'editor' ],
    'menu_icon'       => 'dashicons-admin-post',
    'capability_type' => 'post',
    'menu_position'   => 10,
];
$post_type_state['menu_position'] = $post_type_state['menu_position'] ?? 10;
?>
<div class="post-type-studio__card">
    <div class="post-type-studio__card-header">
        <div>
            <p class="post-type-studio__eyebrow"><?php echo $is_editing ? esc_html__( 'Edit', 'post-type-studio' ) : esc_html__( 'Create', 'post-type-studio' ); ?></p>
            <h2><?php echo $is_editing ? esc_html__( 'Edit Post Type', 'post-type-studio' ) : esc_html__( 'New Post Type', 'post-type-studio' ); ?></h2>
            <p class="post-type-studio__muted"><?php esc_html_e( 'Define labels, visibility, and supports.', 'post-type-studio' ); ?></p>
        </div>
    </div>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="post-type-studio__form">
        <?php wp_nonce_field( 'post_type_studio_save_post_type' ); ?>
        <input type="hidden" name="action" value="post_type_studio_save_post_type" />
        <input type="hidden" name="original_slug" value="<?php echo esc_attr( $is_editing ? $post_type_state['slug'] : '' ); ?>" />
        <div class="post-type-studio__grid">
            <div>
                <label class="post-type-studio__label" for="singular_label"><?php esc_html_e( 'Singular Label', 'post-type-studio' ); ?></label>
                <input class="post-type-studio__input" type="text" name="singular_label" id="singular_label" placeholder="Book" value="<?php echo esc_attr( $post_type_state['singular_label'] ); ?>" />
                <?php if ( ! empty( $errors['singular_label'] ) ) : ?>
                    <span class="post-type-studio__error"><?php echo esc_html( $errors['singular_label'] ); ?></span>
                <?php endif; ?>
            </div>
            <div>
                <label class="post-type-studio__label" for="plural_label"><?php esc_html_e( 'Plural Label', 'post-type-studio' ); ?></label>
                <input class="post-type-studio__input" type="text" name="plural_label" id="plural_label" placeholder="Books" value="<?php echo esc_attr( $post_type_state['plural_label'] ); ?>" />
                <?php if ( ! empty( $errors['plural_label'] ) ) : ?>
                    <span class="post-type-studio__error"><?php echo esc_html( $errors['plural_label'] ); ?></span>
                <?php endif; ?>
            </div>
            <div>
                <label class="post-type-studio__label" for="slug"><?php esc_html_e( 'Slug', 'post-type-studio' ); ?></label>
                <input class="post-type-studio__input" type="text" name="slug" id="slug" placeholder="book" value="<?php echo esc_attr( $post_type_state['slug'] ); ?>" data-touched="<?php echo $is_editing ? 'true' : 'false'; ?>" />
                <?php if ( ! empty( $errors['slug'] ) ) : ?>
                    <span class="post-type-studio__error"><?php echo esc_html( $errors['slug'] ); ?></span>
                <?php endif; ?>
            </div>
            <div class="post-type-studio__toggle-group">
                <label><input type="checkbox" name="public" <?php checked( $post_type_state['public'] ); ?> /> <?php esc_html_e( 'Public', 'post-type-studio' ); ?></label>
                <label><input type="checkbox" name="show_ui" <?php checked( $post_type_state['show_ui'] ); ?> /> <?php esc_html_e( 'Show in Admin', 'post-type-studio' ); ?></label>
                <label><input type="checkbox" name="show_in_rest" <?php checked( $post_type_state['show_in_rest'] ); ?> /> <?php esc_html_e( 'Expose to REST', 'post-type-studio' ); ?></label>
                <label><input type="checkbox" name="has_archive" <?php checked( $post_type_state['has_archive'] ); ?> /> <?php esc_html_e( 'Has Archive', 'post-type-studio' ); ?></label>
                <label><input type="checkbox" name="hierarchical" <?php checked( $post_type_state['hierarchical'] ); ?> /> <?php esc_html_e( 'Hierarchical', 'post-type-studio' ); ?></label>
            </div>
            <div>
                <label class="post-type-studio__label"><?php esc_html_e( 'Supports', 'post-type-studio' ); ?></label>
                <div class="post-type-studio__chip-list">
                    <?php foreach ( $supports as $support ) : ?>
                        <label class="post-type-studio__chip"><input type="checkbox" name="supports[]" value="<?php echo esc_attr( $support ); ?>" <?php checked( in_array( $support, $post_type_state['supports'] ?? [], true ) ); ?> /> <?php echo esc_html( ucfirst( str_replace( '-', ' ', $support ) ) ); ?></label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="post-type-studio__two-col">
                <div>
                    <label class="post-type-studio__label" for="capability_type"><?php esc_html_e( 'Capability Type', 'post-type-studio' ); ?></label>
                    <input class="post-type-studio__input" type="text" name="capability_type" id="capability_type" value="<?php echo esc_attr( $post_type_state['capability_type'] ); ?>" />
                </div>
                <div>
                    <label class="post-type-studio__label" for="menu_icon"><?php esc_html_e( 'Menu Icon', 'post-type-studio' ); ?></label>
                    <input class="post-type-studio__input" type="text" name="menu_icon" id="menu_icon" value="<?php echo esc_attr( $post_type_state['menu_icon'] ); ?>" />
                </div>
            </div>
            <div>
                <label class="post-type-studio__label" for="menu_position"><?php esc_html_e( 'Menu Position', 'post-type-studio' ); ?></label>
                <input class="post-type-studio__input" type="number" name="menu_position" id="menu_position" value="<?php echo esc_attr( $post_type_state['menu_position'] ); ?>" placeholder="10" />
            </div>
        </div>
        <div class="post-type-studio__actions">
            <button type="submit" class="button button-primary"><?php echo $is_editing ? esc_html__( 'Update Post Type', 'post-type-studio' ) : esc_html__( 'Save Post Type', 'post-type-studio' ); ?></button>
        </div>
    </form>
</div>
