# Uploads from Production

This plugin is meant to provide utilities for fetching and proxying images from a production server when working locally.

Currently, proxying images is implemented and it works by activating the plugin and then setting the `UPLOADS_FROM_PRODUCTION_PROXY_URL` constant in `wp-config.php`. For example:

```php
define( 'UPLOADS_FROM_PRODUCTION_PROXY_URL', 'https://eric.blog' );
```

When this constant is present, this plugin attempts to overwrite the image URLs from attachments.

## Future Work

- A WP-CLI command that will import image from the primary image from the production server and then process thumbnails locally.
