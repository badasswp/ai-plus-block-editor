<?php
/**
 * Bootstrap Tests.
 *
 * Set up PHPUnit related dependencies
 * for WP Mock tests.
 *
 * @package AiPlusBlockEditor
 */

// First we need to load the composer autoloader.
require_once dirname( __DIR__ ) . '/../vendor/autoload.php';

// Bootstrap WP_Mock.
WP_Mock::activateStrictMode();
WP_Mock::bootstrap();
