<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$current_page = $_GET['page'] ?? 'post-type-studio';
$base_url     = admin_url( 'admin.php' );
$doc_url      = plugins_url( 'DOCUMENTATION.md', dirname( __DIR__ ) . '/post-type-studio.php' );
?>
<div class="wrap post-type-studio">
    <h1 class="post-type-studio__title"><?php esc_html_e( 'Content Types', 'post-type-studio' ); ?></h1>
    <nav class="post-type-studio__tabs" aria-label="<?php esc_attr_e( 'Content Type tabs', 'post-type-studio' ); ?>">
        <a class="post-type-studio__tab <?php echo $current_page === 'post-type-studio' ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( [ 'page' => 'post-type-studio' ], $base_url ) ); ?>">
            <?php esc_html_e( 'Post Types', 'post-type-studio' ); ?>
        </a>
        <a class="post-type-studio__tab <?php echo $current_page === 'post-type-studio-taxonomies' ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( [ 'page' => 'post-type-studio-taxonomies' ], $base_url ) ); ?>">
            <?php esc_html_e( 'Taxonomies', 'post-type-studio' ); ?>
        </a>
        <a class="post-type-studio__tab <?php echo $current_page === 'post-type-studio-fields' ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( [ 'page' => 'post-type-studio-fields' ], $base_url ) ); ?>">
            <?php esc_html_e( 'Custom Fields', 'post-type-studio' ); ?>
        </a>
    </nav>
    <?php if ( ! empty( $updated ) ) : ?>
        <div class="post-type-studio__notice success">
            <span><?php esc_html_e( 'Saved successfully.', 'post-type-studio' ); ?></span>
        </div>
    <?php endif; ?>
    <?php if ( ! empty( $errors ) ) : ?>
        <div class="post-type-studio__notice error">
            <span><?php esc_html_e( 'Please fix the highlighted fields.', 'post-type-studio' ); ?></span>
        </div>
    <?php endif; ?>

    <div
        id="post-type-studio-confirm"
        class="post-type-studio__modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="post-type-studio-confirm__title"
        aria-describedby="post-type-studio-confirm__message"
        hidden
    >
        <div class="post-type-studio__modal__overlay" tabindex="-1" data-dismiss="true"></div>
        <div class="post-type-studio__modal__dialog" role="document">
            <div class="post-type-studio__modal__header">
                <h2 id="post-type-studio-confirm__title"><?php esc_html_e( 'Confirm deletion', 'post-type-studio' ); ?></h2>
            </div>
            <div class="post-type-studio__modal__body">
                <p id="post-type-studio-confirm__message" class="post-type-studio__muted"></p>
            </div>
            <div class="post-type-studio__modal__footer">
                <button type="button" class="button" id="post-type-studio-confirm__cancel"><?php esc_html_e( 'Cancel', 'post-type-studio' ); ?></button>
                <button type="button" class="button button-primary" id="post-type-studio-confirm__accept"><?php esc_html_e( 'Delete item', 'post-type-studio' ); ?></button>
            </div>
        </div>
    </div>

    <div class="post-type-studio__footer">
        <a class="post-type-studio__doc-link" href="<?php echo esc_url( $doc_url ); ?>" target="_blank" rel="noreferrer noopener">
            <?php esc_html_e( 'Open the Post Type Studio documentation', 'post-type-studio' ); ?>
        </a>
    </div>
</div>
