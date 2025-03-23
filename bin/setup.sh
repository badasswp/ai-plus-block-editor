#!/bin/bash

wp-env run cli wp rewrite structure /%postname%
wp-env run cli wp theme activate twentytwentythree
