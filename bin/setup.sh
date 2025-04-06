#!/bin/bash

wp-env run cli wp theme activate twentytwentythree
wp-env run cli wp rewrite structure /%postname%
wp-env run cli wp option update blogname "AI + Block Editor"
wp-env run cli wp option update blogdescription "Add AI Capabilities to the WP Block Editor."
