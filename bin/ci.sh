#!/bin/bash

yarn wp-env run cli wp theme activate twentytwentythree
yarn wp-env run cli wp rewrite structure /%postname%
yarn wp-env run cli wp option update blogname "AI + Block Editor"
yarn wp-env run cli wp option update blogdescription "Add AI Capabilities to the WP Block Editor."
