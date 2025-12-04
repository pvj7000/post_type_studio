<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$is_editing          = ! empty( $editing_field_group );
$field_group_state   = $editing_field_group ?? [
    'group_id'  => '',
    'title'     => '',
    'locations' => [],
    'fields'    => [],
];
$current_field_count = count( $field_group_state['fields'] );
?>
<div class="post-type-studio__card">
    <div class="post-type-studio__card-header">
        <div>
            <p class="post-type-studio__eyebrow"><?php echo $is_editing ? esc_html__( 'Edit', 'post-type-studio' ) : esc_html__( 'Create', 'post-type-studio' ); ?></p>
            <h2><?php echo $is_editing ? esc_html__( 'Edit Field Group', 'post-type-studio' ) : esc_html__( 'New Field Group', 'post-type-studio' ); ?></h2>
            <p class="post-type-studio__muted"><?php esc_html_e( 'Attach structured fields to your post types.', 'post-type-studio' ); ?></p>
        </div>
    </div>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="post-type-studio__form" id="post-type-studio-field-group-form">
        <?php wp_nonce_field( 'post_type_studio_save_field_group' ); ?>
        <input type="hidden" name="action" value="post_type_studio_save_field_group" />
        <input type="hidden" name="original_group_id" value="<?php echo esc_attr( $is_editing ? $field_group_state['group_id'] : '' ); ?>" />
        <div class="post-type-studio__grid">
            <div>
                <label class="post-type-studio__label" for="group_id"><?php esc_html_e( 'Group Key', 'post-type-studio' ); ?></label>
                <input class="post-type-studio__input" type="text" name="group_id" id="group_id" placeholder="book_fields" value="<?php echo esc_attr( $field_group_state['group_id'] ); ?>" />
                <?php if ( ! empty( $errors['group_id'] ) ) : ?>
                    <span class="post-type-studio__error"><?php echo esc_html( $errors['group_id'] ); ?></span>
                <?php endif; ?>
            </div>
            <div>
                <label class="post-type-studio__label" for="title"><?php esc_html_e( 'Title', 'post-type-studio' ); ?></label>
                <input class="post-type-studio__input" type="text" name="title" id="title" placeholder="Book Fields" value="<?php echo esc_attr( $field_group_state['title'] ); ?>" />
                <?php if ( ! empty( $errors['title'] ) ) : ?>
                    <span class="post-type-studio__error"><?php echo esc_html( $errors['title'] ); ?></span>
                <?php endif; ?>
            </div>
            <div>
                <label class="post-type-studio__label"><?php esc_html_e( 'Attach to Post Types', 'post-type-studio' ); ?></label>
                <div class="post-type-studio__chip-list">
                    <?php foreach ( $post_types as $post_type ) : ?>
                        <label class="post-type-studio__chip"><input type="checkbox" name="locations[]" value="<?php echo esc_attr( $post_type['slug'] ); ?>" <?php checked( in_array( $post_type['slug'], $field_group_state['locations'] ?? [], true ) ); ?> /> <?php echo esc_html( $post_type['plural_label'] ); ?></label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="post-type-studio__fieldset">
            <div class="post-type-studio__fieldset-header">
                <div>
                    <p class="post-type-studio__eyebrow"><?php esc_html_e( 'Fields', 'post-type-studio' ); ?></p>
                    <h3><?php esc_html_e( 'Add Fields', 'post-type-studio' ); ?></h3>
                </div>
                <button type="button" class="button button-secondary" id="post-type-studio-add-field"><?php esc_html_e( 'Add Field', 'post-type-studio' ); ?></button>
            </div>
            <div id="post-type-studio-fields-container" class="post-type-studio__field-rows" aria-live="polite" data-field-count="<?php echo esc_attr( $current_field_count ); ?>">
                <?php if ( empty( $field_group_state['fields'] ) ) : ?>
                    <p class="post-type-studio__muted" id="post-type-studio-empty-state"><?php esc_html_e( 'No fields yet. Add your first field.', 'post-type-studio' ); ?></p>
                <?php else : ?>
                    <?php foreach ( $field_group_state['fields'] as $index => $field ) : ?>
                        <?php $show_choices = ( $field['type'] ?? '' ) === 'select'; ?>
                        <div class="post-type-studio__field-row">
                            <div class="post-type-studio__field-row-header">
                                <h4><?php esc_html_e( 'Field', 'post-type-studio' ); ?></h4>
                                <button type="button" class="post-type-studio__remove-field" aria-label="<?php esc_attr_e( 'Remove field', 'post-type-studio' ); ?>">&times;</button>
                            </div>
                            <div class="post-type-studio__grid">
                                <div>
                                    <label class="post-type-studio__label"><?php esc_html_e( 'Field Key', 'post-type-studio' ); ?></label>
                                    <input class="post-type-studio__input" type="text" name="fields[<?php echo esc_attr( $index ); ?>][field_key]" placeholder="book_isbn" value="<?php echo esc_attr( $field['field_key'] ?? '' ); ?>" />
                                </div>
                                <div>
                                    <label class="post-type-studio__label"><?php esc_html_e( 'Label', 'post-type-studio' ); ?></label>
                                    <input class="post-type-studio__input" type="text" name="fields[<?php echo esc_attr( $index ); ?>][label]" placeholder="ISBN" value="<?php echo esc_attr( $field['label'] ?? '' ); ?>" />
                                </div>
                                <div>
                                    <label class="post-type-studio__label"><?php esc_html_e( 'Name', 'post-type-studio' ); ?></label>
                                    <input class="post-type-studio__input" type="text" name="fields[<?php echo esc_attr( $index ); ?>][name]" placeholder="isbn" value="<?php echo esc_attr( $field['name'] ?? '' ); ?>" />
                                </div>
                                <div>
                                    <label class="post-type-studio__label"><?php esc_html_e( 'Type', 'post-type-studio' ); ?></label>
                                    <select class="post-type-studio__input" name="fields[<?php echo esc_attr( $index ); ?>][type]">
                                        <option value="text" <?php selected( $field['type'] ?? '', 'text' ); ?>><?php esc_html_e( 'Text', 'post-type-studio' ); ?></option>
                                        <option value="textarea" <?php selected( $field['type'] ?? '', 'textarea' ); ?>><?php esc_html_e( 'Textarea', 'post-type-studio' ); ?></option>
                                        <option value="select" <?php selected( $field['type'] ?? '', 'select' ); ?>><?php esc_html_e( 'Select', 'post-type-studio' ); ?></option>
                                        <option value="checkbox" <?php selected( $field['type'] ?? '', 'checkbox' ); ?>><?php esc_html_e( 'Checkbox', 'post-type-studio' ); ?></option>
                                    </select>
                                </div>
                                <div>
                                    <label class="post-type-studio__label"><?php esc_html_e( 'Default Value', 'post-type-studio' ); ?></label>
                                    <input class="post-type-studio__input" type="text" name="fields[<?php echo esc_attr( $index ); ?>][default_value]" value="<?php echo esc_attr( $field['default_value'] ?? '' ); ?>" />
                                </div>
                                <div>
                                    <label class="post-type-studio__label"><?php esc_html_e( 'Instructions', 'post-type-studio' ); ?></label>
                                    <textarea class="post-type-studio__input" name="fields[<?php echo esc_attr( $index ); ?>][instructions]" rows="2"><?php echo esc_textarea( $field['instructions'] ?? '' ); ?></textarea>
                                </div>
                                <div>
                                    <label class="post-type-studio__label"><input type="checkbox" name="fields[<?php echo esc_attr( $index ); ?>][required]" <?php checked( ! empty( $field['required'] ) ); ?> /> <?php esc_html_e( 'Required', 'post-type-studio' ); ?></label>
                                </div>
                                <div class="post-type-studio__choices" <?php echo $show_choices ? '' : 'hidden'; ?>>
                                    <label class="post-type-studio__label"><?php esc_html_e( 'Choices (value : label)', 'post-type-studio' ); ?></label>
                                    <?php $choices = $field['choices'] ?? [ [ 'value' => '', 'label' => '' ] ]; ?>
                                    <?php foreach ( $choices as $choice_index => $choice ) : ?>
                                        <div class="post-type-studio__choice-row">
                                            <input class="post-type-studio__input" type="text" name="fields[<?php echo esc_attr( $index ); ?>][choices][<?php echo esc_attr( $choice_index ); ?>][value]" placeholder="value" value="<?php echo esc_attr( $choice['value'] ?? '' ); ?>" />
                                            <input class="post-type-studio__input" type="text" name="fields[<?php echo esc_attr( $index ); ?>][choices][<?php echo esc_attr( $choice_index ); ?>][label]" placeholder="Label" value="<?php echo esc_attr( $choice['label'] ?? '' ); ?>" />
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="post-type-studio__actions">
            <button type="submit" class="button button-primary"><?php echo $is_editing ? esc_html__( 'Update Field Group', 'post-type-studio' ) : esc_html__( 'Save Field Group', 'post-type-studio' ); ?></button>
        </div>
    </form>

    <template id="post-type-studio-field-template">
        <div class="post-type-studio__field-row">
            <div class="post-type-studio__field-row-header">
                <h4><?php esc_html_e( 'Field', 'post-type-studio' ); ?></h4>
                <button type="button" class="post-type-studio__remove-field" aria-label="<?php esc_attr_e( 'Remove field', 'post-type-studio' ); ?>">&times;</button>
            </div>
            <div class="post-type-studio__grid">
                <div>
                    <label class="post-type-studio__label"><?php esc_html_e( 'Field Key', 'post-type-studio' ); ?></label>
                    <input class="post-type-studio__input" type="text" name="fields[__INDEX__][field_key]" placeholder="book_isbn" />
                </div>
                <div>
                    <label class="post-type-studio__label"><?php esc_html_e( 'Label', 'post-type-studio' ); ?></label>
                    <input class="post-type-studio__input" type="text" name="fields[__INDEX__][label]" placeholder="ISBN" />
                </div>
                <div>
                    <label class="post-type-studio__label"><?php esc_html_e( 'Name', 'post-type-studio' ); ?></label>
                    <input class="post-type-studio__input" type="text" name="fields[__INDEX__][name]" placeholder="isbn" />
                </div>
                <div>
                    <label class="post-type-studio__label"><?php esc_html_e( 'Type', 'post-type-studio' ); ?></label>
                    <select class="post-type-studio__input" name="fields[__INDEX__][type]">
                        <option value="text"><?php esc_html_e( 'Text', 'post-type-studio' ); ?></option>
                        <option value="textarea"><?php esc_html_e( 'Textarea', 'post-type-studio' ); ?></option>
                        <option value="select"><?php esc_html_e( 'Select', 'post-type-studio' ); ?></option>
                        <option value="checkbox"><?php esc_html_e( 'Checkbox', 'post-type-studio' ); ?></option>
                    </select>
                </div>
                <div>
                    <label class="post-type-studio__label"><?php esc_html_e( 'Default Value', 'post-type-studio' ); ?></label>
                    <input class="post-type-studio__input" type="text" name="fields[__INDEX__][default_value]" />
                </div>
                <div>
                    <label class="post-type-studio__label"><?php esc_html_e( 'Instructions', 'post-type-studio' ); ?></label>
                    <textarea class="post-type-studio__input" name="fields[__INDEX__][instructions]" rows="2"></textarea>
                </div>
                <div>
                    <label class="post-type-studio__label"><input type="checkbox" name="fields[__INDEX__][required]" /> <?php esc_html_e( 'Required', 'post-type-studio' ); ?></label>
                </div>
                <div class="post-type-studio__choices" hidden>
                    <label class="post-type-studio__label"><?php esc_html_e( 'Choices (value : label)', 'post-type-studio' ); ?></label>
                    <div class="post-type-studio__choice-row">
                        <input class="post-type-studio__input" type="text" name="fields[__INDEX__][choices][0][value]" placeholder="value" />
                        <input class="post-type-studio__input" type="text" name="fields[__INDEX__][choices][0][label]" placeholder="Label" />
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
