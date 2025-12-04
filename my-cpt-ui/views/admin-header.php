<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$current_page = $_GET['page'] ?? 'my-cpt-ui';
$base_url     = admin_url( 'admin.php' );
$doc_url      = plugins_url( 'DOCUMENTATION.md', dirname( __DIR__ ) . '/my-cpt-ui.php' );
?>
<div class="wrap my-cpt-ui">
    <h1 class="my-cpt-ui__title"><?php esc_html_e( 'Content Types', 'my-cpt-ui' ); ?></h1>
    <nav class="my-cpt-ui__tabs" aria-label="<?php esc_attr_e( 'Content Type tabs', 'my-cpt-ui' ); ?>">
        <a class="my-cpt-ui__tab <?php echo $current_page === 'my-cpt-ui' ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( [ 'page' => 'my-cpt-ui' ], $base_url ) ); ?>">
            <?php esc_html_e( 'Post Types', 'my-cpt-ui' ); ?>
        </a>
        <a class="my-cpt-ui__tab <?php echo $current_page === 'my-cpt-ui-taxonomies' ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( [ 'page' => 'my-cpt-ui-taxonomies' ], $base_url ) ); ?>">
            <?php esc_html_e( 'Taxonomies', 'my-cpt-ui' ); ?>
        </a>
        <a class="my-cpt-ui__tab <?php echo $current_page === 'my-cpt-ui-fields' ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( [ 'page' => 'my-cpt-ui-fields' ], $base_url ) ); ?>">
            <?php esc_html_e( 'Custom Fields', 'my-cpt-ui' ); ?>
        </a>
    </nav>
    <?php if ( ! empty( $updated ) ) : ?>
        <div class="my-cpt-ui__notice success">
            <span><?php esc_html_e( 'Saved successfully.', 'my-cpt-ui' ); ?></span>
        </div>
    <?php endif; ?>
    <?php if ( ! empty( $errors ) ) : ?>
        <div class="my-cpt-ui__notice error">
            <span><?php esc_html_e( 'Please fix the highlighted fields.', 'my-cpt-ui' ); ?></span>
        </div>
    <?php endif; ?>

    <div
        id="my-cpt-ui-confirm"
        class="my-cpt-ui__modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="my-cpt-ui-confirm__title"
        aria-describedby="my-cpt-ui-confirm__message"
        hidden
    >
        <div class="my-cpt-ui__modal__overlay" tabindex="-1" data-dismiss="true"></div>
        <div class="my-cpt-ui__modal__dialog" role="document">
            <div class="my-cpt-ui__modal__header">
                <h2 id="my-cpt-ui-confirm__title"><?php esc_html_e( 'Confirm deletion', 'my-cpt-ui' ); ?></h2>
            </div>
            <div class="my-cpt-ui__modal__body">
                <p id="my-cpt-ui-confirm__message" class="my-cpt-ui__muted"></p>
            </div>
            <div class="my-cpt-ui__modal__footer">
                <button type="button" class="button" id="my-cpt-ui-confirm__cancel"><?php esc_html_e( 'Cancel', 'my-cpt-ui' ); ?></button>
                <button type="button" class="button button-primary" id="my-cpt-ui-confirm__accept"><?php esc_html_e( 'Delete item', 'my-cpt-ui' ); ?></button>
            </div>
        </div>
    </div>

    <div class="my-cpt-ui__footer">
        <a class="my-cpt-ui__doc-link" href="<?php echo esc_url( $doc_url ); ?>" target="_blank" rel="noreferrer noopener">
            <?php esc_html_e( 'Open the My CPT UI documentation', 'my-cpt-ui' ); ?>
        </a>
    </div>
</div>
