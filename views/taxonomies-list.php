<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="post-type-studio__card">
    <div class="post-type-studio__card-header">
        <div>
            <p class="post-type-studio__eyebrow"><?php esc_html_e( 'Overview', 'post-type-studio' ); ?></p>
            <h2><?php esc_html_e( 'Taxonomies', 'post-type-studio' ); ?></h2>
            <p class="post-type-studio__muted"><?php esc_html_e( 'Grouping content with clean, structured vocabularies.', 'post-type-studio' ); ?></p>
        </div>
    </div>
    <table class="widefat fixed post-type-studio__table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Name', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Slug', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Attached To', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Status', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'post-type-studio' ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ( empty( $taxonomies ) ) : ?>
            <tr>
                <td colspan="4" class="post-type-studio__muted"><?php esc_html_e( 'No taxonomies yet. Create one to organize your content.', 'post-type-studio' ); ?></td>
            </tr>
        <?php else : ?>
            <?php foreach ( $taxonomies as $taxonomy ) : ?>
                <tr>
                    <td><?php echo esc_html( $taxonomy['plural_label'] ); ?></td>
                    <td><code><?php echo esc_html( $taxonomy['slug'] ); ?></code></td>
                    <td><?php echo esc_html( implode( ', ', $taxonomy['object_type'] ?? [] ) ); ?></td>
                    <td><span class="post-type-studio__badge is-active"><?php esc_html_e( 'Active', 'post-type-studio' ); ?></span></td>
                    <td>
                        <div class="post-type-studio__table-actions">
                            <a class="button" href="<?php echo esc_url( add_query_arg( [ 'page' => 'post-type-studio-taxonomies', 'edit' => $taxonomy['slug'] ], admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Edit', 'post-type-studio' ); ?></a>
                            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="post-type-studio__inline-form post-type-studio__delete-form" data-confirm-message="<?php esc_attr_e( 'Delete this taxonomy? This cannot be undone.', 'post-type-studio' ); ?>">
                                <?php wp_nonce_field( 'post_type_studio_delete_taxonomy' ); ?>
                                <input type="hidden" name="action" value="post_type_studio_delete_taxonomy" />
                                <input type="hidden" name="slug" value="<?php echo esc_attr( $taxonomy['slug'] ); ?>" />
                                <button type="submit" class="button button-link-delete"><?php esc_html_e( 'Delete', 'post-type-studio' ); ?></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
