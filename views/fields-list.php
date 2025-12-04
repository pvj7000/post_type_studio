<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="post-type-studio__card">
    <div class="post-type-studio__card-header">
        <div>
            <p class="post-type-studio__eyebrow"><?php esc_html_e( 'Overview', 'post-type-studio' ); ?></p>
            <h2><?php esc_html_e( 'Field Groups', 'post-type-studio' ); ?></h2>
            <p class="post-type-studio__muted"><?php esc_html_e( 'Reusable sets of custom fields for your content types.', 'post-type-studio' ); ?></p>
        </div>
    </div>
    <table class="widefat fixed post-type-studio__table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Title', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Key', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Attached To', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Fields', 'post-type-studio' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'post-type-studio' ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ( empty( $field_groups ) ) : ?>
            <tr>
                <td colspan="4" class="post-type-studio__muted"><?php esc_html_e( 'No field groups yet. Create one to start capturing structured data.', 'post-type-studio' ); ?></td>
            </tr>
        <?php else : ?>
            <?php foreach ( $field_groups as $group ) : ?>
                <tr>
                    <td><?php echo esc_html( $group['title'] ); ?></td>
                    <td><code><?php echo esc_html( $group['group_id'] ); ?></code></td>
                    <td><?php echo esc_html( implode( ', ', $group['locations'] ?? [] ) ); ?></td>
                    <td><?php echo esc_html( count( $group['fields'] ?? [] ) ); ?></td>
                    <td>
                        <div class="post-type-studio__table-actions">
                            <a class="button" href="<?php echo esc_url( add_query_arg( [ 'page' => 'post-type-studio-fields', 'edit' => $group['group_id'] ], admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Edit', 'post-type-studio' ); ?></a>
                            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="post-type-studio__inline-form post-type-studio__delete-form" data-confirm-message="<?php esc_attr_e( 'Delete this field group? This cannot be undone.', 'post-type-studio' ); ?>">
                                <?php wp_nonce_field( 'post_type_studio_delete_field_group' ); ?>
                                <input type="hidden" name="action" value="post_type_studio_delete_field_group" />
                                <input type="hidden" name="group_id" value="<?php echo esc_attr( $group['group_id'] ); ?>" />
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
