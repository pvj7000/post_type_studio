# Post Type Studio

A lightweight WordPress dashboard plugin for creating and managing custom post types, taxonomies, and reusable custom field groups without writing code.

- Version: 1.0.0

## Requirements
- WordPress 6.4 or later
- PHP 8.0 or later
- Administrator capability (`manage_options`) to access the UI

## Installation
1. Upload the plugin folder to your site's `/wp-content/plugins/` directory.
2. Activate **Post Type Studio** from **Plugins → Installed Plugins**.
3. Open **Content Types** in the admin menu to begin configuring post types, taxonomies, and fields.

## File tree

```
.
├── DOCUMENTATION.md
├── README.md
├── assets/
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
├── composer.json
├── post-type-studio.php
├── src/
│   ├── Plugin.php
│   ├── Admin/
│   │   ├── Assets.php
│   │   ├── Menu.php
│   │   ├── Notices.php
│   │   └── Screens/
│   │       ├── FieldsScreen.php
│   │       ├── PostTypesScreen.php
│   │       └── TaxonomiesScreen.php
│   ├── Domain/
│   │   ├── FieldManager.php
│   │   ├── PostTypeManager.php
│   │   ├── TaxonomyManager.php
│   │   └── Model/
│   │       ├── FieldGroup.php
│   │       ├── PostType.php
│   │       └── Taxonomy.php
│   ├── Persistence/
│   │   └── OptionsRepository.php
│   └── Utils/
│       ├── PostTypeRegistry.php
│       ├── Sanitization.php
│       └── Validation.php
├── vendor/
│   ├── autoload.php
│   └── composer/
│       ├── ClassLoader.php
│       ├── LICENSE
│       ├── autoload_classmap.php
│       ├── autoload_namespaces.php
│       ├── autoload_psr4.php
│       ├── autoload_real.php
│       └── autoload_static.php
└── views/
    ├── admin-header.php
    ├── field-group-edit.php
    ├── fields-list.php
    ├── post-type-edit.php
    ├── post-types-list.php
    ├── taxonomies-list.php
    └── taxonomy-edit.php
```

## Usage
### Managing post types
1. Go to **Content Types → Post Types**.
2. Fill **Singular label**, **Plural label**, and **Slug**. The slug auto-fills from the singular label but can be edited.
3. Toggle visibility options (public, show UI, REST support), choose supported features (title, editor, etc.), and optionally set menu icon/position.
4. Click **Save Post Type**. Existing items can be edited via the **Edit** action or removed with the delete action (a confirmation dialog prevents accidental removal).

### Managing taxonomies
1. Go to **Content Types → Taxonomies**.
2. Provide labels, slug, and select the post types the taxonomy attaches to.
3. Configure REST, UI, hierarchy, and admin column options as needed.
4. Save the taxonomy. Use **Edit** to change it later or **Delete** (with confirmation) to remove it.

### Managing field groups
1. Go to **Content Types → Custom Fields**.
2. Choose a **Group key** and **Title**, then select the post types where the group should appear.
3. Add fields via **Add field**, setting the label, name, type (text, textarea, select, checkbox), optional instructions, default value, and choices for select fields.
4. Save the field group. The fields appear as meta boxes on the selected post types and are exposed in the REST API as single-value post meta.

## Hooks
- `post_type_studio_post_types`, `post_type_studio_taxonomies`, `post_type_studio_field_groups` — filter saved configuration before registration.
- `post_type_studio_post_type_args`, `post_type_studio_taxonomy_args` — filter arguments before registering with WordPress.
- `post_type_studio_post_type_saved`, `post_type_studio_post_type_deleted`, `post_type_studio_taxonomy_saved`, `post_type_studio_taxonomy_deleted`, `post_type_studio_field_group_saved`, `post_type_studio_field_group_deleted` — action hooks for lifecycle events.

## Notes and best practices
- After creating or deleting post types or taxonomies on production sites, flush permalinks by visiting **Settings → Permalinks → Save Changes** to avoid 404s.
- Limit access to trusted administrators; all management routes already check for `manage_options` capability and nonces.
- When adding many custom fields, keep labels concise and provide clear instructions to improve editor usability.

## Support & development
- Development dependencies are installed via Composer (`composer install`).
- To customize styling or behavior, edit files in `assets/` and `views/`, then clear any admin caches to see changes.
