# Post Type Studio Documentation

A friendly guide to building and maintaining custom post types, taxonomies, and custom fields without writing code.

- **Audience:** Site owners, editors, and developers.
- **Goal:** Explain every setting in plain language and help you keep content models consistent and fast.

## Getting Started

1. Open **Content Types** in the WordPress admin menu.
2. Use the top tabs to switch between **Post Types**, **Taxonomies**, and **Custom Fields**.
3. Create or edit items with the forms on each screen. Changes are saved to a single configuration, so everything is centralized.

## Post Types

A post type defines a bucket of content (e.g., Books, Events, Portfolio Items).

### Fields
- **Singular Label**: How a single item is named (e.g., "Book"). Shown in buttons like *Add New Book*.
- **Plural Label**: The collection name (e.g., "Books"). Used in menus and listings.
- **Slug**: The machine-safe key. Lowercase letters, numbers, and hyphens only (e.g., `book`). Defaults to the Singular Label if left alone.
- **Public**: Whether the type is visible on the front end and queryable.
- **Show in Admin**: Adds the post type to the admin menu and lists.
- **Expose to REST**: Enables the WordPress REST API and block editor. Keep this on for Gutenberg.
- **Has Archive**: Generates an archive page like `/books/`.
- **Hierarchical**: Allows parent/child relationships (similar to Pages).
- **Supports**: Built-in UI features to enable on the edit screen. Options: Title, Editor, Thumbnail, Excerpt, Custom Fields.
- **Capability Type**: Base capability for permissions (usually `post` or `page`).
- **Menu Icon**: Dashicon class or a custom icon URL for the admin menu.
- **Menu Position**: Numeric order for the admin menu (lower numbers appear higher). Common anchors: 5 (Posts), 10 (Media), 20 (Pages).

### Tips
- Keep slugs short and permanent; changing them later can break permalinks.
- Enable **Expose to REST** for a smooth block editor experience (fixes "entity config" errors when editing).

## Taxonomies

Taxonomies group related content under a shared vocabulary (e.g., Genres for Books).

### Fields
- **Singular Label**: Name for one term (e.g., "Genre").
- **Plural Label**: Name for the collection (e.g., "Genres").
- **Slug**: Machine key for the taxonomy (e.g., `genre`).
- **Attach to Post Types**: Choose which post types use this taxonomy. You can select multiple.
- **Public**: Whether terms are visible publicly and queryable.
- **Show in Admin**: Adds the taxonomy UI inside the chosen post types.
- **Expose to REST**: Makes terms available to the REST API and block editor.
- **Hierarchical**: Allows nested terms (like Categories). Turn off for tag-like behavior.
- **Admin Column**: Adds a column to post lists showing assigned terms.

### Tips
- Attach taxonomies to at least one post type, or they will not appear anywhere.
- Use hierarchical taxonomies when you need parent/child term relationships.

## Custom Fields (Field Groups)

Field groups let you attach structured metadata to post types (e.g., ISBN for Books, Event date for Events).

### Field Group Settings
- **Group Key**: A unique identifier for the group (e.g., `book_fields`). Used internally; avoid changing after use.
- **Title**: Human-friendly name shown on the edit screen.
- **Attach to Post Types**: Select which post types display this field group.

### Fields within a Group
For each field you add:
- **Field Key**: Internal reference key. Helps identify fields uniquely (e.g., `book_isbn`).
- **Label**: What editors see on the form (e.g., "ISBN").
- **Name**: The meta key stored in the database (e.g., `isbn`). Keep it lowercase with no spaces.
- **Type**:
  - **Text**: Single-line input.
  - **Textarea**: Multi-line input.
  - **Select**: Dropdown list of choices (configure values and labels).
  - **Checkbox**: Single checkbox that stores `1` when checked.
- **Default Value**: Prefills the field when creating a new post.
- **Instructions**: Helper text shown beneath the label.
- **Required**: Marks the field as mandatory for editors (enforced visually; add additional validation in custom code if needed).
- **Choices (for Select)**: Provide one or more `value : label` pairs. The value is stored; the label is shown to editors.

### Using Field Values in Templates
Retrieve field data in your theme with standard WordPress functions:
- `get_post_meta( $post_id, 'field_name', true )` to read a value.
- `the_field_name = get_post_meta( ... )` within loops for display.

## Editing, Deleting, and Staying Organized

- **Edit**: Use the Edit buttons in each list; the form loads in the same section and preserves your place.
- **Delete**: Delete actions ask for confirmation before removing configs. This does not delete existing posts or terms but removes registration; plan redirects if permalinks change.
- **Navigation**: The tab bar at the top jumps directly to Post Types, Taxonomies, or Custom Fields without unexpected redirects.

## Best Practices

- Plan labels and slugs before publishing content to minimize migration work.
- Keep **Expose to REST** enabled when using the block editor or external integrations.
- Use clear, descriptive labels so team members understand each option without docs.
- Regularly export or back up the `post_type_studio_config` option for version control across environments.

## Troubleshooting

- **“The entity being edited does not have a loaded config”**: Ensure **Expose to REST** is enabled for the affected post type so the block editor can load its REST schema.
- **Fields not showing**: Confirm the field group is attached to the post type and that you saved the configuration.
- **Taxonomy missing**: Make sure it is attached to at least one post type and the slug is unique.
