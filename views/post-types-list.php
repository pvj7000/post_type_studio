<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="post-type-studio__card">
    <div class="post-type-studio__card-header">
        <div>
            <p class="post-type-studio__eyebrow"><?php esc_html_e( 'Overview', 'post-type-studio' ); ?></p>
            <h2><?php esc_html_e( 'Post Types', 'post-type-studio' ); ?></h2>
            <p class="post-type-studio__muted"><?php esc_html_e( 'Registered custom post types and their visibility.', 'post-type-studio' ); ?></p>
        </div>
    </div>
    <table class="widefat fixed post-type-studio__table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Name', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Slug', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Status', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Supports', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'post-type-studio' ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ( empty( $post_types ) ) : ?>
            <tr>
                <td colspan="4" class="post-type-studio__muted"><?php esc_html_e( 'No post types yet. Create your first one below.', 'post-type-studio' ); ?></td>
            </tr>
        <?php else : ?>
            <?php foreach ( $post_types as $post_type ) : ?>
                <tr>
                    <td><?php echo esc_html( $post_type['plural_label'] ); ?></td>
                    <td><code><?php echo esc_html( $post_type['slug'] ); ?></code></td>
                    <td><span class="post-type-studio__badge is-active"><?php esc_html_e( 'Active', 'post-type-studio' ); ?></span></td>
                    <td><?php echo esc_html( implode( ', ', $post_type['supports'] ?? [] ) ); ?></td>
                    <td>
                        <?php if ( ! empty( $post_type['managed'] ) ) : ?>
                            <div class="post-type-studio__table-actions">
                                <a class="button" href="<?php echo esc_url( add_query_arg( [ 'page' => 'post-type-studio', 'tab' => 'post-types', 'edit' => $post_type['slug'] ], admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Edit', 'post-type-studio' ); ?></a>
                                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="post-type-studio__inline-form post-type-studio__delete-form" data-confirm-message="<?php esc_attr_e( 'Delete this post type? This cannot be undone.', 'post-type-studio' ); ?>">
                                    <?php wp_nonce_field( 'post_type_studio_delete_post_type' ); ?>
                                    <input type="hidden" name="action" value="post_type_studio_delete_post_type" />
                                    <input type="hidden" name="slug" value="<?php echo esc_attr( $post_type['slug'] ); ?>" />
                                    <button type="submit" class="button button-link-delete"><?php esc_html_e( 'Delete', 'post-type-studio' ); ?></button>
                                </form>
                            </div>
                        <?php else : ?>
                            <span class="post-type-studio__muted"><?php esc_html_e( 'Registered elsewhere', 'post-type-studio' ); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
