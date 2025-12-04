<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="my-cpt-ui__card">
    <div class="my-cpt-ui__card-header">
        <div>
            <p class="my-cpt-ui__eyebrow"><?php esc_html_e( 'Overview', 'my-cpt-ui' ); ?></p>
            <h2><?php esc_html_e( 'Field Groups', 'my-cpt-ui' ); ?></h2>
            <p class="my-cpt-ui__muted"><?php esc_html_e( 'Reusable sets of custom fields for your content types.', 'my-cpt-ui' ); ?></p>
        </div>
    </div>
    <table class="widefat fixed my-cpt-ui__table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Title', 'my-cpt-ui' ); ?></th>
                <th><?php esc_html_e( 'Key', 'my-cpt-ui' ); ?></th>
                <th><?php esc_html_e( 'Attached To', 'my-cpt-ui' ); ?></th>
                <th><?php esc_html_e( 'Fields', 'my-cpt-ui' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'my-cpt-ui' ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ( empty( $field_groups ) ) : ?>
            <tr>
                <td colspan="4" class="my-cpt-ui__muted"><?php esc_html_e( 'No field groups yet. Create one to start capturing structured data.', 'my-cpt-ui' ); ?></td>
            </tr>
        <?php else : ?>
            <?php foreach ( $field_groups as $group ) : ?>
                <tr>
                    <td><?php echo esc_html( $group['title'] ); ?></td>
                    <td><code><?php echo esc_html( $group['group_id'] ); ?></code></td>
                    <td><?php echo esc_html( implode( ', ', $group['locations'] ?? [] ) ); ?></td>
                    <td><?php echo esc_html( count( $group['fields'] ?? [] ) ); ?></td>
                    <td>
                        <div class="my-cpt-ui__table-actions">
                            <a class="button" href="<?php echo esc_url( add_query_arg( [ 'page' => 'my-cpt-ui-fields', 'edit' => $group['group_id'] ], admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Edit', 'my-cpt-ui' ); ?></a>
                            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="my-cpt-ui__inline-form my-cpt-ui__delete-form" data-confirm-message="<?php esc_attr_e( 'Delete this field group? This cannot be undone.', 'my-cpt-ui' ); ?>">
                                <?php wp_nonce_field( 'my_cpt_ui_delete_field_group' ); ?>
                                <input type="hidden" name="action" value="my_cpt_ui_delete_field_group" />
                                <input type="hidden" name="group_id" value="<?php echo esc_attr( $group['group_id'] ); ?>" />
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
