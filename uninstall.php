<?php
/**
 * Uninstall ShaplaTools.
 *
 * @package ShaplaTools
 * @since  1.0.3
 * @author Sayful Islam
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Delete Plugin Options
delete_option( 'shaplatools_options' );
