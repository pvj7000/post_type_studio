<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="my-cpt-ui__card">
    <div class="my-cpt-ui__card-header">
        <div>
            <p class="my-cpt-ui__eyebrow"><?php esc_html_e( 'Overview', 'my-cpt-ui' ); ?></p>
            <h2><?php esc_html_e( 'Taxonomies', 'my-cpt-ui' ); ?></h2>
            <p class="my-cpt-ui__muted"><?php esc_html_e( 'Grouping content with clean, structured vocabularies.', 'my-cpt-ui' ); ?></p>
        </div>
    </div>
    <table class="widefat fixed my-cpt-ui__table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Name', 'my-cpt-ui' ); ?></th>
                <th><?php esc_html_e( 'Slug', 'my-cpt-ui' ); ?></th>
                <th><?php esc_html_e( 'Attached To', 'my-cpt-ui' ); ?></th>
                <th><?php esc_html_e( 'Status', 'my-cpt-ui' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'my-cpt-ui' ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ( empty( $taxonomies ) ) : ?>
            <tr>
                <td colspan="4" class="my-cpt-ui__muted"><?php esc_html_e( 'No taxonomies yet. Create one to organize your content.', 'my-cpt-ui' ); ?></td>
            </tr>
        <?php else : ?>
            <?php foreach ( $taxonomies as $taxonomy ) : ?>
                <tr>
                    <td><?php echo esc_html( $taxonomy['plural_label'] ); ?></td>
                    <td><code><?php echo esc_html( $taxonomy['slug'] ); ?></code></td>
                    <td><?php echo esc_html( implode( ', ', $taxonomy['object_type'] ?? [] ) ); ?></td>
                    <td><span class="my-cpt-ui__badge is-active"><?php esc_html_e( 'Active', 'my-cpt-ui' ); ?></span></td>
                    <td>
                        <div class="my-cpt-ui__table-actions">
                            <a class="button" href="<?php echo esc_url( add_query_arg( [ 'page' => 'my-cpt-ui-taxonomies', 'edit' => $taxonomy['slug'] ], admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Edit', 'my-cpt-ui' ); ?></a>
                            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="my-cpt-ui__inline-form my-cpt-ui__delete-form" data-confirm-message="<?php esc_attr_e( 'Delete this taxonomy? This cannot be undone.', 'my-cpt-ui' ); ?>">
                                <?php wp_nonce_field( 'my_cpt_ui_delete_taxonomy' ); ?>
                                <input type="hidden" name="action" value="my_cpt_ui_delete_taxonomy" />
                                <input type="hidden" name="slug" value="<?php echo esc_attr( $taxonomy['slug'] ); ?>" />
                                <button type="submit" class="button button-link-delete"><?php esc_html_e( 'Delete', 'my-cpt-ui' ); ?></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
