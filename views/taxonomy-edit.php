<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="post-type-studio__card">
    <div class="post-type-studio__card-header">
        <div>
            <?php $is_editing = ! empty( $editing_taxonomy ); ?>
            <p class="post-type-studio__eyebrow"><?php echo $is_editing ? esc_html__( 'Edit', 'post-type-studio' ) : esc_html__( 'Create', 'post-type-studio' ); ?></p>
            <h2><?php echo $is_editing ? esc_html__( 'Edit Taxonomy', 'post-type-studio' ) : esc_html__( 'New Taxonomy', 'post-type-studio' ); ?></h2>
            <p class="post-type-studio__muted"><?php esc_html_e( 'Attach taxonomies to one or more post types.', 'post-type-studio' ); ?></p>
        </div>
    </div>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="post-type-studio__form">
        <?php wp_nonce_field( 'post_type_studio_save_taxonomy' ); ?>
        <input type="hidden" name="action" value="post_type_studio_save_taxonomy" />
        <input type="hidden" name="original_slug" value="<?php echo esc_attr( $is_editing ? $editing_taxonomy['slug'] : '' ); ?>" />
        <div class="post-type-studio__grid">
            <div>
                <label class="post-type-studio__label" for="singular_label"><?php esc_html_e( 'Singular Label', 'post-type-studio' ); ?></label>
                <input class="post-type-studio__input" type="text" name="singular_label" id="taxonomy_singular_label" placeholder="Genre" value="<?php echo esc_attr( $editing_taxonomy['singular_label'] ?? '' ); ?>" />
                <?php if ( ! empty( $errors['singular_label'] ) ) : ?>
                    <span class="post-type-studio__error"><?php echo esc_html( $errors['singular_label'] ); ?></span>
                <?php endif; ?>
            </div>
            <div>
                <label class="post-type-studio__label" for="plural_label"><?php esc_html_e( 'Plural Label', 'post-type-studio' ); ?></label>
                <input class="post-type-studio__input" type="text" name="plural_label" id="taxonomy_plural_label" placeholder="Genres" value="<?php echo esc_attr( $editing_taxonomy['plural_label'] ?? '' ); ?>" />
                <?php if ( ! empty( $errors['plural_label'] ) ) : ?>
                    <span class="post-type-studio__error"><?php echo esc_html( $errors['plural_label'] ); ?></span>
                <?php endif; ?>
            </div>
            <div>
                <label class="post-type-studio__label" for="slug"><?php esc_html_e( 'Slug', 'post-type-studio' ); ?></label>
                <input class="post-type-studio__input" type="text" name="slug" id="taxonomy_slug" placeholder="genre" value="<?php echo esc_attr( $editing_taxonomy['slug'] ?? '' ); ?>" data-touched="<?php echo $is_editing ? 'true' : 'false'; ?>" />
                <?php if ( ! empty( $errors['slug'] ) ) : ?>
                    <span class="post-type-studio__error"><?php echo esc_html( $errors['slug'] ); ?></span>
                <?php endif; ?>
            </div>
            <div>
                <label class="post-type-studio__label"><?php esc_html_e( 'Attach to Post Types', 'post-type-studio' ); ?></label>
                <div class="post-type-studio__chip-list">
                    <?php foreach ( $post_types as $post_type ) : ?>
                        <label class="post-type-studio__chip"><input type="checkbox" name="object_type[]" value="<?php echo esc_attr( $post_type['slug'] ); ?>" <?php checked( in_array( $post_type['slug'], $editing_taxonomy['object_type'] ?? [], true ) ); ?> /> <?php echo esc_html( $post_type['plural_label'] ); ?></label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="post-type-studio__toggle-group">
                <label><input type="checkbox" name="public" <?php checked( $editing_taxonomy['public'] ?? true ); ?> /> <?php esc_html_e( 'Public', 'post-type-studio' ); ?></label>
                <label><input type="checkbox" name="show_ui" <?php checked( $editing_taxonomy['show_ui'] ?? true ); ?> /> <?php esc_html_e( 'Show in Admin', 'post-type-studio' ); ?></label>
                <label><input type="checkbox" name="show_in_rest" <?php checked( $editing_taxonomy['show_in_rest'] ?? true ); ?> /> <?php esc_html_e( 'Expose to REST', 'post-type-studio' ); ?></label>
                <label><input type="checkbox" name="hierarchical" <?php checked( $editing_taxonomy['hierarchical'] ?? false ); ?> /> <?php esc_html_e( 'Hierarchical', 'post-type-studio' ); ?></label>
                <label><input type="checkbox" name="show_admin_column" <?php checked( $editing_taxonomy['show_admin_column'] ?? false ); ?> /> <?php esc_html_e( 'Admin Column', 'post-type-studio' ); ?></label>
            </div>
        </div>
        <div class="post-type-studio__actions">
            <button type="submit" class="button button-primary"><?php echo $is_editing ? esc_html__( 'Update Taxonomy', 'post-type-studio' ) : esc_html__( 'Save Taxonomy', 'post-type-studio' ); ?></button>
        </div>
    </form>
</div>
